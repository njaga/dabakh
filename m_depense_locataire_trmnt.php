<?php
session_start();
include 'connexion.php';
include 'supprim_accents.php';
$motif=htmlspecialchars(ucfirst(strtolower(suppr_accents($_POST['motif']))));
$mois=htmlspecialchars($_POST['mois']);
$annee=htmlspecialchars($_POST['annee']);
$type_depense=htmlspecialchars($_POST['type_depense']);
$date_depense=htmlspecialchars($_POST['date_depense']);
$montant=htmlspecialchars($_POST['montant']);
$id_user=substr($_SESSION['prenom'], 0,1).".".substr($_SESSION['nom'], 0,1);
//$id_user=$_SESSION['prenom']." ".$_SESSION['nom'];

$req=$db->prepare('UPDATE depense_locataire SET motif=?, mois=?, annee=?, type_depense=?, date_depense=?, montant=? WHERE id=? ');
$req->execute(array($motif, $mois, $annee, $type_depense, $date_depense, $montant,  $_GET['id'])) or die(print_r($req->errorInfo()));

$req=$db->prepare('UPDATE caisse_immo SET  motif=?, montant=?, date_operation=? WHERE id_cotisation_locataire=? ');
$req->execute(array($motif, $montant, $date_depense, $_GET['id'])) or die(print_r($req->errorInfo()));
?>
<script type="text/javascript">
	alert('Cotisation modifée');
	window.location="l_depense_locataire.php";
</script>
