<?php
session_start();
include 'connexion.php';
include 'supprim_accents.php';

$id=htmlspecialchars($_GET['id']);
$quantite=htmlspecialchars($_POST['qt']);
$demandeur=htmlspecialchars(ucwords(strtolower(suppr_accents($_POST['demandeur']))));
$commentaire=htmlspecialchars(suppr_accents($_POST['commentaire']));
$date_operation=htmlspecialchars($_POST['date_operation']);

$req=$db->prepare('UPDATE consommable_utilisation SET demandeur=?, commentaire=?, quantite=?, date_operation=? WHERE id=? ');
$nbr=$req->execute(array($demandeur, $commentaire, $quantite, $date_operation, $id)) or die(print_r($req->errorInfo()));
if ($nbr>0) 
{
	//$req=$db->prepare('UPDATE consommable SET qt=qt-? WHERE id=? ');
	//$nbr=$req->execute(array($quantite, $id)) or die(print_r($req->errorInfo()));
?>
<script type="text/javascript">
	alert('Opération modifiée');
	window.location="l_consommable_user.php";
</script>
<?php
}
