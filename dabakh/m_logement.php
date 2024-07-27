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
$req = $db->prepare('SELECT id, `designation`, `adresse`, `pu`, `id_type`, id_bailleur from logement WHERE id=?');
$req->execute(array($_GET['id']));
$donnees = $req->fetch();
$id = $donnees['0'];
$designation = $donnees['1'];
$adresse = $donnees['2'];
$pu = $donnees['3'];
$id_type = $donnees['4'];
$id_bailleur = $donnees['5'];
?>
<!DOCTYPE html>
<html>

<head>
	<title>Modification d'un logement</title>
	<?php include 'entete.php'; ?>
</head>

<body style="background-image: url(<?= $image ?>m_logement.jpg);">
	<?php
	include 'verification_menu_immo.php';
	?>
	<div class="container white">
		<div class="row z-depth-5" style="padding: 10px;">
			<h3 class="center">Modification d'un logement</h3>
			<form class="col s12" method="POST" id="form" action="m_logement_trmnt.php?id=<?= $id ?>">
				<div class="row">
					<input type="number" name="bailleur" hidden value="<?= $id_bailleur ?>">

				</div>
				<div class="row">
					<div class="col s6 input-field">
						<input type="text" value="<?= $designation ?>" name="designation" id="designation" required>
						<label for="designation">Désignation</label>
					</div>
					<div class="col s5 input-field">
						<select class="browser-default" name="type_logement" required>
							<option value="" disabled selected>Choisir le type</option>
							<?php
							include 'connexion.php';
							$reponse = $db->query("SELECT * FROM type_logement  ORDER BY type_logement");
							while ($donnees = $reponse->fetch()) {
								echo "<option value='" . $donnees['0'] . "'";
								if ($id_type == $donnees['0']) {
									echo "selected";
								}
								echo ">";
								echo $donnees['1'];
								echo "</option>";
							}
							$reponse->closeCursor();
							?>
						</select>
					</div>
				</div>
				<div class="row">
					<div class="col s6 input-field">
						<input type="number" value="<?= $pu ?>" name="pu" id="pu" required>
						<label for="pu">Prix location HT</label>
					</div>
					<div class="col s6 input-field">
						<input type="text" value="<?= $adresse ?>" name="adresse" id="adresse" required>
						<label for="adresse">Adresse</label>
					</div>
				</div>

				<div class="row">
					<div class="col s2 offset-s8 input-field">
						<input class="btn" type="submit" name="enregistrer" value="Enregistrer">
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
			if (!confirm('Voulez-vous confirmer la modification de ce nouveau logement ?')) {
				return false;
			}
		});
	});
	$('.datepicker').datepicker({
		autoClose: true,
		yearRange: [2014, 2020],
		showClearBtn: true,
		i18n: {
			nextMonth: 'Mois suivant',
			previousMonth: 'Mois précédent',
			labelMonthSelect: 'Selectionner le mois',
			labelYearSelect: 'Selectionner une année',
			months: ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'],
			monthsShort: ['Jan', 'Fev', 'Mar', 'Avr', 'Mai', 'Jun', 'Jul', 'Août', 'Sep', 'Oct', 'Nov', 'Dec'],
			weekdays: ['Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'],
			weekdaysShort: ['Dim', 'Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam'],
			weekdaysAbbrev: ['D', 'L', 'M', 'M', 'J', 'V', 'S'],
			today: 'Aujourd\'hui',
			clear: 'Réinitialiser',
			cancel: 'Annuler',
			done: 'OK'

		},
		format: 'yyyy-mm-dd'
	});
</script>

</html>