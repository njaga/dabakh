<?php
$formatter = \NumberFormatter::create('fr_FR', \NumberFormatter::SPELLOUT);
$formatter->setAttribute(\NumberFormatter::FRACTION_DIGITS, 0);
$formatter->setAttribute(\NumberFormatter::ROUNDING_MODE, \NumberFormatter::ROUND_HALFUP);
include 'connexion.php';
	$db->query("SET lc_time_names='fr_FR';");
	$req=$db->prepare("SELECT CONCAT(patient.prenom,' ',patient.nom), CONCAT(day(date_consultation),' ', monthname(date_consultation),' ', year(date_consultation)), consultation.montant, consultation.diagnostic, patient.profession, patient.domicile 
FROM consultation, patient
WHERE consultation.id_patient=patient.id_patient AND consultation.id_consultation=?");
	$req->execute(array($_GET['id']));
	$donnees=$req->fetch();
	$patient=$donnees['0'];
	$date_consultation=$donnees['1'];
	$montant=$donnees['2'];
	$diagnostic=$donnees['3'];
	$profession=$donnees['4'];
	$domicile=$donnees['5'];
	$date_facture=date("d/m/y");

?>
<!DOCTYPE html>
<html>
	<head>
		<title>Facture</title>
		<?php include 'entete.php';?>
	</head>
	<body style="background-color: grey">
		<a href="" class="btn "  onclick="window.print();">Imprimer</a>
		<a href="l_consultation.php" class="btn " >Retour</a>
		<table style="border:0px solid">
			<thead >
				<tr >
					<div class="row center white" style="margin-bottom: 1px" >
						<img class="col s8 offset-s2" src="<?=$image ?>entete.jpg" >
					</div>
				</tr>
			</thead>
			<tbody>
				<div class="container white">
					<div class="row">
						<h5 class="col s12 right-align ">
						 Dakar le .......<b><?=$date_facture ?></b>.....
						</h5>
						<h5 class="col s12 right-align ">
						 <?=$patient?><br>
						 <?=$profession?><br>
						 <?=$domicile?><br>
						</h5>
						<h3 class="col s12 center"><b>Facture consultation N° 00<?=$_GET['id'] ?></b></h3>
						<br>
						<br>
						<div class="col s12" style="font: 22pt 'times new roman';">
							A la suite d'une consultation le .......<b><?=$date_consultation?> </b>........ <br><br>
							Nous déclarons avoir reçu la somme de : <b><?= number_format($montant,0,'.',' ') ?> Fcfa</b><br><br>
							.....<b><i><?=$formatter->format($montant); ?> Fcfa</i></b>....du patient
							<br><br>correspondant à nos honoraires 
						</div>
						<br>
						<br>
						<p class="col s12"></p>
						<p class="col s12"></p>
						<p class="col s12"></p>
						<h5 class="col s12 right-align"><u><b>Signature</b></u></h5>
					</div>
				</div>
			</tbody>
		</table>
	</body>
	<style type="text/css">
	
		/*import du css de materialize*/
		@import "../css/materialize.min.css" print;
		/*CSS pour la page à imprimer */
		/*Dimension de la page*/
		@page
		{
			size: portrait;
			margin: 10px;
			margin: 5px;
		}
		@media print
		{
			.btn{
				display: none;
			}
			
		}
	</style>
</html>