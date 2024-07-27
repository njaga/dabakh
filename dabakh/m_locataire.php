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
$req = $db->prepare('SELECT `id`, `prenom`, `nom`, `tel`, `cni`, `num_dossier`, `annee_inscription`, `statut`,`email` FROM `locataire` WHERE id= ?');
$req->execute(array($_GET['id']));
$nbr = $req->rowCount();
if ($nbr > 0) {
	$donnees = $req->fetch();
	$id = $donnees['0'];
	$prenom = $donnees['1'];
	$nom = $donnees['2'];
	$tel = $donnees['3'];
	$cni = $donnees['4'];
	$num_dossier = $donnees['5'];
	$annee_inscription = $donnees['6'];
	$etat = $donnees['7'];
	$email = $donnees['8'];
}
?>
<!DOCTYPE html>
<html>

<head>
	<title>Modification d'un locataire</title>
	<?php include 'entete.php'; ?>
</head>

<body style=" background-image: url(<?= $image ?>m_locataire.jpg) ;">
	<?php
	include 'verification_menu_immo.php';
	?>
	<div class="container white">
		<div class="row z-depth-5" style="padding: 10px;">
			<h3 class="center">Modification d'un locataire</h3>
			<form class="col s12" method="POST" id="form" action="m_locataire_trmnt.php?id=<?= $id ?>" enctype="multipart/form-data">
				<div class="row">
					<div class="col s2 input-field">
						<input type="number" value="<?= $num_dossier ?>" name="num_dossier" id="num_dossier" required>
						<label for="num_dossier">Numéro dossier</label>
					</div>
					<div class="col s2 input-field">
						<select class="browser-default" name="annee_inscription" required>
							<option disabled="">Année d'inscription</option>
							<?php

							for ($i = 12; $i > 0; $i--) {
								echo "<option value='" . (date('Y') - $i) . "'";
								if ($annee_inscription == (date('Y') - $i)) {
									echo "selected";
								}
								echo ">" . (date('Y') - $i) . "</option>";
							}
							echo "<option value='" . (date('Y')) . "'";
							if ($annee_inscription == (date('Y'))) {
								echo "selected";
							}
							echo ">" . (date('Y')) . "</option>";
							echo "<option value='" . (date('Y') + 1) . "'";
							if ($annee_inscription == (date('Y') + 1)) {
								echo "selected";
							}
							echo ">" . (date('Y') + 1) . "</option>";
							?>


						</select>
					</div>
				</div>
				<div class="row">
					<div class="col s6 input-field">
						<input type="text" name="prenom" value="<?= $prenom ?>" id="prenom" required>
						<label for="prenom">Prénom</label>
					</div>
					<div class="col s5 input-field">
						<input type="text" name="nom" value="<?= $nom ?>" id="nom" required>
						<label for="nom">Nom</label>
					</div>
				</div>
				<div class="row">
					<div class="col s4 input-field">
						<input type="text" value="<?= $tel ?>" name="telephone" id="telephone" required>
						<label for="telephone">Téléphone</label>
					</div>
					<div class="col s4 input-field">
						<input type="text" value="<?= $cni ?>" name="cni" id="cni" required>
						<label for="cni">N° CNI</label>
					</div>
					<div class="col s4 input-field">
						<input type="text" value="<?= $email ?>" name="email" id="email" required>
						<label for="email">Email</label>
					</div>
				</div>
				<!--Pièces Jointes -->
				<div class="row" id="doc">
					<h4 class="center col s12">Pièces Jointes</h4>
					<h5 class="center">
						<?php
						$req_pj = $db->prepare('SELECT * FROM pj_locataire WHERE id_locataire=?');
						$req_pj->execute(array($_GET['id']));
						$nbr = $req_pj->rowCount();
						if ($nbr > 0) {
							while ($donnees_pj = $req_pj->fetch()) {
								echo "<div class='row'>";
								echo "<a class='col s5' href='" . $donnees_pj['2'] . "'>" . $donnees_pj['1'] . "</a>";
								echo "&nbsp&nbsp&nbsp";
								if ($_SESSION['fonction'] == 'administrateur') {
									echo "<a class='col s2 red-text' href='s_pj_locataire.php?s=" . $donnees_pj['0'] . "' onclick='return(confirm(\"Voulez-vous supprimer cette pièce jointe ?\"))'>Supprimer</a>";
								}
								echo "<br>";
								echo "</div>";
							}
						} else {
							echo "Aucun fichier";
						}
						?>
						<br>
					</h5>
					<div class="file-field input-field col s10">
						<div class="btn blue darken-4">
							<span>Sélectionner</span>
							<input type="file" accept="" name="fichier[]" class=" fichier" multiple>
						</div>
						<div class="file-path-wrapper">
							<input class="file-path validate fichier" placeholder="Sélectionner le(s) document(s)" type="text">
						</div>
					</div>
				</div>
				<div class="row hide">
					<div class="col s2 input-field">
						<select class="" name="statut" required>
							<option value="" disabled>Etat</option>
							<option value="actif" <?php if ($etat == 'actif') {
														echo "selected";
													} ?>>Actif</option>
							<option value="inactif" <?php if ($etat == 'inactif') {
														echo "selected";
													} ?>>Inactif</option>
						</select>
						<label>Etat</label>
					</div>
				</div>
				<div class="row">
					<div class="col s2 offset-s8 input-field">
						<input class="btn" type="submit" name="enregistrer" value="Enregistrer modification(s)">
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
			if (!confirm('Voulez-vous confirmer la modification de ce locataire ?')) {
				return false;
			}
		});
	});
</script>

</html>