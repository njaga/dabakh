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
		<title>Liste des produits</title>
		<?php include 'entete.php'; ?>
	</head>
	<body style="background-image: url(<?= $image ?>bgaccueil.jpg);">
		<?php
		include 'verification_menu_sante.php';
		?><br>
		<a href="immobilier.php" class="btn " >Retour au menu</a>
		<div class="row">
			<h3 class="center col s12 white-text" >Ravitaillement de produit</h3>
			<h4 class="center col s12 black-text" >Veillez sélectionner le produit</h4>
			<div class="col s4 input-field white" style="border-radius: 45px">
				<i class="material-icons prefix">search</i>
				<input type="text" name="search" id="search">
				<label for="search">Produit</label>
			</div>
			<div class="col s12   ">
				<table class="bordered highlight centered striped">
					<thead>
						<tr style="color: #fff; background-color: #bd8a3e">
							<th data-field="">Produit</th>
							<th data-field="">QT restante</th>
							<th data-field="">Prix Unitaire</th>
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
			 $('.modal').modal();
			function e_ravitaillement_produit_ajax(search) {
				$.ajax({
					type:'POST',
					url:'e_ravitaillement_produit_ajax.php',
					data:'search='+search,
					success:function (html) {
						$('tbody').html(html);
						
					}
				});
			}

			var search ="";
			e_ravitaillement_produit_ajax(search);
			$('input:first').keyup(function(){
			var search = $('input:first').val();
			e_ravitaillement_produit_ajax(search)
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