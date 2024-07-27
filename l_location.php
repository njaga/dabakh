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
$req_bailleur = $db->query('SELECT * FROM bailleur ORDER BY nom, prenom');

?>
<!DOCTYPE html>
<html>

<head>
	<title>Liste des locations actives </title>
	<?php include 'entete.php'; ?>

</head>

<body id="debut" style="background-image: url(<?= $image ?>bgaccueil.jpg);">
	<?php
	include 'verification_menu_immo.php';
	?>
	<div class="fixed-action-btn">
		<a class="btn-floating btn-large brown">
			<i class="large material-icons">import_export</i>
		</a>
		<ul>
			<li><a href="#debut" class="btn-floating green"><i class="material-icons">arrow_upward</i></a></li>
			<li><a href="#fin" class="btn-floating red darken-1"><i class="material-icons">arrow_downward</i></a></li>
		</ul>
	</div>
	<div class="row">
		<div class="col s12 m3 offset-m2 input-field white" style="border-radius: 45px">
			<i class="material-icons prefix">search</i>
			<input type="text" name="search" id="search">
			<label for="search">Locataire</label>
		</div>
		<select class="browser-default col s12 m3 offset-m1" name="annee">
			<option value="0" selected="">--Tous les bailleurs--</option>
			<?php
			while ($donnee_bailleur = $req_bailleur->fetch()) {
				echo '<option value="' . $donnee_bailleur['0'] . '">' . $donnee_bailleur['2'] . ' ' . $donnee_bailleur['3'] . '</option>';
			}
			?>
		</select>
	</div>
	<div class="row">
		<h4 class="center col s12 black-text">Liste des locations actives</h4>
		<div class="col s12   ">
			<table class="bordered highlight centered striped">
				<thead>
					<tr style="color: #fff; background-color: #bd8a3e">
						<th></th>
						<th data-field="">Date début</th>
						<th data-field="">Locataire</th>
						<th data-field="">Designation</th>
						<th data-field="">Bailleur</th>
						<th data-field="">Caution</th>
						<th data-field="">Montant mensuel</th>
					</tr>
				</thead>
				<tbody class="tbody">


				</tbody>
			</table>

		</div>
	</div>
	<span id="fin"></span>
</body>
<style type="text/css">
	table {
		background: white;
	}
</style>
<script type="text/javascript">
	$(document).ready(function() {
		$('.tooltipped').tooltip();
		$('.fixed-action-btn').floatingActionButton();

		function l_location() {
			var bailleur = $('select:eq(0)').val();
			var search = $('input:first').val();
			$.ajax({
				type: 'POST',
				url: 'l_location_ajax.php',
				data: 'search=' + search + '&bailleur=' + bailleur,
				success: function(html) {
					$('.tbody').html(html);
				}
			});
		}

		l_location();

		$('select').change(function() {
			l_location();
		});
		$('input:first').keyup(function() {
			l_location();
		});
	});
</script>
<style type="text/css">
	/*import du css de materialize*/
	@import "../css/materialize.min.css"print;

	/*CSS pour la page à imprimer */
	/*Dimension de la page*/
	@page {
		size: portrait;
		margin: 0;
		margin-top: 25px;
	}

	@media print {
		button {
			display: none;
		}

		nav {
			display: none;
		}

		div {
			font: 12pt "times new roman";
		}

		select {
			border-color: transparent
		}
	}
</style>

</html>