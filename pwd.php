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
	<title>Modification mot de passe</title>
	<?php include 'entete.php'; ?>
</head>

<body style="background-image: url(../css/images/carreaux.jpg);">
	<?php
	include 'verification_menu_immo.php';
	?>
	<div class="container white">
		<div class="row z-depth-5" style="padding: 10px;">
			<h3 class="center">Modification du mot de passe</h3>
			<form class="col s12" method="POST" id="form" action="pwd_trmnt.php?id=<?= $_SESSION['id'] ?>">
				<div class="row">
					<div class="col s6 input-field">
						<input type="password" name="new_password" id="new_password" required="">
						<label for="new_password">Mot de passe</label>
					</div>
				</div>
				<div class="row">
					<div class="col s6 input-field">
						<input type="password" name="confirm_password" id="confirm_password" required="">
						<label for="password">Confirmer mot de passe</label>
					</div>
				</div>
				<div class="row">
					<div class="col s2 offset-s8 input-field">
						<input class="btn" type="submit" name="enregistrer" value="Sauvegarder">
					</div>
				</div>
			</form>
		</div>
	</div>
</body>
<script type="text/javascript">
	$(document).ready(function() {
		$('form').submit(function() {
			var pwd1 = $('input[name=new_password]').val();
			var pwd2 = $('input[name=confirm_password]').val();
			if (pwd1 == pwd2) {
				if (!confirm('Voulez-vous confirmer l\'enregistrement du nouveau mot de passe ?')) {
					return false;
				}
			} else {
				alert('Erreur : Les mots de passe ne correspondent pas');
				return false;
			}
		})
	});
</script>

</html>