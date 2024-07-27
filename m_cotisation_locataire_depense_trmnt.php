<?php
session_start();
include 'connexion.php';
include 'supprim_accents.php';
$motif=htmlspecialchars(ucfirst(strtolower(suppr_accents($_POST['motif']))));
$montant_a_payer=htmlspecialchars($_POST['montant_a_payer']);
$date_depense=htmlspecialchars($_POST['date_depense']);
$montant_payer=htmlspecialchars($_POST['montant']);
//$id_user=substr($_SESSION['prenom'], 0,1).".".substr($_SESSION['nom'], 0,1);
$id_user=$_SESSION['prenom']." ".$_SESSION['nom'];
$reliquat=$montant_a_payer-$montant_payer;

$req=$db->prepare('UPDATE cotisation_locataire_depense SET date_depense=?, motif=?, montant_a_regler=?, montant_regler=?, reliquat=?, id_user=? WHERE id=?');
$req->execute(array($date_depense, $motif, $montant_a_payer, $montant_payer, $reliquat, $id_user, $_GET['id'])) or die(print_r($req->errorInfo()));
$nbr=$req->rowCount();

$req=$db->prepare('UPDATE  caisse_immo SET motif=?, montant=?, date_operation=?, id_user=? WHERE id_cotisation_depense=?');
$req->execute(array($motif, $montant_payer, $date_depense, $id_user, $_GET['id'])) or die(print_r($req->errorInfo()));
?>
<script type="text/javascript">
	alert('Cotisation enregistrée');
	window.location="l_cotisation_locataire_depense.php";
</script>
