<?php
session_start();
include 'connexion.php';
$db->query("SET lc_time_names = 'fr_FR';");
$reponse=$db->prepare("SELECT patient.id_patient, patient.prenom, patient.nom, CONCAT(day(patient.date_naissance),' ',monthname(patient.date_naissance),' ',year(patient.date_naissance)), patient.lieu_naissance, patient.profession, patient.domicile, patient.telephone, patient.sexe, patient.situation_matrimoniale, consultation.poids, consultation.temperature, consultation.pouls, consultation.tension, consultation.glycemie, consultation.tdr, consultation.plaintes, CONCAT(day(consultation.date_consultation), ' ', monthname(consultation.date_consultation),' ', year(consultation.date_consultation)), consultation.ant_medicaux, consultation.ant_chirurgicaux, consultation.traitement_cours, consultation.allergie, consultation.histoire_maladie, consultation.neurologie, consultation.hemodynamique, consultation.respiratoire, consultation.autres_appareils, consultation.ecg, consultation.biologie, consultation.radiographie, consultation.tdm,  consultation.echographie, consultation.autres_examen, consultation.id_service, consultation.traitement, consultation.evolution, consultation.traitement_sortie, consultation.resume, consultation.resume , patient.num_dossier, patient.annee_inscription, consultation.spo2
FROM `consultation`, patient
WHERE patient.id_patient=consultation.id_patient AND consultation.id_consultation=?");
$reponse->execute(array($_GET['id']));
$donnees=$reponse->fetch();
$id_patient=$donnees['0'];
$patient=$donnees['1']." ".$donnees['2'];
$date_naissance=$donnees['3'];
$lieu_naissance=$donnees['4'];
$profession=$donnees['5'];
$domicile=$donnees['6'];
$telephone=$donnees['7'];
$sexe=$donnees['8'];
$situation_mat=$donnees['9'];
$poids=$donnees['10'];
$temperature=$donnees['11'];
$pouls=$donnees['12'];
$tension=$donnees['13'];
$glycemie=$donnees['14'];
$tdr=$donnees['15'];
$plaintes=$donnees['16'];
$date_consultation=$donnees['17'];
$ant_medicaux=$donnees['18'];
$ant_chirurgicaux=$donnees['19'];
$traitement_cours=$donnees['20'];
$allergie=$donnees['21'];
$histoire_maladie=$donnees['22'];
$neurologie=$donnees['23'];
$hemodynamique=$donnees['24'];
$respiratoire=$donnees['25'];
$autres_appareils=$donnees['26'];
$ecg=$donnees['27'];
$biologie=$donnees['28'];
$radiographie=$donnees['29'];
$tdm=$donnees['30'];
$echographie=$donnees['31'];
$autres_examen=$donnees['32'];
$id_service=$donnees['33'];
$traitement=$donnees['34'];
$evolution=$donnees['35'];
$traitement_sortie=$donnees['36'];
$resume=$donnees['37'];
$num_dossier=$donnees['38'];
$annee_inscription=$donnees['39'];
$spo2=$donnees['41'];
$num_dossier=str_pad($num_dossier,3,"0",STR_PAD_LEFT)."/".substr($annee_inscription, -2);
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Infos consultation du <?=$date_consultation ?></title>
		<?php include 'entete.php';?>
	</head>
	<body style="background-image: url('<?=$image ?>i_consultation.png'); font: 12pt 'times new roman';">
		<a href="" class="btn" onclick="window.print();">Imprimer</a>
		&nbsp &nbsp &nbsp
		<?php if ($_SESSION['fonction']!="infirmier") {
			?>
			<a href="m_consultation.php?id=<?=$_GET['id'] ?>" class="btn" >Modifier</a>
			&nbsp &nbsp &nbsp
			<?php
		}
		?>
		<a href="" class="btn" onclick="window.close();">Fermer</a>
		<div class="container  white" style="padding:  10px">
			<div class="row center " >
				<img class="col s12" src="../css/images/entete.jpg" >
			</div>
			<div class="row">
				<div class="s4  offset-s8 right">Imprimé le <?= date('d')."/".date('m')."/".date('y') ?></div>	
			<?php 
				if ($sexe=="Masculin") 
				{
					echo '<h4 class="center">Compte rendu médical du patient</h4>';
				}
				else
				{
					echo'<h4 class="center">Compte rendu médical de la patiente</h4>';
				}
			?>
			</div>
			<br>
			<!--Dossier du patient -->
			<div class="row">
				<p class="col s6">
				Prénom et Nom : <b><?=$patient?></b><br>
				Profession &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp: <b><?=$profession?></b><br>
				Date et lieu de naissance : <b><?=$date_naissance?></b>
				</p>
				<p class="col s6">
					Domicile &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp: <b><?=$domicile?></b><br>
					Téléphone &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp: <b><?=$telephone?></b><br>
				</p>
			</div>
			<div class="row">
				<div class="col s2">Poids : <b><?=$poids ?></b></div>
				<div class="col s2">Pouls : <b><?=$pouls ?></b></div>
				<div class="col s2">Tension : <b><?=$tension ?></b></div>
				<div class="col s2">Température : <b><?=$temperature ?></b></div>
				<div class="col s2">Glycémie : <b><?=$glycemie ?></b></div>
				<div class="col s2">spO2 : <b><?=$spo2 ?></b></div>
				<div class="col s2">TDR : <b><?=$tdr ?></b></div><br><br>
				<p class="col s12"><b>Plaintes : </b><?= nl2br($plaintes)?></p>
			</div>


			<div class="row">
				<h6 class="col s12"><b><u>Histoire de la maladie</u></b></h6>
				<?=nl2br($histoire_maladie)?>
			</div>
			<div class="row">
				<h6 class="col s12"><b><u>Antécedant</u></b></h6>
				<p class="col s12"><u>Medicaux :</u> &nbsp<?=$ant_medicaux?></p> 
				<p class="col s12"><u>Chirurgicaux :</u> &nbsp<?=$ant_chirurgicaux ?></p>
				<p class="col s12"><u>Traitement en cours :</u> &nbsp<?=$ant_chirurgicaux ?></p>
				<p class="col s12"><u>Allergie :</u> &nbsp<?=$allergie ?></p>
			</div>

			<div class="row">
				<h6 class="col s12"><b><u>Examen des appareils</u></b></h6>
				<p class="col s12"><u>Neurologie :</u><?=$neurologie ?></p>
				<p class="col s12"><u>Hemodynamique :</u> &nbsp<?=$hemodynamique ?></p>
				<p class="col s12"><u>Respiratoire :</u> &nbsp<?=$respiratoire ?></p>
				<p class="col s12"><u>Autres appareils :</u> &nbsp<?=$autres_appareils ?></p>
			</div>
			
			<div class="row">
				<h6 class="col s12"><b><u>Examens complémentaires</u></b></h6>
				<p class="col s12"><u>ECG :</u> &nbsp<?=$ecg ?></p>
				<p class="col s12"><u>Biologie :</u> &nbsp<?=$biologie ?></p>
				<p class="col s12"><u>Radiographie :</u> &nbsp<?=$radiographie ?></p>
				<p class="col s12"><u>TDM : </u> &nbsp<?=$tdm ?></p>
				<p class="col s12"><u>Echographie :</u> &nbsp<?=$echographie ?></p>
				<p class="col s12">Autres : &nbsp<?=$autres_examen ?></p>
				<p class="col s12"><b><u>Traitement :</u></b> &nbsp<?=$traitement ?></p>
			</div>
			<div class="row">
				<p class="col s12"><u>Evolution :</u> &nbsp<?=$evolution ?></p>
				<p class="col s12"><u>Traitement de sortie :</u> &nbsp<?=$traitement_sortie ?></p>
				<p class="col s12"><b><u>Résumé</u></b> <br> <?=nl2br($resume)?></p>
			</div>
		</div>
		
	</body>
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
			margin-top: 10px;
		}
		@media print
		{
			.btn{
				display: none;
			}
			
			p {
				margin-top : -5px;
			}
			.row h6{
				margin-top: -5px;
			}
			
		}
		td{
			border:1px solid black;
		}

		
			img
			{
				margin-top: 10px;
			}
			p{
				

				margin-top : -5px;
			}
			.row{
				margin-top: -20px;
			}
	</style>
</html>