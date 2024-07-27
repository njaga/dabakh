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
		<title>Liste contact</title>
		<?php include 'entete.php'; ?>
	</head>
	<body style="background-image: url(<?= $image ?>bgaccueil.jpg);">
		<?php
		if ($_SESSION['service']=="immobilier")
		{
		include 'verification_menu_immo.php';
		}
		else
		{
		include 'verification_menu_sante.php';
		}
		?>
		<div class="row">
			<h3 class="center col s12 m12 black-text" >Liste des contacts</h3>
			<div class="col s4 offset-s2 input-field white" style="border-radius: 30px">
				<i class="material-icons prefix">search</i>
				<input type="text" name="search" id="search">
				<label for="search">Prénom / Nom</label>
			</div>
		</div>
		<div class="row">
			<table class="col s12 m12    bordered highlight centered striped">
					<thead>
						<tr style="color: #fff; background-color: #bd8a3e">
							<th data-field="">Prénom / Nom</th>
							<th data-field="">Téléphone</th>
							<th data-field="">Mail</th>
							<th data-field="">Autres Informations</th>
						</tr>
					</thead>
					<tbody>
					</tbody>
				</table>
		</div>
		
	</body>
	<script type="text/javascript">
		$(document).ready(function(){
			$('.modal').modal();
			function l_logement_libre(search) {
				$.ajax({
					type:'POST',
					url:'l_contact_ajax.php',
					data:'search='+search,
					success:function (html) {
						$('tbody').html(html);
					}
				});
			}
			var search ="";
			l_logement_libre(search);
			$('input:first').keyup(function(){
			var search = $('input:first').val();
			l_logement_libre(search)
				});
			$('.tooltipped').tooltip();
	});
	</script>
	<style type="text/css">
		table
		{
			background: white;
			font: 12pt "times new roman";
		}
	</style>
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
			a, .btn{
				display: none;
			}
		}
	</style>
</html>