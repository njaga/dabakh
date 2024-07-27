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
		<title>Nouveau compte rendu</title>
		<?php include 'entete.php'; ?>
	</head>
	<body style="background-image: url(<?= $image ?>bgaccueil.jpg);">
		<?php
			if ($_SESSION['service']=="immobilier") 
	        {
	            include 'verification_menu_immo.php';              
	        }
	        else
	        {
	            include 'verification_menu_sante.php';              
	        }
		?>
		<div class="container white">
			<div class="row z-depth-5" style="padding: 10px;">
				<h3 class="center">Nouveau compte rendu</h3>
				<form class="col s12" method="POST" id="form" action="e_compte_rendu_trmnt.php" >
					<div class="row">
						<div class="col s6 m3 input-field">
							<input type="text" value="<?= date("Y")."-".date("m")."-".date("d") ?>" class="datepicker" name="date_cmpt" id="date_cmpt" required>
							<label for="date_cmpt">Date compte rendu</label>
						</div>
						
					</div>
					<div class="row">
						<div class="input-field col s12 m10">
							<textarea id="compte_rendu" name="compte_rendu" class="materialize-textarea"></textarea>
							<label for="compte_rendu">Compte rendu</label>
						</div>
					</div>
					<div class="row">
						<div class="col s2 offset-s6 m2 offset-m8 input-field">
							<input class="btn" type="submit" name="enregistrer" value="Enregistrer" >
						</div>
					</div>
				</form>
			</div>
		</div>
	</body>
	<script type="text/javascript">
		$(document).ready(function () {
			$('select').formSelect();
			$('#form').submit(function () {
				if (!confirm('Voulez-vous confirmer l\'enregistrement ?')) {
					return false;
				}
			});
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
		});
		
	</script>
</html>