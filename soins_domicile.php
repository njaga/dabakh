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
		<title>Soins à domicile</title>
		<?php
		include 'entete.php';
		?>
		
	</head>
	<body id="debut" style="background-image: url(<?= $image ?>bgaccueil.jpg);">
		<?php
		include 'verification_menu_sante.php';
		?>
		<div class="fixed-action-btn">
			 <?php
		        if ($_SESSION['fonction']=="infirmier") 
		        {
		            echo '<a class="btn-floating btn-large teal lighten-2">';
		        }
		        if ($_SESSION['fonction']=="medecin" OR $_SESSION['fonction']=="administrateur") 
		        {
		            echo '<a class="btn-floating btn-large purple darken-4">';
		        }
		        if ($_SESSION['fonction']=="secretaire") 
		        {
		            echo '<a class="btn-floating btn-large light-blue darken-4">';
		        
		        }
		      ?>
				<i class="large material-icons">import_export</i>
			</a>
			<ul>
				<li><a href="#debut" class="btn-floating green"><i class="material-icons">arrow_upward</i></a></li>
				<li><a href="#fin" class="btn-floating red darken-1"><i class="material-icons">arrow_downward</i></a></li>
			</ul>
		</div>
		<div class="container white">
			<div class="row">
				<div class="col s12 m8	  ">
					<h3 class="center-align col s12 m12"><u>Soins à domicile</u></h3>
					<div class="col s8 m6 l8 offset-s1 input-field white" style="border-radius: 45px; border: 2px black solid">
					<i class="material-icons prefix">search</i>
					<input type="text" name="search" id="search">
					<label for="search">Recherche</label>
				</div>
					<table class="bordered highlight centered ">
						<thead>
							<tr>
								<th  data-field="id"></th>
								<th data-field="">Soins</th>
								<th data-field="">Prix Unitaire</th>
							</tr>
						</thead>
						<tbody>
							
						</tbody>
							</table>
						</div>
						
						<div class="col s12 m4 teal lighten-5"  style="border-radius: 45px; border: 2px black solid">
							<div class="container center-align ">
								<form method="POST" action="soins_domicile_trmnt.php" name="ajout_nature"   >
									<h5 class="center-align" >Ajout Soins</h5>
									<div class="input-field  " >
										<input id="soins" type="text" class="validate " name="soins" >
										<label for="soins">Soins</label>
									</div>
									<div class="input-field  " >
										<input id="pu" type="number" class="validate " name="pu" >
										<label for="pu">Prix Unitaire</label>
									</div>
									<div class="input-field center-align">
										<button class="btn  waves-light blue darken-4" type="submit" name="envoyer">Ajouter
										<i class="material-icons right">send</i>
										</button>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>
				<span id="fin"></span>
			</body>
			<script type="text/javascript">
				$(document).ready(function() {
					$('.fixed-action-btn').floatingActionButton();
					$('.tooltipped').tooltip();
					function soins_domicile_ajax()
					{
						var search = $('input:first').val();
						$.ajax({
						type:'POST',
						url:'soins_domicile_ajax.php',
						data:'search='+search,
						success:function (html) {
							$('tbody').html(html);
						}
						});
					}
					soins_domicile_ajax();
					$('input:first').keyup(function(){
					soins_domicile_ajax();
					});
				})
			</script>
			<style type="text/css">
				.centrer{  margin:  auto;
			width: 800px;
			height: 25px;
			position: relative;
			right: 15%;
			
			}
			.ajout{
					position: fixed;
					right: 50px;
					top: 20%;
			}
			body {
			font-size: 16px;
			font-family: "times new roman"Cambria, Georgia, serif;
			}
			</style>
		</html>