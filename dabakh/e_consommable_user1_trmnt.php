<?php
session_start();
include 'connexion.php';
include 'supprim_accents.php';

$id=htmlspecialchars($_GET['id']);
$quantite=htmlspecialchars($_POST['qt']);
$demandeur=htmlspecialchars(strtoupper(suppr_accents($_POST['demandeur'])));
$commentaire=htmlspecialchars(suppr_accents($_POST['commentaire']));
$date_operation=htmlspecialchars($_POST['date_operation']);
$id_user=$_SESSION['prenom']." ".$_SESSION['nom'];

$req=$db->prepare('INSERT INTO consommable_utilisation(demandeur, commentaire, quantite, id_consommable, date_operation, id_user) values (?,?,?,?,?,?) ');
$nbr=$req->execute(array($demandeur, $commentaire, $quantite, $id, $date_operation, $id_user)) or die(print_r($req->errorInfo()));
if ($nbr>0) 
{
	$req=$db->prepare('UPDATE consommable SET qt=qt-? WHERE id=? ');
	$nbr=$req->execute(array($quantite, $id)) or die(print_r($req->errorInfo()));
?>
<script type="text/javascript">
	alert('Opération enregistrée');
	window.location="l_consommable_user.php";
</script>
<?php
}
