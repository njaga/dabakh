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
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Enregistrement demande d'emploi</title>
		<?php include 'entete.php'; ?>
	</head>
	<body style="background-image: url(<?= $image ?>bgaccueil.jpg);">
		<?php
		include 'verification_menu_sante.php';
		?>
		<div class="container white">
			<div class="row z-depth-5" style="padding: 10px;">
				<h3 class="center">Enregistrement d'une nouvelle demande d'emploi</h3>
				<form class="col s12" method="POST" id="form" action="e_demande_emploi_trmnt.php" enctype="multipart/form-data">
					<div class="row">
						<div class="col s6 m4 l4 input-field">
							<input type="text" class="datepicker" name="date_enregistrement" id="date_enregistrement" >
							<label for="date_enregistrement">Date d'enregistrement</label>
						</div>
					</div>
					<div class="row ">
						<div class="col s5 m4 l4 input-field">
							<input type="text" class="" name="prenom" class="" id="prenom" >
							<label for="prenom">Prénom(s)</label>
						</div>
						<div class="col s5 m4 l4 input-field">
							<input type="text" class="" name="nom" class="" id="nom" >
							<label for="nom">Nom</label>
						</div>
					</div>
					<div class="row">
						<div class="col s5 m4 l4 input-field">
							<input type="text" class="" name="poste" class="" id="poste" >
							<label for="poste">Poste</label>
						</div>
					</div>
					
					
					<div class="row" id="doc">
						<h5 class="center"><b>Pièces jointes</b></h5>
						<div class="row">
							<div class="file-field input-field col s12 m5 l5">
								<div class="btn blue darken-4">
									<span >CV</span>
									<input type="file" accept="" name="cv[]" class=" cv" multiple>
								</div>
								<div class="file-path-wrapper">
									<input class="file-path validate cv" placeholder="Sélectionner le(s) document(s)"  type="text" >
								</div>
							</div>
							<div class="file-field input-field col s12 m6 l5">
								<div class="btn blue darken-4">
									<span >Diplômes</span>
									<input type="file" accept="application/pdf" name="diplomes[]" class=" diplomes" multiple>
								</div>
								<div class="file-path-wrapper">
									<input class="file-path validate diplomes" placeholder="Sélectionner le(s) document(s)"  type="text" >
								</div>
							</div>
						</div>
						<div class="row">
							<div class="file-field input-field col s12 m10 l10">
								<div class="btn blue darken-4">
									<span >Autres documents</span>
									<input type="file" accept="application/pdf" name="autres_docs[]" class=" autres_docs" multiple>
								</div>
								<div class="file-path-wrapper">
									<input class="file-path validate autres_docs" placeholder="Sélectionner le(s) document(s)"  type="text" >
								</div>
							</div>
						</div>
						
					</div>
					<div class="row">
						<div class="col s2 offset-s8 input-field">
							<input class="btn" type="submit" name="enregistrer" value="Enregistrer" >
						</div>
					</div>
				</form>
			</div>
		</div>
	</body>
	<script type="text/javascript">
		$(document).ready(function () {
			
			$('.date_demande').addClass('hide');
			$('.heure_demande').addClass('hide');
			$('.date_emission_reception').addClass('hide');
			function demande()
			{
				var s=$('.type_demande').val();
				if (s=="Demande de permission")
				{
					$('.date_demande').removeClass('hide');
					$('.heure_demande').addClass('hide');
					$('.date_emission_reception').addClass('hide');
				}
				else if (s=="Demande de conge")
				{
					$('.date_demande').removeClass('hide');
					$('.heure_demande').addClass('hide');
					$('.date_emission_reception').addClass('hide');
				}
				else if (s=="Demande d'explication")
				{
					$('.date_emission_reception').removeClass('hide');
					$('.date_demande').addClass('hide');
					$('.heure_demande').addClass('hide');
				}
				else if (s=="Autorisation d'absence")
				{
					$('.heure_demande').removeClass('hide');
					$('.date_demande').addClass('hide');
					$('.date_emission_reception').addClass('hide');
				}
			}
			demande();
			$('.type_demande').change(function(){
				demande();
					});
			$('.type_demande').formSelect();
			$('#form').submit(function () {
				if (!confirm('Voulez-vous confirmer l\'enregistrement ?')) {
					return false;
				}
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
			$('.datepicker').datepicker({
				autoClose: true,
	yearRange:[2016,<?=(date('Y')+1) ?>],
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
	});
	
	</script>
</html>