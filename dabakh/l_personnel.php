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
		<title>Liste du personnel</title>
		<?php include 'entete.php'; ?>
	</head>
	<body style="background-image: url(<?= $image ?>bgaccueil.jpg);">
		<?php
		include 'verification_menu_sante.php';
		?>
		<div class="row ">
			<div class="col s4 input-field black-text white" style="border-radius: 20px">
				<i class="material-icons prefix ">search</i>
				<input type="text" class="" placeholder="Recherche " name="search" id="search">
			</div>
		</div>
		<div class="row">
			<h4 class="center col s12 white-text" >Liste du personnel</h4>
			<div class="col s12   ">
				<table class="bordered highlight centered striped">
					<thead>
						<tr style="color: #fff; background-color: #bd8a3e">
							<th  data-field=""></th>
							<th data-field="prenom">Prénom</th>
							<th data-field="nom">Nom</th>
							<th data-field="nom">Fonction</th>
							<th data-field="tel">Date d'embauche</th>
							<th data-field="tel">N° Téléphone</th>
							<th data-field="adr">login</th>
							<th data-field="adr">Service</th>
						</tr>
					</thead>
					<tbody>
						
					</tbody>
				</table>
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
			function l_personnel()
			{
				var search = $('input:first').val();
				$.ajax({
				type:'POST',
				url:'l_personnel_ajax.php',
				data:'search='+search,
				success:function (html) {
					$('tbody').html(html);
				}
			});
			}
			l_personnel();
			$('input:first').keyup(function(){
			l_personnel();
				});
			$('.tooltipped').tooltip();
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