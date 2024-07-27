<?php
session_start();
include 'connexion.php';

$id_consommable=htmlspecialchars($_POST['id_consommable']);
$qt_restante=htmlspecialchars($_POST['qt_restante']);
$qt=htmlspecialchars($_POST['qt']);
$pu=htmlspecialchars($_POST['pu']);
$montant=$pu*$qt;
$date_ravitaillement=htmlspecialchars($_POST['date_ravitaillement']);
$qt_total=$qt+$qt_restante;
$id_user=$_SESSION['prenom']." ".$_SESSION['nom'];

$req=$db->prepare('INSERT INTO ravitaillement_consommable (date_ravitaillement, qt, ancien_qt, id_consommable, montant, id_user) VALUES (?,?,?,?,?,?)');
$req->execute(array($date_ravitaillement, $qt_total, $qt_restante, $id_consommable, $montant, $id_user)) or die(print_r($req->errorInfo()));
$id_ravitaillement=$db->lastInsertId();
$nbr=$req->rowCount();
if ($nbr>0) {

	$req=$db->prepare('UPDATE consommable SET qt=?, id_ravitaillement=? WHERE id=?');
	$req->execute(array($qt_total, $id_ravitaillement, $id_consommable)) or die(print_r($req->errorInfo()));
	$nbr=$req->rowCount();
?>
<script type="text/javascript">
	alert('Ravitaillement ajouté');
	window.location="l_ravitaillement_consommable.php";
</script>
<?php
}
else
{
?>
<script type="text/javascript">
	alert('Erreur Ravitaillement non enregistrée');
	window.history.go(-1);
</script>
<?php
}
?>