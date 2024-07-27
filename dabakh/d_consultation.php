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
include 'connexion.php';
$db->query("SET lc_time_names = 'fr_FR';");
$reponse=$db->prepare("SELECT CONCAT(patient.prenom,' ',patient.nom), CONCAT(CONCAT(day(patient.date_naissance),' ', monthname(patient.date_naissance),' ', year(patient.date_naissance)), ' ',patient.lieu_naissance)
FROM `patient` WHERE id_patient=?");
$reponse->execute(array($_GET['id']));
$donnees=$reponse->fetch();
$patient=$donnees['0'];
$date_naissance=$donnees['1'];
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
				<form class="col s12" method="POST" action="d_consultation_trmnt.php?id_patient=<?=$_GET['id']?>">
					<div class="container">
						<div class="row">
							<h4 class="col s12">Demande de Consultation(s) / Imageire(s) spécialisée pour le patient :<br>
							<b><?=$patient ?></b>
							</h4>
						</div>
						<div class="row">
						<select class="browser-default col s12  m6  l6" name="type_examen">
							<option value="" disabled selected>--Type de l'examen--</option>
							<option value="d'Echographie">Echographie</option>
							<option value="de Consultation spécialisée">Consultation spécialisée</option>
							<option value="de Radiographie">Radigrahie</option>
							<option value="de Scanner">Scanner</option>
							<option value="d'IRM">IRM</option>
							<option value="de TDM">TDM</option>
						</select>
						</div>
						<div class="row">
							<div class="col s10 input-field">
								<textarea class='materialize-textarea'  name="renseignements" id="renseignements" required> </textarea>
								<label for="renseignements">Renseignements Clinique</label>
							</div>
						</div>
						<div class="row">
							<div class="col s10 input-field">
								<textarea class='materialize-textarea'  name="examen" id="examen" required> </textarea>
								<label for="examen">Examen(s) demandé(s)</label>
							</div>
						</div>
						<div class="row">
							<div class="col s2 offset-s8 input-field">
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