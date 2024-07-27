<?php
session_start();
$formatter = \NumberFormatter::create('fr_FR', \NumberFormatter::SPELLOUT);
$formatter->setAttribute(\NumberFormatter::FRACTION_DIGITS, 0);
$formatter->setAttribute(\NumberFormatter::ROUNDING_MODE, \NumberFormatter::ROUND_HALFUP);
include 'connexion.php';
	$db->query("SET lc_time_names='fr_FR';");
	$req=$db->prepare("SELECT CONCAT(locataire.prenom,' ', locataire.nom), CONCAT(type_logement.type_logement,' situé à ',logement.adresse), location.prix_location
FROM logement, location, locataire, type_logement
	WHERE type_logement.id=logement.id_type AND logement.id=location.id_logement AND location.id_locataire=locataire.id AND location.id=?");
	$req->execute(array($_GET['id']));
	$donnees=$req->fetch();
	$locataire=$donnees['0'];
	$logement=$donnees['1'];
	$montant=$donnees['2'];
	$date_actuelle=date('d')."/".date('m').date("y");
	$id_user=$_SESSION['prenom']." ".$_SESSION['nom'];

?>
<!DOCTYPE html>
<html>
	<head>
		<title>Mensualité impayé</title>
		<?php include 'entete.php';?>
	</head>
	<body style="background-image: url(<?= $image ?>bgaccueil.jpg);">
		<a href="" class="btn "  onclick="window.print();">Imprimer</a>
		<a href="immobilier.php" class="btn " >Retour au menu</a>
		
	
	<div class="container white">
		<div class="row center white" style="margin-bottom: 1px" >
			<img class="col s8 offset-s2" src="../css/images/banniere_immo.png" >
			<p class="col s12 right-align" style="font-family: 'times new roman'; font-size: 8px"><?=$id_user ?></p>
		</div>
		<h4 class="center"><b>Facture impayée du mois de <?=$_GET['m'] ?></b></h4>
		<p style="font: 13pt 'times new roman';">
			Mr/Mme <b><?=$locataire ?></b><br>
			Sauf erreur ou omission de notre part, le paiement de la facture du mois de <b><?=$_GET['m'] ?></b> correspondant à la location de : <b><?=$logement ?></b> pour un montant de <b><?= number_format($montant,0,'.',' ') ?> Fcfa</b> .....<b><i><?=$formatter->format($montant); ?> Fcfa</i></b>.... n'a toujours pas été régler.
			<br>
			Nous vous prions de bien vouloir procéder à son règlement dans les meilleurs délais.
			<br>
			Vous remerciant de faire le nécessaire, et restant à votre entière disposition pour toute éventuelle question, nous vous prions d'agréer, Mr/Mme l'expression de nos salutations distinguées.
		</p>
		<h6 class="col s12 right"><u><b>La direction</b></u></h6>
		<br>
		<br>
		<br>
		<br>
		<br>
		<br>
		<br>
		<div class="row center white" style="margin-bottom: 1px" >
			<img class="col s8 offset-s2" src="../css/images/banniere_immo.png" >
			<p class="col s12 right-align" style="font-family: 'times new roman'; font-size: 8px"><?=$id_user ?></p>
		</div>
		<h4 class="center"><b>Facture impayée du mois de <?=$_GET['m'] ?></b></h4>
		<p style="font: 13pt 'times new roman';">
			Mr/Mme <b><?=$locataire ?></b><br>
			Sauf erreur ou omission de notre part, le paiement de la facture du mois de <b><?=$_GET['m'] ?></b> correspondant à la location de : <b><?=$logement ?></b> pour un montant de <b><?= number_format($montant,0,'.',' ') ?> Fcfa</b> .....<b><i><?=$formatter->format($montant); ?> Fcfa</i></b>.... n'a toujours pas été régler.
			<br>
			Nous vous prions de bien vouloir procéder à son règlement dans les meilleurs délais.
			<br>
			Vous remerciant de faire le nécessaire, et restant à votre entière disposition pour toute éventuelle question, nous vous prions d'agréer, Mr/Mme l'expression de nos salutations distinguées.
		</p>
		<h6 class="col s12 right"><u><b>La direction</b></u></h6>
	</div>
</body>
<script type="text/javascript">
	$(document).ready(function () {
	})
</script>
<style type="text/css">
	/*import du css de materialize*/
	@import "../css/materialize.min.css" print;
	/*CSS pour la page à imprimer */
	/*Dimension de la page*/
	@page
	{
		size: portrait;
		margin: 0px;
		margin-bottom: 10px;
		margin-top: 1px;
	}
	@media print
	{
		h4{
			margin-top: -20px;
		}

		.btn{
			display: none;
		}
		
	}
</style>
</html>