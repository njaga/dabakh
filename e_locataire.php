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
		
	</head>
	<?php include 'entete.php'; ?>
	<body style="background-image: url(<?= $image ?>bgaccueil.jpg);">
		<?php
		include 'verification_menu_immo.php';
		?>
		<div class="container white">
			<div class="row z-depth-5" style="padding: 10px;">
				<h3 class="center">Ajout d'un nouveau locataire</h3>
				<form class="col s12" method="POST" id="form" action="e_locataire_trmnt.php" >
					<div class="row">
						<div class="col s6 input-field">
							<input type="text" name="prenom" id="prenom" required>
							<label for="prenom">Prénom</label>
						</div>
						<div class="col s5 input-field">
							<input type="text" name="nom" id="nom" required>
							<label for="nom">Nom</label>
						</div>
					</div>
					<div class="row">
						<div class="col s4 input-field">
							<input type="text" name="telephone" id="telephone" required>
							<label for="telephone">Téléphone</label>
						</div>
					</div>
					<div class="row">
						<div class="col s6 input-field">
							<input  type="number" name="cni" id="cni" required>
							<label for="cni">CNI</label>
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
				if (!confirm('Voulez-vous confirmer l\'enregistrement de ce nouveau locataire ?')) {
					return false;
				}
			});
		});
		
	</script>
</html>