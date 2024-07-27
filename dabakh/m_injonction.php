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
$req=$db->prepare('SELECT *
FROM `injonction` 
WHERE injonction.id=?');
$req->execute(array($_GET['id']));
$donnee=$req->fetch();
$date_injonction=$donnee['1'];
$nbr_mois=$donnee['2'];
$montant=$donnee['3'];
$mnt_a_ajouter=$donnee['4'];
$a_ajouter=$donnee['5'];
$date_echeance=$donnee['6'];
$fichier=$donnee['7'];
$id_locataire=$donnee['8'];
$id_user=$donnee['9'];
 ?>
<!DOCTYPE html>
<html>
	<head>
		<title>Modification injonction</title>
	</head>
	<?php include 'entete.php'; ?>
	<body style="background-image: url(<?= $image ?>bgaccueil.jpg);">
		<?php
		include 'verification_menu_immo.php';
		?>
		<div class="container white">
			<div class="row z-depth-5" style="padding: 10px;">
				<h3 class="center">Modification d'une injonction</h3>
				<form class="col s12" method="POST" id="form" action="m_injonction_trmnt.php?id=<?=$_GET['id'] ?>" >
					<div class="row">
						<div class="col s6 m5 l3 input-field">
							<input  type="date" value="<?=$date_injonction ?>" name="date_injonction" id="date_injonction" required>
							<label for="date_injonction">Date injonction</label>
						</div>
					</div>
					<div class="row">
						<div class="col s6 m6 l4 input-field">
							<input type="number" value="<?=$nbr_mois ?>" name="nbr_mois" id="nbr_mois" value="0" required min="0">
							<label for="nbr_mois">Nombre de mois de loyer impayés</label>
						</div>
						<div class="col s6 m5 l5  input-field">
							<input type="number" value="<?=$montant ?>" name="montant_mensuel" id="montant_mensuel" value="0" required min="0">
							<label for="montant_mensuel" >Monatant des locations</label>
						</div>
					</div>
					<div class="row">
						<div class="col s6 m6 l4 input-field">
							<input type="text" value="<?=$a_ajouter ?>" name="a_ajouter" id="a_ajouter" >
							<label for="a_ajouter">A ajouter</label>
						</div>
						<div class="col s6 m5 l5  input-field">
							<input type="number" value="<?=$mnt_a_ajouter ?>" name="mnt_a_ajouter" id="mnt_a_ajouter" value="0" required min="0">
							<label for="mnt_a_ajouter" >Montans à ajouter</label>
						</div>
					</div>
					<div class="row">
						<div class="col s6 m5 l3 input-field">
							<input  type="date" value="<?=$date_echeance ?>" name="date_echeance" id="date_echeance" required>
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