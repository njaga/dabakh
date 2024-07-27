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
		<title>Ajout</title>
		<?php include 'entete.php'; ?>
	</head>
	<body style="background-image: url(<?= $image ?>bgaccueil.jpg);">
		<?php
		include 'verification_menu_sante.php';
		?>
		<div class="container white">
			<div class="row z-depth-5" style="padding: 10px;">
				<h3 class="center">Ajout d'un nouveau personnel</h3>
				<form class="col s12" method="POST" id="form" action="e_personnel_trmnt.php" enctype="multipart/form-data">
					<div class="row">
						<div class="col s6 m6 l4 input-field">
							<input type="text" name="matricule" id="matricule" required>
							<label for="matricule">Matricule</label>
						</div>
					</div>
					<div class="row">
						<div class="col s6 m6 l4 input-field">
							<input type="text" name="prenom" id="prenom" required>
							<label for="prenom">Prénom</label>
						</div>
						<div class="col s5 m6 l4 input-field">
							<input type="text" name="nom" id="nom" required>
							<label for="nom">Nom</label>
						</div>
						<div class="col s6 m6 l2 input-field">
							<select name="sexe" class="browser-default" required>
								<option value="" disabled="" selected="" >-- SEXE --</option>
								<option value="Feminin">Feminin</option>
								<option value="Masculin">Masculin</option>
								DAF
							</option>
							
						</select>
					</div>
				</div>
				<div class="row">
				</div>
				<div class="row">
					<div class="col s4 m6 l4 input-field">
						<input type="text" name="telephone" id="telephone" required>
						<label for="telephone">Téléphone</label>
					</div>
					<div class="col s7 m6 l5 input-field">
						<select name="fonction" class="browser-default" required>
							<option value="" disabled="" selected="" >Veillez choisir la fonction</option>
							<option value="administrateur">
								Administrateur
							</option>
							<option value="comptable">
								Comptable
							</option>
							<option value="daf">
								DAF
							</option>
							<option value="secretaire">
								Secrétaire
							</option>
							<option value="medecin">
								Medecin
							</option>
							<option value="infirmier">
								Infirmier(e)
							</option>
							<option value="agent immobilier">
								Agent immobilier(e)
							</option>
							<option value="agent de sante">
								Agent de santé
							</option>
							<option value="agent commercial">
								Agent commercial
							</option>
							<option value="agent responsable des opérations de caisse">
								Agent responsable des opérations de caisse
							</option>
							<option value="caissier">
								caissier(e)
							</option>
							<option value="femme de charge">
								Femme de charge
							</option>
							<option value="stagiaire">
								Stagiare
							</option>
							<option value="chauffeur">
								Chauffeur
							</option>
						</select>
					</div>
				</div>
				<div class="row">
					<div class="col s5 input-field">
						<input type="date" name="date_embauche" class="" id="date_embauche" required>
						<label for="date_embauche">Date d'embauche</label>
					</div>
					<div class="col s6 input-field">
						<select name="service" class="browser-default" required>
							<option value="" disabled="" selected="" >Veillez choisir le service</option>
							<option value="commerce">Commerce</option>
							<option value="sante">Santé</option>
							<option value="immmobilier">Immobilier</option>
							<option value="service general">Service Général</option>
						</select>
					</div>
				</div>
				<div class="row" id="doc">
					<h4 class="center col s12">Pièces Jointes</h4>
					<div class="file-field input-field col s10">
						<div class="btn blue darken-4">
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
	});
	
</script>
</html>