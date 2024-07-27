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
		<title>Liste des clients</title>
		<?php include 'entete.php';?>
	</head>
	<body style="background-image: url(<?= $image ?>bgaccueil.jpg);">
		<?php
		include 'verification_menu_cm.php';
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
		<!-- Modal Structure -->
		<div id="modal1" class="modal">
			<div class="modal-content ">
				<h4 class='center'><b>Ajouter un nouveau client</b></h4>
				<form action="e_client_trmnt.php" method="post">
					<div class="row">
						<div class="col s5 input-field">
							<input type="text" name="prenom" id="prenom" required>
							<label for="prenom">Prénom(s)</label>						
						</div>
						<div class="col s5 input-field">
							<input type="text" name="nom" id="nom" required>
							<label for="nom">Nom</label>	
						</div>
					</div>
                    <div class="row">
                    <select class="browser-default col s4" name="sexe" class="sexe">
                        <option value="" selected disabled>Sexe</option>
                        <option value="Masculin" >Masculin</option>
                        <option value="Feminin" >Féminin</option>
                    </select>
                    </div>
					<div class="row">
						<div class="col s5 input-field">
							<input type="number" name="telephone" id="telephone" required>
							<label for="telephone">Téléphone</label>						
						</div>
						<div class="col s5 input-field">
							<input type="text" name="adresse" id="adresse">
							<label for="adresse">Adresse</label>	
						</div>
					</div>
					<div class="row">
						<div class="col s5 input-field">
						<input type="submit" value="Enregistrer" class="btn">
						</div>
					</div>
				</form>
			</div>
			<div class="modal-footer">
			<a href="#!" class="modal-close waves-effect waves-green btn btn-flat red">Annuler</a>
			</div>
        </div>
		<div class="row">
			<h4 class="center col s12 white-text" >Liste des clients</h4>
			<div class="col s10 m4 input-field white" style="border-radius: 45px">
				<i class="material-icons prefix">search</i>
				<input type="text" name="search" id="search" class="search">
				<label for="search">Recherche</label>
			</div>
			<a class='waves-effect waves-light  modal-trigger col s4 m2 offset-m2 btn' href='#modal1'>Ajouter +</a>
			<div class="col s12   ">
				<table class="bordered highlight centered striped">
					<thead>
						<tr style="color: #fff; background-color: #bd8a3e">
							<th></th>
							<th data-field="prenom">Prénom(s)</th>
							<th data-field="prenom">Nom</th>
							<th data-field="tel">N° Téléphone</th>
							<th data-field="tel">Adresse</th>
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
			$('.modal').modal();
			$('.tooltipped').tooltip();
			function l_client() {
				var search = $('#search').val();
				$.ajax({
					type:'POST',
					url:'e_vente_client_ajax.php',
					data:'search='+search,
					success:function (html) {
						$('tbody').html(html);
					}
				});
			}

			l_client();
			$('#search').keyup(function(){
			l_client()
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