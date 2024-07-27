<?php
$formatter = \NumberFormatter::create('fr_FR', \NumberFormatter::SPELLOUT);
$formatter->setAttribute(\NumberFormatter::FRACTION_DIGITS, 0);
$formatter->setAttribute(\NumberFormatter::ROUNDING_MODE, \NumberFormatter::ROUND_HALFUP);
include 'connexion.php';
	$db->query("SET lc_time_names='fr_FR';");
	$req=$db->prepare("SELECT CONCAT(locataire.prenom,' ', locataire.nom), CONCAT(type_logement.type_logement,' situé à ',logement.adresse), mensualite.montant, CONCAT(day(mensualite.date_versement),' ', monthname(mensualite.date_versement),' ', year(mensualite.date_versement)), mensualite.mois, location.id, mensualite.type, mensualite.id_user
FROM logement, location, locataire, mensualite, type_logement
	WHERE type_logement.id=logement.id_type AND logement.id=location.id_logement AND location.id_locataire=locataire.id AND mensualite.id_location=location.id AND mensualite.id=?");
	$req->execute(array($_GET['id']));
	$donnees=$req->fetch();
	$locataire=$donnees['0'];
	$logement=$donnees['1'];
	$montant=$donnees['2'];
	$date_versement=$donnees['3'];
	$mois=$donnees['4'];
	$id_location=$donnees['5'];
	$type=$donnees['6'];
	$id_user=$donnees['7'];

//Recupération des informations sur les mensualités 
$req=$db->prepare('SELECT CONCAT(mensualite.mois," ", mensualite.annee),location.prix_location, SUM(mensualite.montant), mensualite.type
FROM `mensualite`, location 
WHERE mensualite.id_location=location.id AND location.prix_location>=mensualite.montant AND id_location=? 
GROUP BY mensualite.mois
ORDER BY date_versement ASC');
$req->execute(array($id_location));
$nbr=$req->rowCount();
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Mensualité du <?=$date_versement ?></title>
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
		<div class="row">
			<h6 class="col s12 right-align " style="margin-top: -8px;">
			Dakar le .......<b><?=$date_versement ?></b>.....
			</h6>
			<h4 class="col s12 center" style="margin-top: -5px"><b>Quittance de caution loyer N° 00<?=str_pad($_GET['id'], 3,"0",STR_PAD_LEFT)?></b></h4>
			<div class="col s12" style="font: 16pt 'times new roman';">
				Nous déclarons avoir restituer la somme de : <b><?= number_format($montant,0,'.',' ') ?> Fcfa</b>
				.....<b><i><?=$formatter->format($montant); ?> Fcfa</i></b>....
				au ...<b><?=$locataire ?></b>...
				correspondant à la caution de l'appartement 
				....<b><?= $logement ?></b>...				
			</div>

			<h6 class="col s6 center"><u><b>Bailleur</b></u></h6>
			<h6 class="col s6 center"><u><b>Caissier(e)</b></u></h6>
		</div>	
		<br>
		<br>
		<div class="row" style="font-size: 11px;">
			<p style="border: 1px solid; padding: 1px;">
				Le paiement de la présente n'emporte pas présemtion de paiement des termes antérieurs. Cette quittance ou reçu annule tous les reçus qui aurait pu être donnés pour accompte versé sur le présent terme.
			</p>
		</div>
		
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
		.btn{
			display: none;
		}
		
	}
</style>
</html>