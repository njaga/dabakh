<?php
include 'connexion.php';
$db->query("SET lc_time_names = 'fr_FR';");
$reponse=$db->prepare("SELECT CONCAT(day(date_consultation),' ', monthname(date_consultation),' ', year(date_consultation)), CONCAT(patient.prenom,' ',patient.nom), CONCAT(CONCAT(day(patient.date_naissance),' ', monthname(patient.date_naissance),' ', year(patient.date_naissance)), ' à ',patient.lieu_naissance), patient.allergie, patient.antecedant, patient.domicile, patient.taille, patient.profession, patient.telephone, consultation.poids, consultation.tension, consultation.poue, consultation.temperature, consultation.observation, consultation.diagnostic, consultation.ordonnance, consultation.analyse, consultation.radio,patient.poid
FROM `consultation`, patient
WHERE consultation.id_patient=patient.id_patient AND consultation.id_consultation=1");
$reponse->execute(array($_GET['id_consultation']));
$donnees=$reponse->fetch();
$date_consultation=$donnees['0'];
$patient=$donnees['1'];
$date_naissance=$donnees['2'];
$profession=$donnees['7'];
$domicile=$donnees['5'];
$telephone=$donnees['8'];
$situation_mat=$donnees['8'];
$antecedant=$donnees['4'];
$allergie=$donnees['3'];
$taille=$donnees['6']."m";
$poids=$donnees['9'];
$tension=$donnees['10'];
$poue=$donnees['11'];
$temperature=$donnees['12'];
$observation=$donnees['13'];
$diagnostic=$donnees['14'];
$ordonnance=$donnees['15'];
$analyse=$donnees['16'];
$radio=$donnees['17'];
$poids_p=$donnees['18'];
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Bulletin d'analyse du <?=$date_consultation ?></title>
		<?php include 'entete.php';?>
	</head>
	<body style="background-color: grey">
		<a href="" class="btn" onclick="window.print();">Imprimer</a>
		<table style="border:0px solid">
			<thead >
				<tr >
					<div class="row center" style="margin-bottom: 1px" >
						<img class="col s8 offset-s2" src="<?=$image ?>entete.jpg" >
					</div>
				</tr>
			</thead>
			<tbody>
				<div class="container white">
					<div class="row">
						<h6 class="col s6 right-align offset-s6">
						 Dakar le .......<b><?php echo date('d.M.Y'); ?></b>.....
						</h6>
						<br>
						<h6 class="col s12  ">
						Prénom et Nom du patient : <b><?=$patient ?></b>
						</h6>
						<h6 class="col s12  ">
						Date et lieu de naissance : <b><?=$date_naissance ?></b>
						</h6>
						<h6 class="col s12  ">
						Diagnostic : <b><?=$diagnostic ?></b>
						</h6>
					</div>
					<div class="row center">
						<h3><u><b>BULLETIN D'ANALYSE</b></u></h3>
					</div>
					<br>
					<div class="row">
						<h5 class="col s12" style="font: 18pt 'georgia'">
						<?= nl2br($analyse) ?>
						</h5>
						<br>
						<br>
						<br>
						<h5 class="col s12 right-align" style="font: 16pt 'georgia'">
							<b>Nom et cacher du prescipteur</b>
						</h5>
						<br>
						<br>
						<br><br>
						<br>
						<br><br>
						<br>
						
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
			
			{
			font: 12pt "times new roman";
			}
		}
	</style>
</html>