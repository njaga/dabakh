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
		<title>Enregistrer courrier</title>
		<?php include 'entete.php'; ?>
	</head>
	<body style="background-image: url(<?= $image ?>bgaccueil.jpg);">
        <?php
            if ($_SESSION['service']=="immobilier") 
            {
                include 'verification_menu_immo.php';
            }
            elseif ($_SESSION['service']=="sante")
            {
                include 'verification_menu_sante.php';
            }
            else
            {
                include 'verification_menu_cm.php';
            }

        ?>
		<br>
		<div class="container white">
			<div class="row z-depth-5" style="padding: 10px;">
				<h3 class="center">Enregistrement courrier</h3>
				<form class="col s12" method="POST" id="form" action="e_courrier_trmnt.php" enctype="multipart/form-data">
					<div class="row">
                        <div class="col s6 m6 l3 input-field">
                            <select name="type_courrier" class="browser-default" required>
                                <option value="" disabled="" selected="" >-- Type courrier --</option>
                                <option value="Depart">Départ</option>
                                <option value="Arriver">Arriver</option>
                            </option>
                            
                        </select>
                        </div>
						<div class="col s6 m6 l3 input-field">
							<input type="text" name="numero" id="numero" required>
							<label for="numero">N° Courrier</label>
						</div>
					</div>
					<div class="row">
						<div class="col s6 m6 l4 input-field">
							<input type="text" name="intitule" id="intitule" required>
							<label for="intitule">Intitule</label>
						</div>
                        
						<div class="col s5 m6 l5 input-field">
                            <textarea id="description" name="description" class="materialize-textarea"></textarea>
							<label for="description">Description</label>
						</div>
				</div>
				<div class="row">
				</div>
				<div class="row">
					<div class="col s4 m6 l4 input-field">
						<input type="text" name="expediteur" id="expediteur" required>
						<label for="expediteur">Expéditeur</label>
					</div>
                    <div class="col s4 m6 l4 input-field">
						<input type="text" name="destinataire" id="destinataire" required>
						<label for="destinataire">Destinataire</label>
					</div>
				</div>
				<div class="row">
					<div class="col s5 input-field">
						<input type="text" name="date_courrier" value="<?= date("Y")."-".date("m")."-".date("d") ?>" class="datepicker" id="datecourriere" required>
						<label for="date_courrier">Date courrier</label>
					</div>
				</div>
				<div class="row" id="doc">
					<h4 class="center col s12">Pièces Jointes</h4>
					<div class="file-field input-field col s10">
						<div class="btn black darken-4">
							<span >Sélectionner</span>
							<input type="file" accept="" name="fichier[]" class=" fichier" multiple>
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
		$('select').formSelect();
		$('#form').submit(function () {
			if (!confirm('Voulez-vous confirmer l\'enregistrement ?')) {
				return false;
			}
		});
        $('.datepicker').datepicker({
			autoClose: true,
			yearRange:[2017,2023],
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