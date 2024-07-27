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
$annee=date("Y");
$req=$db->query('SELECT DISTINCT YEAR(date_depense) FROM `cotisation_locataire`');
$req_bailleur=$db->query('SELECT * FROM bailleur ORDER BY nom, prenom');
$dateFormat = date("Y-m-d");
$jour = array("Dimanche","Lundi","Mardi","Mercredi","Jeudi","Vendredi","Samedi");
$mois = array("","Janvier","Février","Mars","Avril","Mai","Juin","Juillet","Août","Septembre","Octobre","Novembre","Décembre");
$datefr = $mois[date("n")];
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Cotisation locataire</title>
		 <?php include 'entete.php'; ?>
	</head>
	<body style="background-image: url(<?= $image ?>bgaccueil.jpg);">
		<?php
		include 'verification_menu_immo.php';
		?>
		<br>
		<div class="row">
			<select class="browser-default col s4 m2" name="annee">
				<option value="" disabled>--Choisir Annee--</option>
				<?php
				while ($donnee=$req->fetch())
				{
					
	                echo '<option value="'. $donnee['0'] .'"';
	                if ($annee==$donnee['0']) {
	                    echo "selected";
	                }
	                echo ">"; 
	                echo $donnee['0'] .'</option>';
				}
				?>
			</select>
			<div class="col s12 m3 offset-m2 input-field white" style="border-radius: 45px">
				<i class="material-icons prefix">search</i>
				<input type="text" name="search" id="search">
				<label for="search">Locataire</label>
			</div>
			<select class="browser-default col s12 m3 offset-m1" name="annee">
				<option value="0" selected="">--Tous les bailleurs--</option>
				<?php
				while ($donnee_bailleur=$req_bailleur->fetch())
				{
					echo '<option value="'. $donnee_bailleur['0'] .'">'. $donnee_bailleur['2'] .' '.$donnee_bailleur['3'].'</option>';
				}
				?>
			</select>
		</div>
		<div class="row">
			<a class="btn col s4 m2 " href="e_cotisation_locataire.php">Nouvelle cotisation</a>
			<a class="btn col s4 m2 offset-s1 offset-m1" href="e_cotisation_locataire_depense.php">Nouvelle dépense</a>
			<h4 class="center #0d47a1 col s12 m9" style="color: white">Cotisations locataire effectuées pendant le mois de :</h4>
			<h5><select class="browser-default col s6 offset-s3 m2 offset-m5" name="mois" class="mois" style="background-color: white;">
				<?php
				for ($i=1; $i <= 12; $i++) {
					echo "<option value='$mois[$i]'";
					if ($mois[$i]==$datefr) {
						echo "selected";
					}
					echo">$mois[$i]</option>";
				}
				?>
			</select></h5>
		</div>
		<div class=" row  ">
			<table class="bordered highlight centered striped table col s12">
				<thead>
					<tr style="color: #fff; background-color: #bd8a3e">
						<th></th>
						<th>Date</th>
						<th>locataire</th>
						<th>Type</th>
						<th>Motif</th>
						<th>Montant</th>
					</tr>
				</thead>
				<tbody class="tbody">
				</tbody>
			</table>
		</div>
		
	</body>
	<style type="text/css">
		
		.table
		{
			background: white;
		}
	</style>
	<script type="text/javascript">
		$(document).ready(function(){
			$('.tooltipped').tooltip();
			function l_cotisation_locataire()
			{
				var mois = $('select:eq(2)').val();
				var bailleur = $('select:eq(1)').val();
				var annee = $('select:eq(0)').val();
				var search = $('input:first').val();
				$.ajax({
				type:'POST',
				url:'l_cotisation_locataire_ajax.php',
				data:'mois='+mois+'&annee='+annee+'&search='+search+'&bailleur='+bailleur,
				success:function (html) {
					$('.tbody').html(html);
				}
			});
			}
			
			l_cotisation_locataire();

			$('select').change(function(){
				l_cotisation_locataire();
				});
			$('input:first').keyup(function(){
			l_cotisation_locataire();
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
			size: landscape;
			margin: 0;
			margin-top: 25px;
		}
		@media print
		{
			.btn,.img{
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