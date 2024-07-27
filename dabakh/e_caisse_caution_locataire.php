<!DOCTYPE html>
<html>
	<head>
		<title>Liste des locataires</title>
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
			<h4 class="center col s12 white-text" >Liste des locataires actif</h4>
			<div class="col s4 input-field white" style="border-radius: 45px">
				<i class="material-icons prefix">search</i>
				<input type="text" name="search" id="search">
				<label for="search">Prénom / Nom</label>
			</div>
			<div class="col s12   ">
				<table class="bordered highlight centered striped">
					<thead>
						<tr style="color: #fff; background-color: #bd8a3e">
							<th  data-field=""></th>
							<th  data-field="">N° dossier</th>
							<th data-field="prenom">Prénom et nom</th>
							<th data-field="nom">cni</th>
							<th data-field="tel">N° Téléphone</th>
							<th ></th>
						</tr>
					</thead>
					<tbody>
						
						
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
			function l_locataires(search) {
				$.ajax({
					type:'POST',
					url:'e_caisse_caution_locataire_ajax.php',
					data:'search='+search,
					success:function (html) {
						$('tbody').html(html);
					}
				});
			}

			var search ="";
			l_locataires(search);
			$('input:first').keyup(function(){
			var search = $('input:first').val();
			l_locataires(search)
				});
			$('.fixed-action-btn').floatingActionButton();
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