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

$reponse=$db->prepare("SELECT * FROM patient WHERE id_patient=?");
$reponse->execute(array($_GET['id_patient']));
$donnees=$reponse->fetch();
$prenom=$donnees['1'];
$nom=$donnees['2'];
$date_naissance=$donnees['3'];
$lieu_naissance=$donnees['4'];
$profession=$donnees['5'];
$domicile=$donnees['6'];
$telephone=$donnees['7'];
$situation_mat=$donnees['8'];
$antecedant=$donnees['9'];
$allergie=$donnees['10'];
$taille=$donnees['11'];
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Enregistrement d'un rendez-vous</title>
		<?php include 'entete.php'; ?>
	</head>
	<body style="background-image: url(<?= $image ?>bgaccueil.jpg);">
		<?php include 'verification_menu_sante.php'; ?>
		<div class="container white">
			<div class="row z-depth-5" style="padding: 10px;">
				<h4 class="center">
				Enregistrement d'un rendez-vous pour<b> <?=$prenom." ".$nom ?></b>
				</h4>
				<form class="col s12" method="POST" id="form" action="e_rdv_trmnt.php?id_patient=<?=$_GET['id_patient']?>" >
					<br>
					<div class="row">
						<div class="col s3 input-field">
							<input type="date" class="" name="date_rdv" id="date_rdv" required>
							<label for="date_rdv">Date du prochain rendez-vous</label>
						</div>
					</div>
					<div class="row">
						<div class="col s2 input-field">
							<input type="time" class="" name="heure_rdv" id="heure_rdv" required>
							<label for="heure_rdv">Heure</label>
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
			$('#form').submit(function () {
				if (!confirm('Voulez-vous confirmer l\'enregistrement de ce nouveau rendez-vous ?')) {
					return false;
				}
			});
			$('select').formSelect();
			$('.datepicker').datepicker({
			autoClose: true,
			yearRange:[2017,<?=(date('Y')+1) ?>],
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