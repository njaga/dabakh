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
		<title>Ajout d'un nouveau chnatier</title>
		<?php include 'entete.php'; ?>
	</head>
	<body style="background-image: url(<?= $image ?>bgaccueil.jpg);">
		<?php
		include 'verification_menu_immo.php';
		?>
		<div class="container white">
			<div class="row z-depth-5" style="padding: 10px;">
				<h3 class="center">Ajout d'un nouveau chantier</h3>
				<form class="col s12" method="POST" id="form" action="e_chantier_trmnt.php" >
					<div class="row">
						<div class="col s12 m3 input-field">
							<input type="text" class="datepicker" name="date_debut" id="date_debut" required>
							<label for="date_debut">Date début</label>
						</div>
                        <div class="col s12 m3 input-field">
							<input type="text" class="datepicker" name="date_prevu_fin" id="date_prevu_fin" >
							<label for="date_prevu_fin">Date prévu fin</label>
						</div>
					</div>
					<div class="row">
						<div class="col s12 m6 input-field">
							<input type="text" name="proprietaire" id="proprietaire" required>
							<label for="proprietaire">Propriétaire</label>
						</div>
						<div class="col s12 m4 input-field">
							<input type="text" name="contact" id="contact" required>
							<label for="contact">Contact</label>
						</div>
					</div>
					<div class="row">
                        <div class="col s6 m3 input-field">
                            <input type="number" name="cout" id="cout" >
                            <label for="cout">Coût</label>
                        </div>
						<div class="col s12 m7 input-field">
							<input type="text" name="emplacement" id="emplacement" required>
							<label for="emplacement">Emplacement</label>
						</div>
					</div>
					<div class="row">
                        <div class="col s8 input-field">
                            <textarea class="materialize-textarea" name="travail_demande" id="travail_demande"></textarea>
                            <label for="travail_demande">Travail demandé</label>
                        </div>
					</div>
					<div class="row">
						
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
				if (!confirm('Voulez-vous confirmer l\'enregistrement de ce chantier ?')) {
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