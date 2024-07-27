<?php
$formatter = \NumberFormatter::create('fr_FR', \NumberFormatter::SPELLOUT);
$formatter->setAttribute(\NumberFormatter::FRACTION_DIGITS, 0);
$formatter->setAttribute(\NumberFormatter::ROUNDING_MODE, \NumberFormatter::ROUND_HALFUP);
$jour = array("Dimanche","Lundi","Mardi","Mercredi","Jeudi","Vendredi","Samedi");
$mois = array("","Janvier","Février","Mars","Avril","Mai","Juin","Juillet","Août","Septembre","Octobre","Novembre","Décembre");
$datefr = $jour[date("w")]." ".date("d")." ".$mois[date("n")]." ".date("Y");
include 'connexion.php';
if (isset($_GET['id']))
{
	$date_obs=htmlspecialchars($_POST['date_obs']);
	$date_reg=htmlspecialchars($_POST['date_emission']);
	$montant=htmlspecialchars($_POST['montant']);
	$id_cons_domicile=htmlspecialchars($_GET['id']);
	$date_prescription=date('Y').'-'.date('m').'-'.date('d');

	$req=$db->prepare('SELECT id_patient FROM consultation_domicile WHERE id_consultation=?');
	$req->execute(array($id_cons_domicile));
	$donnees=$req->fetch();
	$id_patient=$donnees['0'];

	$req=$db->prepare('INSERT INTO d_regularisation (date_obs, date_reg, montant, date_prescription, id_cons_domicile, id_patient) VALUES (?, ?, ?, ?, ?, ?)');
	$req->execute(array($date_obs, $date_reg, $montant, $date_prescription, $id_cons_domicile, $id_patient));
	$nbr=$req->rowCount();
	$id_reg=$db->lastInsertId();
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
	//	window.location='d_regularisation.php';
	</script>
	<?php
	}
}
else
{
$id_reg=$_GET['id_reg'];
}
$db->query("SET lc_time_names = 'fr_FR';");
$reponse=$db->prepare("SELECT patient.id_patient, CONCAT(patient.prenom,' ', patient.nom), CONCAT(day(d_regularisation.date_obs),' ',monthname(d_regularisation.date_obs),' ',year(d_regularisation.date_obs)), CONCAT(day(d_regularisation.date_reg),' ',monthname(d_regularisation.date_reg),' ',year(d_regularisation.date_reg)), d_regularisation.montant, patient.sexe, patient.situation_matrimoniale, patient.num_dossier,  patient.annee_inscription
FROM `d_regularisation`, patient
WHERE  patient.id_patient=d_regularisation.id_patient  AND d_regularisation.id_reg=?");
$reponse->execute(array($id_reg));
$donnees=$reponse->fetch();
$id_patient=$donnees['0'];
$patient=$donnees['1'];
$date_obs=$donnees['2'];
$date_reg=$donnees['3'];
$montant=$donnees['4'];
$sexe=$donnees['5'];
$situation_matrimoniale=$donnees['6'];
$num_dossier=$donnees['7'];
$annee_inscription=$donnees['8'];


?>
<!DOCTYPE html>
<html>
	<head>
		<title>Demande de régularisation</title>
		<?php include 'entete.php'; ?>
	</head>
	<body style="background-image: url('<?=$image ?>dossier_patient.png');">
		<?php include 'verification_menu_sante.php';?>
		<a href="" class="btn" onclick="window.print();">Imprimer</a>
		<div class="container white" style="border-radius: 15px;">
			<div class="row center" >
				<img class="col s12" src="<?=$image ?>entete.jpg" >
			</div>
			<h6 class="center"><b  style="font: 22pt 'times new roman';">Demande de régularisation soins à domicile <br><b>N° <?= str_pad($id_reg, 4, "0", STR_PAD_LEFT) ?></b></b></h6>
			<div class="row" style="padding: 5px; ">
				<h4 class="col s12 center"><u></u></h4>
				<br>
				<h5 class="col s12 ">
				<?php if ($sexe=="Masculin")
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
				<b><?=$patient?></b> enregistré sous le numéro <b>N° <?=$num_dossier."/".substr($annee_inscription, -2) ?></b>
				
				N'est pas en règle pour la somme de ....<b><?=$montant?> Fcfa... <?php echo $formatter->format($montant); ?> Fcfa</b>
				<br>
				<br>
				<?php if ($sexe=="Masculin") {echo "Il";} else{echo "Elle";} ?>
				est prié<?php if ($sexe!="Masculin") {echo "e";} ?> de bien vouloir régulariser ce montant dans les plus bref délais.
				</h5>
				
				<br>
				<br>
				<h4 class="col s12 right-align">
				<b><u>La direction</u></b>
				</h4>
				
				<p class="col s12">
					<b>Fait à Dakar le  <?=$datefr ?></b>
				</p>
			</div>
		</div>
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

			
			p {
				margin-top : -5px;
			}
			.row h5{
				margin-top: -5px;
			}

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
		td{
			text-align: center;
			border:1px solid black;
		}
		
		th{
			text-align: center;
			border:1px solid black;
		}

			p{
				

				margin-top : -5px;
			}
	</style>
</html>