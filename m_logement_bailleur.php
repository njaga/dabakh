<!DOCTYPE html>
<html>
	<head>
		<title>Sélection du bailleur </title>
		<?php include 'entete.php';?>
	</head>
	<body style="background-image: url(<?= $image ?>bgaccueil.jpg);">
		<?php
		include 'verification_menu_immo.php';
		?>
		<div class="row">
			<h4 class="center col s12 white-text" >Sélectionner le bailleur  pour le tranfert du logement</h4>
			<div class="col s12 m4 input-field white" style="border-radius: 45px">
				<i class="material-icons prefix">search</i>
				<input type="text" name="search" id="search">
				<label for="search">Prénom / Nom</label>
			</div>
            <input type="number" name="id" value="<?= $_GET['id'] ?>" hidden>
			<div class=" row">
				<table class="bordered highlight centered striped col s12">
					<thead>
						<tr style="color: #fff; background-color: #bd8a3e">
							<th  data-field="">N° dossier</th>
							<th data-field="prenom">Prénom et nom</th>
							<th data-field="tel">N° Téléphone</th>
							<th data-field="tel">Adresse</th>
							<th data-field="tel"></th>
							<th ></th>
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
			$('.tooltipped').tooltip();
			function l_bailleurs(search) {
                var id = $('input:last').val();
				$.ajax({
					type:'POST',
					url:'m_logement_bailleur_ajax.php',
					data:'search='+search+'&id='+id,
					success:function (html) {
						$('tbody').html(html);
					}
				});
			}

			var search ="";
			l_bailleurs(search);
			$('input:first').keyup(function(){
			var search = $('input:first').val();
			
			l_bailleurs(search)
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