<?php
$formatter = \NumberFormatter::create('fr_FR', \NumberFormatter::SPELLOUT);
$formatter->setAttribute(\NumberFormatter::FRACTION_DIGITS, 0);
$formatter->setAttribute(\NumberFormatter::ROUNDING_MODE, \NumberFormatter::ROUND_HALFUP);
include 'connexion.php';
$db->query("SET lc_time_names='fr_FR';");


$req=$db->prepare('SELECT injonction.nbr_mois, injonction.montant, injonction.a_ajouter, CONCAT(day(injonction.date_echeance)," ",monthname(injonction.date_echeance)," ",year(injonction.date_echeance)), locataire.num_dossier, locataire.annee_inscription, CONCAT(locataire.prenom," ",locataire.nom), CONCAT(bailleur.prenom," ",bailleur.nom), CONCAT(DATE_FORMAT(now(), "%d"), "/", DATE_FORMAT(now(), "%m"),"/", DATE_FORMAT(now(), "%Y")), CONCAT(DATE_FORMAT(date_injonction, "%d"), "/", DATE_FORMAT(date_injonction, "%m"),"/", DATE_FORMAT(date_injonction, "%Y")), injonction.id_user, injonction.mnt_a_ajouter
FROM `injonction`, locataire, location, logement, bailleur 
WHERE injonction.id_locataire=locataire.id AND locataire.id=location.id_locataire AND location.id_logement=logement.id AND logement.id_bailleur=bailleur.id  AND injonction.id=?');
$req->execute(array($_GET['id']));
$donnees=$req->fetch();
$nbr_mois=$donnees['0'];
$montant=$donnees['1'];
$a_ajouter=$donnees['2'];
$date_echeance=$donnees['3'];
$num_dossier=$donnees['4'];
$annee_inscription=$donnees['5'];
$locataire=$donnees['6'];
$bailleur=$donnees['7'];
$date_actuelle=$donnees['8'];
$date_injonction=$donnees['9'];
$id_user=$donnees['10'];
$mnt_a_ajouter=$donnees['11'];
	
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Injonction du <?=$date_injonction ?></title>
		<?php include 'entete.php';?>
	</head>
	<body style="background-image: url(<?= $image ?>bgaccueil.jpg);">
		<a href="" class="btn "  onclick="window.print();">Imprimer</a>
		<a href="l_injonction.php" class="btn " >Retour au menu</a>
		
	
	<div class="container white">
		<div class="row center white" style="margin-bottom: 1px" >
			<img class="col s8 offset-s2" src="../css/images/banniere_immo.png" >
			<p class="col s6 left-align" style="font-family: 'times new roman'; font-size: 8px">Imprimé le <?=$date_actuelle ?></p>
			<p class="col s6 right-align" style="font-family: 'times new roman'; font-size: 8px"><?=$id_user ?></p>
		</div>
		<div class="row">
			<h5 class="col s12 center"><b>INJONCTION DE PAYER LOYERS EN RETARD</b></h5>
			<div class="col s12" style="font: 16pt 'times new roman';">
				Dossier : <b><?=str_pad($num_dossier, 4,"0",STR_PAD_LEFT ) ?>/<?=substr($annee_inscription, 2) ?> </b>
			</div>
			<div class="col s12" style="font: 16pt 'times new roman';">
				Mr/Mme : <b><?=$locataire ?> </b>
				<br>
				<br>
			</div>

				<span style="font: 12pt 'times new roman';">
				C'est en vertu des dispositions du CONTRAT de prestation : SURVEILLANCE, GERENCE et RECOUVREMENT qui nous lie à  :<br> 
				Mr/Mme <b><?=$bailleur ?></b><br>
				Que nous venons par la présente vous demander de REGULARISER vos <b><?=$nbr_mois ?></b> <b><i>(<?=$formatter->format(($nbr_mois )); ?>)</i></b> mois de loyer impayés à ce jour : le <b><?=$date_injonction ?></b>
				</span>
				<br>
				<span class="truncate"  style="font: 12pt 'times new roman';"> Soit .......<b><?=number_format($montant,0,'.',' ') ?></b>......<b><i>(<?=$formatter->format($montant); ?>)</i></b> FCFA............................................................................................................................................................................................................................</span> 
				<span class="truncate" style="font: 12pt 'times new roman';"> A ajouter  : .......<b><?=$a_ajouter ?></b>......<b><?=number_format($mnt_a_ajouter,0,'.',' ') ?></b> <b><i>(<?=$formatter->format($mnt_a_ajouter); ?>)</i></b> FCFA............................................................................................................................................................................................................................</span>
				<br>
				<span class="truncate" style="font: 12pt 'times new roman';">TOTAL à régulariser : .......<b><?=number_format(($mnt_a_ajouter+$montant ),0,'.',' ') ?></b>......<b><i>(<?=$formatter->format(($mnt_a_ajouter+$montant )); ?>)</i></b> FCFA............................................................................................................................................................................................................................</span>
				<br>
				<span class="truncate" style="font: 12pt 'times new roman';">
				Ceci avant  le: .............<b><?=$date_echeance ?></b>.............................................................................................................. 
				</span>
				<span style="font: 12pt 'times new roman';">
					<br>
					<br>
					<br>
					NOTA : Faute de donner une bonne suite à cette RECOMMANDATION de vous METTRE A JOUR AVEC VOS LOYERS, vous obligerez à retenir votre refus de payer vos loyers et D'ENGAGER A VOTRE ENCONTRE UNE PROCEDURE TENDANT A EXPULSION ET A RECOUVRIR la créance de votre propriétaire ; ceci après avoir pris des mesures censertoires pour garantir le paiement de notre créance plus tous les frais engendrés.
					<br>
					<br>
					Nous vous souhaitons une bonne réception et osons espérer que vous saurez vous éviter certains désagréments.
					<br>
					<br>
				</span>
			</div>
			<h6 class="col s12 right-align " >
			Fait le <b><?=$date_injonction ?></b>
			</h6>
			<h6 class="col s6 right-align offset-s6"><b>LA DIRECTION</b></h6>
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
		size: A4 portrait ;
		margin-top: 25px;
		margin-bottom: 25px;

	}
	@media print
	{
		.btn{
			display: none;
		}
		
	}
</style>
</html>