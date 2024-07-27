<?php
$formatter = \NumberFormatter::create('fr_FR', \NumberFormatter::SPELLOUT);
$formatter->setAttribute(\NumberFormatter::FRACTION_DIGITS, 0);
$formatter->setAttribute(\NumberFormatter::ROUNDING_MODE, \NumberFormatter::ROUND_HALFUP);
$jour = array("Dimanche","Lundi","Mardi","Mercredi","Jeudi","Vendredi","Samedi");
$mois = array("","Janvier","Février","Mars","Avril","Mai","Juin","Juillet","Août","Septembre","Octobre","Novembre","Décembre");
$datefr = $jour[date("w")]." ".date("d")." ".$mois[date("n")]." ".date("Y");
include 'connexion.php';
$db->query("SET lc_time_names = 'fr_FR';");

if (isset($_GET['id_pe'])) 
{
	$date_obs=htmlspecialchars($_POST['date_obs']);
	$date_reg=htmlspecialchars($_POST['date_emission']);
	$montant=htmlspecialchars($_POST['montant']);
	$id_patient_externes=htmlspecialchars($_GET['id_pe']);
	$date_prescription=date('Y').'-'.date('m').'-'.date('d');

	$req=$db->prepare('INSERT INTO d_regularisation (date_obs, date_reg, montant, date_prescription, id_patient_externes) VALUES (?, ?, ?, ?, ?)');
	$req->execute(array($date_obs, $date_reg, $montant, $date_prescription,  $id_patient_externes));
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

$reponse_reg=$db->prepare("SELECT patient_externe.id, CONCAT(patient_externe.prenom,' ',patient_externe.nom), CONCAT(day(d_regularisation.date_obs),' ',monthname(d_regularisation.date_obs),' ',year(d_regularisation.date_obs)), CONCAT(day(d_regularisation.date_reg),' ',monthname(d_regularisation.date_reg),' ',year(d_regularisation.date_reg)), d_regularisation.montant, patient_externe.sexe, d_regularisation.id_reg
FROM `d_regularisation`
INNER JOIN patient_externe ON d_regularisation.id_patient_externes=patient_externe.id
WHERE  d_regularisation.id_reg=?");
$reponse_reg->execute(array($id_reg));
$donnees=$reponse_reg->fetch();
$id_patient=$donnees['0'];
$patient=$donnees['1'];
$date_obs=$donnees['2'];
$date_reg=$donnees['3'];
$montant=$donnees['4'];
$sexe=$donnees['5'];
$id=$donnees['6'];
$total=0;
$total1=0;
$total2=0;

//analyses
$req_analyse=$db->prepare('SELECT analyse.analyse, analyse_patient.montant 
FROM `analyse_patient`, analyse
WHERE analyse_patient.id_analyse=analyse.id AND analyse_patient.id_patient=?');
$req_analyse->execute(array($id_patient));
//soins externes
$req_soins=$db->prepare('SELECT soins_externes.soins, soins_externes_patient.montant
FROM  soins_externes,`soins_externes_patient` 
WHERE soins_externes_patient.id_soins=soins_externes.id AND soins_externes_patient.id_patient=?');
$req_soins->execute(array($id_patient));

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
			<h6 class="center"><b  style="font: 22pt 'times new roman';">Demande de régularisation  <br><b>N° <?= str_pad($id, 4, "0", STR_PAD_LEFT) ?></b></b></h6>
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
							echo "Mme ";
						}
				?>
				<b><?=$patient?></b> enregistré sous le numéro <b>N° <?=$id_patient ?></b>
				<br>
				<br>
				Ayant subit à la date du <b><?=$date_obs?></b> les actes suivants :<br>
				<div class="row">
					<h5 class="center col s12 m12"><b>Analyse(s)</b></h5>
					<h6 class="col s12 m12">
						<br>
						<?php
						while ($donnees=$req_analyse->fetch()) 
						{
							echo "-".$donnees['0']." : ".number_format($donnees['1'],0,'.',' ')."<br>";
							$total1=$total1+$donnees['1'];
						}
						echo "<br>";
						echo "<b>TOTAL : ".number_format($total1,0,'.',' ')." Fcfa"."</b>";
						?>
					</h6>
					<h5 class="center col s12 m12"><b>Soins externe(s)</b></h5>
					<h6 class="col s12 m12">
						<br>
						<?php
						while ($donnees=$req_soins->fetch()) 
						{
							echo "-".$donnees['0']." : ".number_format($donnees['1'],0,'.',' ')."<br>";
							$total2=$total2+$donnees['1'];
						}
						echo "<br>";
						echo "<b>TOTAL : ".number_format($total2,0,'.',' ')." Fcfa"."</b>";
						$total=$total1+$total2;
						?>
					</h6>
				</div>				<br>
				<br>
				N'est pas en règle pour la somme de ....<b><?=$total?> Fcfa... <?php echo $formatter->format($total); ?> Fcfa</b>
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