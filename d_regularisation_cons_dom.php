<?php
session_start();
include 'connexion.php';
if (!isset($_SESSION['fonction'])) {
?>
<script type="text/javascript">
    alert("Veillez d'abord vous connectez !");
    window.location = 'index.php';
</script>
<?php
}

$db->query("SET lc_time_names = 'fr_FR';");
if (isset($_GET['id'])) 
{
	$reponse=$db->prepare("SELECT CONCAT(patient.prenom,' ',patient.nom), consultation_domicile.date_consultation
	FROM `patient`, consultation_domicile 
	WHERE consultation_domicile.id_patient=patient.id_patient AND consultation_domicile.id_consultation=?");
	$reponse->execute(array($_GET['id']));
	$donnees=$reponse->fetch();
	$patient=$donnees['0'];
	$date_consultation=$donnees['1'];

	$req_consultation=$db->prepare("SELECT  soins_domicile.soins, soins_domicile.pu, soins_domicile_patient.quantite, soins_domicile_patient.montant
	FROM soins_domicile_patient, soins_domicile 
	WHERE soins_domicile_patient.id_soins=soins_domicile.id AND soins_domicile_patient.id_consultation=?");
	$req_consultation->execute(array($_GET['id']));
}
elseif(isset($_GET['id_pe']))
{
	$reponse=$db->prepare("SELECT CONCAT(patient_externe.prenom,' ',patient_externe.nom), patient_externe.date_analyse
		FROM  patient_externe
		WHERE patient_externe.id=?");
	$reponse->execute(array($_GET['id_pe']));
	$donnees=$reponse->fetch();
	$patient=$donnees['0'];
	$date_consultation=$donnees['1'];
	//analyses
	$req_analyse=$db->prepare('SELECT analyse.analyse, analyse_patient.montant 
	FROM `analyse_patient`, analyse
	WHERE analyse_patient.id_analyse=analyse.id AND analyse_patient.id_patient=?');
	$req_analyse->execute(array($_GET['id_pe']));
	//soins externes
	$req_soins=$db->prepare('SELECT soins_externes.soins, soins_externes_patient.montant
	FROM  soins_externes,`soins_externes_patient` 
	WHERE soins_externes_patient.id_soins=soins_externes.id AND soins_externes_patient.id_patient=?');
	$req_soins->execute(array($_GET['id_pe']));
}

?>
<!DOCTYPE html>
<html>
	<head>
		<title>Demande de régularisation</title>
		<?php include 'entete.php'; ?>
	</head>
	<body style="background-image: url(<?= $image ?>bgaccueil.jpg);">
		<?php include 'verification_menu_sante.php'; ?>
		<div class="container">
			<div class="row white" style="border-radius: 25px">
				<?php
				if (isset($_GET['id'])) 
				{
					?>
					<form class="col s12" method="POST" action="d_regularisation_cons_dom_trmnt.php?id=<?=$_GET['id']?>">
					<?php
				}
				elseif (isset($_GET['id_pe'])) 
				{
					?>
					<form class="col s12" method="POST" action="d_regularisation_autres_trmnt.php?id_pe=<?=$_GET['id_pe']?>">
					<?php
				}
				?>
					<div class="container">
						<div class="row">
							<h4 class="col s12 center">
							Demande de régularisation pour le patient :<br>
							<b><?=$patient ?></b>
							</h4>
						</div>
						<div class="row">
							<div class="col s12 m6 input-field">
								<input type="text" value="<?= $date_consultation ?>" class="datepicker" name="date_obs" id="date_obs" required>
								<label for="date_obs">Date de la consultation</label>
							</div>
						</div>
						<?php
						if (isset($_GET['id'])) 
						{
							?>
							<div class="row">
								<h5 class="center col s12 m12"><b>Soins dispensé(s)</b></h5>
								<h6 class="col s12 m12">
									<br>
									<?php
									$total=0;
									while ($donnees=$req_consultation->fetch()) 
									{
										echo "-".$donnees['0']." : ".number_format($donnees['3'],0,'.',' ')."<br>";
										$total=$total+$donnees['3'];
									}
									echo "<br>";
									echo "<b>TOTAL : ".number_format($total,0,'.',' ')." Fcfa"."</b>";
									?>
								</h6>
							</div>
							<?php
						}
						elseif (isset($_GET['id_pe'])) 
						{
							$total=0;
							$total1=0;
							$total2=0;
							?>
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
							</div>
							<?php
						}
						?>
						<div class="row">
							<div class="col s12 m6 input-field">
								<input type="text" value="<?=date('Y').'-'.date('m').'-'.date('d') ?>" class="datepicker" name="date_emission" id="date_emission" required>
								<label for="date_emission">Date d'émission</label>
							</div>
							<div class="col s12 m6 input-field">
								<input type="number" value="<?=$total?>"  name="montant" id="montant" required>
								<label for="montant">Montant</label>
							</div>
						</div>
						<div class="row">
							<div class="col s12 m2 offset-m8 input-field">
								<input class="btn" type="submit" name="enregistrer" value="Enregistrer" >
							</div>
						</div>
					</div>
				</form>
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
		}
	</style>
	<script type="text/javascript">
		$(document).ready(function () {
			$('form').submit(function () {
				if (!confirm('Voulez-vous confirmer l\'enregistrement ?')) {
					return false;
				}
			});
			$('select').formSelect();
			$('.datepicker').datepicker({
			autoClose: true,
			yearRange:[2017,2022],
			showClearBtn: true,
			i18n:{
				nextMonth: 'Mois suivant',
				previousMonth: 'Mois précédent',
				labelMonthSelect: 'Selectionner le mois',
				labelYearSelect: 'Selectionner une année',
				months: [ 'Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre' ],
				monthsShort: [ 'Jan', 'Fev', 'Mar', 'Avr', 'Mai', 'Jun', 'Jul', 'Aou', 'Sep', 'Oct', 'Nov', 'Dec' ],
				weekdays: [ 'Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi' ],
				weekdaysShort: [ 'Dim', 'Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam' ],
				weekdaysAbbrev: [ 'D', 'L', 'M', 'M', 'J', 'V', 'S' ],
				today: 'Aujourd\'hui',
				clear: 'Réinitialiser',
				cancel: 'Annuler',
				done: 'OK'
					
				},
				format: 'yyyy-mm-dd'
			});
			$('.timepicker').timepicker({
				showClearBtn:true,
				twelveHour:false,
				i18n:{
					cancel:'Annuler',
					done:'OK',
					clear:'Réinitialiser'
				}
			});
		});
	</script>
</html>