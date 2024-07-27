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
		<title>Demandes d'emplois</title>
		<?php include 'entete.php'; ?>
	</head>
	<body style="background-image: url(<?= $image ?>bgaccueil.jpg);">
		<?php
		include 'verification_menu_sante.php';
		?>
			<h4 class="center col s12 m12 " >Liste des demandes d'emploie </h4>
		<div class="row">
			<a href="e_demande_emploi.php" class="btn col s4 m3 l1">Ajouter</a>
		</div>
		<div class="row ">
			<div class="row transparent">
				<div class="col s4 input-field ">
				<i class="material-icons prefix white-text">search</i>
				<input type="text" class="white-text" placeholder="Recherch" name="search" id="search">
			</div>
			</div>
			<div class="row">
				<div class="col s10 offset-s1 ">
				<table class="bordered highlight centered striped">
					<thead>
						<tr style="color: #fff; background-color: #bd8a3e">
							<th data-field="">Date enregistrement</th>
							<th data-field="">Prénom et Nom</th>
							<th data-field="">Poste demandé</th>
							<th data-field="">Pièces déposées</th>
						</tr>
					</thead>
					<tbody>
					</tbody>
				</table>
			</div>
			</div>
		</div>
		
	</body>
	<style type="text/css">
		
		table
		{
			background: white;
		}
	</style>
	<script type="text/javascript">
		$(document).ready(function(){	
			var search ="";
			$.ajax({
				type:'POST',
				url:'l_demande_emploi_ajax.php',
				data:'search='+search,
				success:function (html) {
					$('tbody').html(html);
				}
			});
			$('input:first').keyup(function(){
			var search = $('input:first').val();
			$.ajax({
				type:'POST',
				url:'l_demande_emploi_ajax.php',
				data:'search='+search,
				success:function (html) {
					$('tbody').html(html);
				}
			});
				});
		});
	</script>
	<style type="text/css">

		/*import du css de materialize*/
		@import "../css/materialize.min.css" print;
		/*CSS pour la page à imprimer */
		/*Dimension de la page*/
		@page
		{
			size: portrait;
			margin: 0;
			margin-top: 25px;
		}
		@media print
		{
			button{
				display: none;
			}
			nav{
				display: none;
			}
			div
			{
			font: 12pt "times new roman";
			}
			select{
				border-color: transparent
			}
		}
	</style>
</html>