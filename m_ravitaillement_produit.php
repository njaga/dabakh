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
$req=$db->prepare("SELECT produit.produit, produit.pu, produit.qt, ravitaillement_produit.date_ravitaillement, ravitaillement_produit.qt, produit.id
FROM `ravitaillement_produit`, produit 
WHERE ravitaillement_produit.id_produit=produit.id AND ravitaillement_produit.id=?");
$req->execute(array($_GET['id']));
$donnee=$req->fetch();
$produit= $donnee['0'];
$pu= $donnee['1'];
$qt= $donnee['2'];
$date_ravitaillement= $donnee['3'];
$qt_ra= $donnee['4'];
$id_produit= $donnee['5'];
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Modification ravitaillement d'un produit</title>
		<?php include 'entete.php'; ?>
	</head>
	<body style="background-image: url(<?= $image ?>bgaccueil.jpg);">
		<?php
		include 'verification_menu_sante.php';
		?>
		<div class="container white">
			<div class="row z-depth-5" style="padding: 10px;">
				<h3 class="center">Modification ravitaillement d'un produit</h3>
				<form class="col s12" method="POST" id="form" action="m_ravitaillement_produit_trmnt.php" >
					<div class="row hide">
						<input type="number" name="id_produit" value="<?=$id_produit?>">
						<input type="number" name="id" value="<?=$_GET['id']?>">
					</div>
					<div class="row">
						<h5 class="col s12 m12">
							Produit : <b><?=$produit?></b><br>
							Prix unitaire : <b><?=number_format($pu, 0, ",", " ")." Fcfa"?></b><br>
							Quantité restante : <b><?=$qt?></b><br>
						</h5>
					</div>
					<div class="row">
						<div class="col s12 m6 input-field">
							<input type="number" value="<?=$qt ?>" required=""  name="qt" id="qt" required>
							<label for="qt">Quantite ravitailleé</label>
						</div>
						<div class="col s12 m5 input-field">
							<input type="date" required="" value="<?=$date_ravitaillement ?>" name="date_ravitaillement" id="date_ravitaillement" required>
							<label for="date_ravitaillement">Date ravitaillement</label>
						</div>
					</div>
					<div class="row">
						<div class="col s2 offset-s8 input-field">
							<input class="btn" type="submit" name="enregistrer" value="enregistrer->" >
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
				if (!confirm('Voulez-vous confirmer la modification de ce ravitaillement ?')) {
					return false;
				}
			});
		});
		
	</script>
</html>