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
$req = $db->prepare('SELECT COUNT(*) FROM bailleur WHERE annee_inscription=?');
$req->execute(array(date('Y')));
$donnee = $req->fetch();
$num_dossier = $donnee['0'] + 1;
?>
<!DOCTYPE html>
<html>

<head>
	<title>Ajout d'un nouveau bailleur</title>
	<?php include 'entete.php'; ?>
</head>

<body style="background-image: url(<?= $image ?>bgaccueil.jpg) ;">
	<?php
	include 'verification_menu_immo.php';
	?>
	<br>
	<div class="container white">
		<div class="row z-depth-5" style="padding: 10px;">
			<h3 class="center">Ajout d'un nouveau bailleur</h3>
			<form class="col s12" method="POST" id="form" action="e_bailleur_trmnt.php" enctype="multipart/form-data">
				<div class="row">
					<div class="col s5 m2 input-field">
						<input type="number" value="<?= str_pad($num_dossier, 3, "0", STR_PAD_LEFT) ?>" name="num_dossier" id="num_dossier" required>
						<label for="num_dossier">Numéro dossier</label>
					</div>
					<div class="col s7 m3 input-field">
						<select class="browser-default" name="annee_inscription" required>
							<option disabled="" selected="">Année d'inscription</option>
							<?php

							for ($i = 12; $i > 0; $i--) {
								echo "<option value='" . (date('Y') - $i) . "'>" . (date('Y') - $i) . "</option>";
							}
							echo "<option value='" . (date('Y')) . "'>" . (date('Y')) . "</option>";
							echo "<option value='" . (date('Y') + 1) . "'>" . (date('Y') + 1) . "</option>";
							?>

						</select>
					</div>
					<div class="col s12 m3 input-field">
						<input type="text" class="datepicker" name="date_debut" id="date_debut" required>
						<label for="date_debut">Date début</label>
					</div>
					<div class="col s12 m2 input-field">
						<input type="number" value="01" name="duree_contrat" id="duree_contrat" required>
						<label for="duree_contrat">Durée (année)</label>
					</div>
				</div>
				<div class="row">
					<div class="col s12 m6 input-field">
						<input type="text" name="prenom" id="prenom" required>
						<label for="prenom">Prénom</label>
					</div>
					<div class="col s12 m5 input-field">
						<input type="text" name="nom" id="nom" required>
						<label for="nom">Nom</label>
					</div>
				</div>
				<div class="row">
					<div class="col s12 m4 input-field">
						<input type="text" name="telephone" id="telephone" required>
						<label for="telephone">Téléphone</label>
					</div>
					<div class="col s12 m7 input-field">
						<input type="text" name="adresse" id="adresse" required>
						<label for="adresse">Adresse</label>
					</div>
				</div>
				<div class="row">
					<div class="col s12 m8 input-field">
						<input type="text" name="cni" id="cni" required>
						<label for="cni">Numéro et date CNI</label>
					</div>
				</div>
				<div class="row">
					<div class="col s6 m3 input-field">
						<input type="number" name="pourcentage" id="pourcentage" required>
						<label for="pourcentage">Pourcentage</label>
					</div>
				</div>
				<!--Pièces Jointes -->
				<div class="row" id="doc">
					<h4 class="center col s12">Pièces Jointes</h4>
					<div class="file-field input-field col s10">
						<div class="btn black darken-4">
							<span>Sélectionner</span>
							<input type="file" accept="application/pdf" name="fichier[]" class=" fichier" multiple>
						</div>
						<div class="file-path-wrapper">
							<input class="file-path validate fichier" placeholder="Sélectionner le(s) document(s)" type="text">
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col s2 offset-s6 m2 offset-m8 input-field">
						<input class="btn" type="submit" name="enregistrer" value="suivant->">
					</div>
				</div>
			</form>
		</div>
	</div>
</body>
<script type="text/javascript">
	$(document).ready(function() {
		$('select').formSelect();
		$('#form').submit(function() {
			if (!confirm('Voulez-vous confirmer l\'enregistrement de ce nouveau bailleur ?')) {
				return false;
			}
		});
		$('.datepicker').datepicker({
			autoClose: true,
			yearRange: [2017, 2022],
			showClearBtn: true,
			i18n: {
				nextMonth: 'Mois suivant',
				previousMonth: 'Mois précédent',
				labelMonthSelect: 'Selectionner le mois',
				labelYearSelect: 'Selectionner une année',
				months: ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'],
				monthsShort: ['Jan', 'Fev', 'Mar', 'Avr', 'Mai', 'Jun', 'Jul', 'Aou', 'Sep', 'Oct', 'Nov', 'Dec'],
				weekdays: ['Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'],
				weekdaysShort: ['Dim', 'Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam'],
				weekdaysAbbrev: ['D', 'L', 'M', 'M', 'J', 'V', 'S'],
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