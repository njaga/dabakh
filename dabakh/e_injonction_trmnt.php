<?php
session_start();
include 'connexion.php';

$montant_mensuel=htmlspecialchars($_POST['montant_mensuel']);
$a_ajouter=htmlspecialchars($_POST['a_ajouter']);
$mnt_a_ajouter=htmlspecialchars($_POST['mnt_a_ajouter']);
$date_echeance=htmlspecialchars($_POST['date_echeance']);
$date_injonction=htmlspecialchars($_POST['date_injonction']);
$nbr_mois=htmlspecialchars($_POST['nbr_mois']);
$id=htmlspecialchars($_GET['id']);
$id_user=substr($_SESSION['prenom'], 0,1).".".substr($_SESSION['nom'], 0,1);
//$id_user=$_SESSION['prenom']." ".$_SESSION['nom'];

$req=$db->prepare('INSERT INTO injonction(date_injonction, nbr_mois, montant, a_ajouter, mnt_a_ajouter, date_echeance, id_locataire, id_user) values (?,?,?,?,?,?,?,?) ');
$req->execute(array($date_injonction, $nbr_mois,$montant_mensuel, $a_ajouter, $mnt_a_ajouter, $date_echeance, $id, $id_user)) or die(print_r($req->errorInfo()));
$nbr=$req->rowCount();
$id=$db->lastInsertId();
if ($nbr>0) {
?>
<script type="text/javascript">
	alert('Injonction enregistrée');
	<?php
	header("location:i_injonction.php?id=".$id);
	?>
</script>
<?php
}
else
{
?>
<script type="text/javascript">
	alert('Erreur injonction non enregistré');
	window.history.go(-1);
</script>
<?php
}
?>