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
$req=$db->prepare('SELECT COUNT(*) FROM patient WHERE annee_inscription=?');
$req->execute(array(date('Y')));
$donnee=$req->fetch();
$num_dossier= $donnee['0'] + 1;
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Nouveau dossier</title>
		<?php include 'entete.php'; ?>
	</head>
	<body style="background-image: url(<?= $image ?>bgaccueil.jpg);">
		<?php
		include 'verification_menu_sante.php';
		?>
		<div class="container white">
			<div class="row z-depth-5" style="padding: 10px;">
				<h3 class="center">Nouveau Dossier Patient</h3>
				<form class="col s12" method="POST" id="form" action="e_patient_trmnt.php" >
					<div class="row">
						<div class="col s5 input-field">
							<i class="material-icons prefix">folder_shared</i>
							<input type="number" value="<?=$num_dossier?>" name="num_dossier" id="num_dossier" required>
							<label for="num_dossier">Numéro dossier</label>
						</div>
						<div class="col s5 input-field">
							<select class="browser-default" name="annee_inscription" required>
								<option value="" disabled selected>Année d'inscription</option>
								<?php

								for ($i=12; $i >0 ; $i--) { 
									echo "<option value='".(date('Y')-$i)."'>".(date('Y')-$i)."</option>";
								}
								echo "<option value='".(date('Y'))."'>".(date('Y'))."</option>";
								echo "<option value='".(date('Y')+1)."'>".(date('Y')+1)."</option>";
								?>
							</select>
						</div>
					</div>
					<div class="row">
						<div class="col s5 input-field">
							<i class="material-icons prefix">account_circle</i>
							<input type="text" name="prenom" id="prenom" required>
							<label for="prenom">Prénom</label>
						</div>
						<div class="col s5 input-field">
							<i class="material-icons prefix">account_box</i>
							<input type="text" name="nom" id="nom" required>
							<label for="nom">Nom</label>
						</div>
					</div>
					<div class="row">
						<div class="col s4 input-field">
							<input type="date" name="date_naissance" id="date_naissance"  required>
							<label for="date_naissance">Date de naissance</label>
						</div>
						<div class="col s5 input-field">
							<i class="material-icons prefix">add_location</i>
							<input type="text" class="" name="lieu_naissance" id="lieu_naissance" required>
							<label for="lieu_naissance">Lieu de naissance</label>
						</div>
					</div>
					<div class="row">
						<div class="col s4 input-field">
							<i class="material-icons prefix">location_on</i>
							<input type="text" class="" name="domicile" id="domicile">
							<label for="domicile">Domicile</label>
						</div>
						<div class="col s3 input-field">
							<i class="material-icons prefix">call</i>
							<input type="text" class="" name="telephone" id="telephone" required>
							<label for="telephone">Téléphone</label>
						</div>
						<div class="col s5 input-field">
							<input type="text" class="" name="profession" id="profession">
							<label for="profession">Profession</label>
						</div>
					</div>
					<div class="row">
						<div class="col s4 input-field">
							<select class="browser-default" name="sexe" required>
								<option value="" disabled selected>Sexe</option>
								<option value="Masculin">Masculin</option>
								<option value="Feminin">Feminin</option>
								
							</select>
						</div>
						<div class="col s5 input-field">
							<select class="browser-default" name="situation_matrimoniale" required>
								<option value="" disabled selected>Situation matrimonaile</option>
								<option value="Marie(e)">Marie(e)</option>
								<option value="Celibataire">Celibataire</option>
								
							</select>
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
				if (!confirm('Voulez-vous confirmer l\'enregistrement de nouveau  patient ?')) {
					return false;
				}
			});
		});
	</script>
</html>