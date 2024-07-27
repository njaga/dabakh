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
		<title>Choix logements</title>
		
	</head>
	<?php include 'entete.php'; ?>
	<body style="background-image: url(<?= $image ?>bgaccueil.jpg);">
		<?php
		include 'verification_menu_immo.php';
		?>
		<div class="row">
			<h3 class="center col s12 black-text" >Choix du logement pour une nouvelle location</h3>
			<div class="col s4 input-field white" style="border-radius: 45px">
				<i class="material-icons prefix">search</i>
				<input type="text" name="search" id="search">
				<label for="search">Prénom / Nom</label>
			</div>
			<?php
				if (isset($_GET['id'])) 
				{
					$_SESSION['id_locataire']=$_GET['id'];
				}
			?>
			<div class="col s12   ">
				<table class="bordered highlight centered striped">
					<thead>
						<tr style="color: #fff; background-color: #bd8a3e">
							<th  data-field=""></th>
							<th data-field="">Bailleur</th>
							<th data-field="">Logement</th>
							<th data-field="">Type</th>
							<th data-field="">Nombre libre</th>
							<th data-field="">Adresse</th>
							<th data-field="">Prix location HT</th>
							<th data-field=""></th>
						</tr>
					</thead>
					<tbody>
					</tbody>
				</table>
			</div>
		</div>
		
	</body>
	<style type="text/css">
		body
		{
			background-position: center center;
			background-repeat:  no-repeat;
			background-attachment: fixed;
			background-size:  cover;
			background-color: #999;
		}
		table
		{
			background: white;
			font: 12pt "times new roman";
		}
	</style>
	<script type="text/javascript">
		$(document).ready(function(){
		function l_bailleur(search) {
				$.ajax({
					type:'POST',
					url:'e_location_ajax.php',
					data:'search='+search,
					success:function (html) {
						$('tbody').html(html);
					}
				});
			}

			var search ="";
			l_bailleur(search);
			$('input:first').keyup(function(){
			var search = $('input:first').val();
			l_bailleur(search)
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
			a, .btn{
				display: none;
			}
		}
	</style>
</html>