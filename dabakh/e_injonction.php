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
$req=$db->prepare('SELECT prenom, nom FROM locataire WHERE id=?');
$req->execute(array($_GET['id']));
$donnee=$req->fetch();
$locataire=$donnee['0']." ".$donnee['1'];
 ?>
<!DOCTYPE html>
<html>
	<head>
		<title>Nouvel injonction</title>
	</head>
	<?php include 'entete.php'; ?>
	<body style="background-image: url(<?= $image ?>bgaccueil.jpg);">
		<?php
		include 'verification_menu_immo.php';
		?>
		<div class="container white">
			<div class="row z-depth-5" style="padding: 10px;">
				<h3 class="center">Nouvel injonction pour <br><?=$locataire ?></h3>
				<form class="col s12" method="POST" id="form" action="e_injonction_trmnt.php?id=<?=$_GET['id'] ?>" >
					<div class="row">
						<div class="col s6 m5 l3 input-field">
							<input  type="date" name="date_injonction" id="date_injonction" required>
							<label for="date_injonction">Date injonction</label>
						</div>
					</div>
					<div class="row">
						<div class="col s6 m6 l4 input-field">
							<input type="number" name="nbr_mois" id="nbr_mois" value="0" required min="0">
							<label for="nbr_mois">Nombre de mois de loyer impayés</label>
						</div>
						<div class="col s6 m5 l5  input-field">
							<input type="number" name="montant_mensuel" id="montant_mensuel" value="0" required min="0">
							<label for="montant_mensuel" >Monatant des locations</label>
						</div>
					</div>
					<div class="row">
						<div class="col s6 m6 l4 input-field">
							<input type="text" name="a_ajouter" id="a_ajouter" >
							<label for="a_ajouter">A ajouter</label>
						</div>
						<div class="col s6 m5 l5  input-field">
							<input type="number" name="mnt_a_ajouter" id="mnt_a_ajouter" value="0" required min="0">
							<label for="mnt_a_ajouter" >Montans à ajouter</label>
						</div>
					</div>
					<div class="row">
						<div class="col s6 m5 l3 input-field">
							<input  type="date" name="date_echeance" id="date_echeance" required>
							<label for="date_echeance">Date échéance</label>
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