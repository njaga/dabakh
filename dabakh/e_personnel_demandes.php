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
$req=$db->prepare('SELECT prenom, nom FROM personnel WHERE id=?');
$req->execute(array($_GET['s']));
$donnees=$req->fetch();
$prenom=$donnees['0'];
$nom=$donnees['1'];
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Enregistrement nouvelle demande</title>
		<?php include 'entete.php'; ?>
	</head>
	<body style="background-image: url(<?= $image ?>bgaccueil.jpg);">
		<?php
		include 'verification_menu_sante.php';
		?>
		<div class="container white">
			<div class="row z-depth-5" style="padding: 10px;">
				<h3 class="center">Nouvelle demande pour : <br><b><?=$prenom ?> <?=$nom ?></b></h3>
				<form class="col s12" method="POST" id="form" action="e_personnel_demandes_trmnt.php" enctype="multipart/form-data">
					<input type="text" name="id_personnel" value="<?=$_GET['s'] ?>" hidden>
					<div class="row">
						<div class="col s6 m4 l4 input-field">
							<input type="text" class="datepicker" name="date_demande" id="date_demande" >
							<label for="date_demande">Date demande</label>
						</div>
					</div>
					<div class="row">
						<div class="col s7 m4 l4 input-field">
							<select name="type_demande" class="browser-default type_demande" required>
								<option value="" disabled="" selected="" >Objet de la demande </option>
								<option value="Autorisation d'absence">
									Autorisation d'absence
								</option>
								<option value="Demande de conge">
									Demande de conge
								</option>
								<option value="Demande de permission">
									Demande de permission
								</option>
								<?php
								if ($_SESSION['fonction']=="administrateur" OR $_SESSION['fonction']=="daf") 
								{
									?>
									<option value="Demande d'explication">
										Demande d'explication
									</option>
									<?php
								}
								?>
							</select>
						</div>
					</div>
					<div class="row date_demande">
						<div class="col s5 m4 l4 input-field">
							<input type="text" class="datepicker" name="date_debut"  id="date_debut" >
							<label for="date_debut">Date début</label>
						</div>
						<div class="col s5 m4 l4 input-field">
							<input type="text" class="datepicker" name="date_fin"  id="date_fin" >
							<label for="date_fin">Date fin</label>
						</div>
					</div>
					<div class="row date_emission_reception">
						<div class="col s5 m4 l4 input-field">
							<input type="text" class="datepicker" name="date_emission"  id="date_emission" >
							<label for="date_emission">Date émission</label>
						</div>
						<div class="col s5 m4 l4 input-field">
							<input type="text" class="datepicker" name="date_reception"  id="date_reception" >
							<label for="date_reception">Date réception</label>
						</div>
					</div>
					<div class="row heure_demande">
						<div class="col s5 m2 l2 input-field">
							<input type="text" class="timepicker" name="heure_debut"  id="heure_debut" >
							<label for="heure_debut">Heure début</label>
						</div>
						<div class="col s5 m2 l2 input-field">
							<input type="text" class="timepicker" name="heure_fin"  id="heure_fin" >
							<label for="heure_fin">Heure fin</label>
						</div>
					</div>
					<div class="row" id="doc">
						<div class="file-field input-field col s12">
							<div class="btn blue darken-4">
								<span >Sélectionner</span>
								<input type="file" accept="application/pdf" name="fichier[]" class=" fichier" multiple>
							</div>
							<div class="file-path-wrapper">
								<input class="file-path validate fichier" placeholder="Sélectionner le(s) document(s)"  type="text" >
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
	yearRange:[2018,<?=(date('Y')+1) ?>],
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