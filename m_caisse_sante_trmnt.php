<?php
include 'connexion.php';
$type=htmlspecialchars($_POST['type']);
$section=htmlspecialchars($_POST['section']);
$motif=htmlspecialchars(ucfirst(strtolower($_POST['motif'])));
$montant=htmlspecialchars($_POST['montant']);
$date_operation=htmlspecialchars($_POST['date_operation']);
$pj=htmlspecialchars($_POST['pj']);

$req=$db->prepare('UPDATE caisse_sante SET type=?, section=?, motif=?, montant=?,date_operation=?, pj=? WHERE id_operation=?');
$nbr=$req->execute(array($type, $section, $motif, $montant, $date_operation, $pj, $_GET['id_operation'])) or die(print_r($req->errorInfo()));
if ($nbr>0) {
?>
<script type="text/javascript">
	alert('Opération modifiée');
	window.location="etat_caisse_sante.php";
</script>
<?php
}
else
{
?>
<script type="text/javascript">
	alert('Erreur operation non modifiée');
	window.history.go(-1);
</script>
<?php
}
?>