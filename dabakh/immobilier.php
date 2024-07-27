<?php
session_start();
$_SESSION['service'] = "immobilier";
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
	<title>Accueil</title>
	<?php include 'entete.php'; ?>
</head>

<body>
	<?php include 'verification_menu_immo.php'; ?>
	<style type="text/css">
		body {
			background-image: url(<?= $image ?>bgaccueil.jpg);">
			background-position: center center;
			background-repeat: no-repeat;
			background-attachment: fixed;
			background-size: cover;
			background-color: #999;
		}
	</style>

	<div id="immobilier" class="modal">
		<div class="modal-content">
			<h4 class="center" style="border: 2px solid brown; border-radius: 52px">Mise à jour de Dabakh <b>V1.3</b></h4>
			<h6>
				<b>- Ajout de la caisse dépôt de garantie</b> :
				<br>&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp--lors de l'enregistrement d'une location, vous avez la possibilté d'ajouté déposer comme garantit
				<br>&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp--Après la enregistrement des opérations peuvent être faites sur la caisse de dépots.
				<br>
			</h6>

		</div>
		<div class="modal-footer">
			<a href="#!" class="modal-close waves-effect waves-green btn-flat brown white-text">Fermer</a>
		</div>
	</div>
</body>
<?php
if (isset($_GET['a'])) {
?>
	<script type="text/javascript">
		$(document).ready(function() {
			//$('.modal').modal();
			//$('#immobilier').modal('open');
			M.toast({
				html: 'Bonjour <?= $_SESSION['prenom'] ?> <?= $_SESSION['nom'] ?>!',
				classes: 'rounded'
			});
		});
	</script>
<?php
}
?>

</html>