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
$req=$db->prepare("SELECT article.article, ravitaillement_article.montant, article.qt, ravitaillement_article.date_ravitaillement, (ravitaillement_article.qt - ravitaillement_article.ancien_qt), article.id, ravitaillement_article.ancien_qt
FROM `ravitaillement_article`, article 
WHERE ravitaillement_article.id_article=article.id AND ravitaillement_article.id=?");
$req->execute(array($_GET['id']));
$donnee=$req->fetch();
$article= $donnee['0'];
$montant= $donnee['1'];
$qt= $donnee['2'];
$date_ravitaillement= $donnee['3'];
$qt_ra= $donnee['4'];
$id_article= $donnee['5'];
$ancien_qt= $donnee['6'];
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Modification ravitaillement d'un article</title>
		<?php include 'entete.php'; ?>
	</head>
	<body style="background-image: url(<?= $image ?>bgaccueil.jpg);">
		<?php
		include 'verification_menu_cm.php';
		?>
		<div class="container white">
			<div class="row z-depth-5" style="padding: 10px;">
				<h3 class="center">Modification ravitaillement d'un article</h3>
				<form class="col s12" method="POST" id="form" action="m_ravitaillement_article_trmnt.php" >
					<div class="row hide">
						<input type="number" name="id_article" value="<?=$id_article?>">
						<input type="number" name="id" value="<?=$_GET['id']?>">
						<input type="number" name="ancien_qt" value="<?=$qt-$qt_ra?>">
					</div>
					<div class="row">
						<h5 class="col s12 m12">
							Article : <b><?=$article?></b><br>
							Montant : <b><?=number_format($montant, 0, ",", " ")." Fcfa"?></b><br>
							Quantité actuelle : <b><?=$qt?></b><br>
						</h5>
					</div>
					<div class="row">
						<div class="col s12 m3 input-field">
							<input type="number" value="<?=$qt_ra ?>" required=""  name="qt" id="qt" required>
							<label for="qt">Quantite ravitailleé</label>
						</div>
						<div class="col s12 m3 input-field">
							<input type="number" value="<?=$montant ?>" required=""  name="montant" id="montant" required>
							<label for="montant">Montant</label>
						</div>
					</div>
					<div class="row">
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