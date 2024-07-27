<?php
$formatter = \NumberFormatter::create('fr_FR', \NumberFormatter::SPELLOUT);
$formatter->setAttribute(\NumberFormatter::FRACTION_DIGITS, 0);
$formatter->setAttribute(\NumberFormatter::ROUNDING_MODE, \NumberFormatter::ROUND_HALFUP);

$jour = array("Dimanche","Lundi","Mardi","Mercredi","Jeudi","Vendredi","Samedi"); 
$mois = array("","Janvier","Février","Mars","Avril","Mai","Juin","Juillet","Août","Septembre","Octobre","Novembre","Décembre"); 
$datefr = $jour[date("w")]." ".date("d")." ".$mois[date("n")]." ".date("Y");


include 'connexion.php';
if (isset($_GET['id_patient'])) 
{
	$examen=htmlspecialchars($_POST['examen']);
	$type_examen=htmlspecialchars($_POST['type_examen']);
	$renseignements=htmlspecialchars($_POST['renseignements']);
	$id_patient=htmlspecialchars($_GET['id_patient']);
	$date_prescription=date('Y').'-'.date('m').'-'.date('d');
	$req=$db->prepare('INSERT INTO d_consultation (type_examen, examen, renseignements, id_patient, date_prescription) VALUES (:type_examen, :examen,:renseignements,:id_patient,:date_prescription)');
	$req->execute(array('type_examen'=>$type_examen,'examen'=>$examen,'renseignements'=>$renseignements, 'id_patient'=>$id_patient, 'date_prescription'=>$date_prescription));
	$nbr=$req->rowCount();
	$id_cons=$db->lastInsertId();
	$req->closeCursor();
	if ($nbr>0) {
		?>
		<script type="text/javascript">
			alert('Enregistrement effectué');
			//window.location='i_regularisation.php';
		</script>
		<?php
		}
		else
		{
			?>
		<script type="text/javascript">
			alert('Erreur : enregistrement non effectué');
			//window.location='d_consultaion.php';
		</script>
		<?php
		}
}
else
{
	$id_cons=$_GET['id_cons'];
}

$db->query("SET lc_time_names = 'fr_FR';");
$reponse=$db->prepare("SELECT patient.id_patient, CONCAT(patient.prenom,' ', patient.nom), d_consultation.examen, d_consultation.renseignements, patient.sexe, patient.situation_matrimoniale, CONCAT(CONCAT(day(patient.date_naissance),' ', monthname(patient.date_naissance),' ', year(patient.date_naissance)), ' à ',patient.lieu_naissance), type_examen
FROM d_consultation, patient 
WHERE patient.id_patient=d_consultation.id_patient AND d_consultation.id_cons=?");
$reponse->execute(array($id_cons));
$donnees=$reponse->fetch();
$id_patient=$donnees['0'];
$patient=$donnees['1'];
$examen=$donnees['2'];
$renseignements=$donnees['3'];
$sexe=$donnees['4'];
$situation_matrimoniale=$donnees['5'];
$date_naissance=$donnees['6'];
$type_examen=$donnees['7'];
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Demande de consultation</title>
		<meta charset="utf-8">
		<!--Import materialize.min.css-->
		<link type="text/css" rel="stylesheet" href="../css/materialize.min.css"  media="screen,projection"/>
		<link type="text/css" rel="stylesheet" href="../css/icones.css"  media="screen,projection"/>
		<link type="text/css" rel="stylesheet" href="../css/formulaire.css"  media="screen,projection"/>
		<!--Let browser know website is optimized for mobile-->
		<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
		<!--Import jQuery before materialize.js-->
		<script type="text/javascript" src="../js/jquery.min.js"></script>
		<script type="text/javascript" src="../js/materialize.min.js"></script>
	</head>
	<body style="background-image: url('../css/images/dossier_patient.png');">
		<?php include 'verification_menu_sante.php';?>
		<a href="" class="btn" onclick="window.print();">Imprimer</a>
		<table style="border:0px solid">
			<thead >
				<tr >
					<div class="row center" style="margin-bottom: 1px" >
						<img class="col s8 offset-s2" src="../css/images/entete.jpg" >
					</div>
				</tr>
			</thead>
			<tbody>
				<div class="container white" style="border-radius: 15px;">
					<div class="row" style="padding: 5px; ">
						<h4 class="col s12 center"><u>Demande <?= $type_examen ?></u></h4>
						<br>
						<h5 class="col s12 ">
							Prénom et Nom : 
							<b><?php if ($sexe=="Masculin") 
									{
										echo "Mr ";
									}
									else
									{
										if ($situation_matrimoniale=="Celibataire") 
										{
											echo "Mlle ";
										}
										else
										{
											echo "Mme ";
										}
									}
							?>
							<?=$patient?></b>...............
							<br>
							<br>
							Née le ..<b><?= $date_naissance ?></b>...
							<br>
							<br>
							SEXE : <b><?=$sexe ?></b>
							<br>
							<br>
							Renseignements clinique :<br>
							<b><?= nl2br($renseignements) ?></b>
							<br>
							<br>
							<u>Examen(s) demandé(s) : </u><br>
							<b><?=nl2br($examen)?></b>
						</h5>
						
						<br>
						<br>
						<h6 class="col s12 right-align">
							<b><u>Cachet et non du prescripteur</u></b>
						</h6>
						
						<p class="col s12">
							<b>Fait à Dakar le  <?=$datefr ?></b>
						</p>
					</div>
				</div>
			</tbody>
		</table>
	</body>
	<script type="text/javascript">
		$(document).ready(function () {
			window.print();
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
			margin: 10px;
			margin: 5px;
		}
		@media print
		{
			.btn, a{
				display: none;
			}
			div
			{
			font: 12pt "times new roman";
			}
			nav{
				display: none;
			}
			.entete_img{
				display: none;
			}
		}
	</style>
</html>