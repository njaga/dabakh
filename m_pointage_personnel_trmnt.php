<?php
session_start();
include 'connexion.php';
$heure_fin=$_POST['heure_fin'];
$heure_debut=$_POST['heure_debut'];
$observation=$_POST['observation'];
$observation_ad=$_POST['observation_ad'];

$req=$db->prepare('UPDATE pointage_personnel SET heure_debut=?, heure_fin=?, observation=?, observation_ad=? WHERE id=?');
	$req->execute(array($heure_debut, $heure_fin, $observation, $observation_ad, $_GET['id']));
	$nbr=$req->rowCount();
?>
<script type="text/javascript">
	alert('Pointage modifié');
	window.history.go(-2);
</script>
