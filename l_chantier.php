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
		<title>Liste des chantiers en cours	</title>
		<?php include 'entete.php';?>
		
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
				<label for="search">Recherche</label>
			</div>
		</div>
		<div class="row">
			<h4 class="center col s12 white-text" >Liste des chantiers en cours</h4>
			<div class="col s12   ">
				<table class="bordered highlight centered striped">
					<thead>
						<tr style="color: #fff; background-color: #bd8a3e">
							<th></th>
							<th  data-field="">Date début</th>
							<th data-field="">Propiétaire</th>
							<th data-field="">Contact</th>
							<th data-field="">Travail demandé</th>
							<th data-field="">Emplacement</th>
							<th data-field="">Date prévu fin</th>
                            <th></th>
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
		
		table
		{
			background: white;
		}
	</style>
	<script type="text/javascript">
		$(document).ready(function(){
			$('.tooltipped').tooltip();
			$('.fixed-action-btn').floatingActionButton();
			function l_location()
			{
				var search = $('input:first').val();
				$.ajax({
				type:'POST',
				url:'l_chantier_ajax.php',
				data:'search='+search,
				success:function (html) {
					$('.tbody').html(html);
				}
			});
			}
			
			l_location();

			$('select').change(function(){
				l_location();
				});
			$('input:first').keyup(function(){
			l_location();
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