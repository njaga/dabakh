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
if (date("n")==1)
{
	$annee=$annee-1;
}
$req_annee=$db->query('SELECT DISTINCT YEAR(date_demande) FROM `demandes_p_a` WHERE date_demande IS NOT NULL');
$dateFormat = date("Y-m-d");
$jour = array("Dimanche","Lundi","Mardi","Mercredi","Jeudi","Vendredi","Samedi");
$mois = array("","Janvier","Février","Mars","Avril","Mai","Juin","Juillet","Août","Septembre","Octobre","Novembre","Décembre");
if (date("n")==1)
{
	$datefr = $mois[12];
}
else
{
	$datefr = $mois[date("n")];
}
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Demandes Permissions/Absences</title>
		<?php include 'entete.php'; ?>
	</head>
	<body id="debut" style=" background-image: url(<?=$image ?>grid-3227320_1280.jpg) ;">
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
		<br>
		<div class="row">
			<div class="col s3 m2 l2">
				
			<select class="browser-default " name="annee">
				<option value="" disabled>--Choisir Annee--</option>
				<?php
				while ($donnee=$req_annee->fetch())
				{
				echo '<option value="'. $donnee['0'] .'"';
					if ($annee==$donnee['0']) {
					echo "selected";
					}
					echo ">";
				echo $donnee['0'] .'</option>';
				}
				?>
			</select><br>
			<a href="l_personnel_demandes.php" class="btn purple darken-4 ">Nouvelle de mande</a>
			</div>
			<div class="col s6 offset-s2 m5 l4 offset-m2 offset-l2 input-field white" style="border-radius: 45px">
				<i class="material-icons prefix">search</i>
				<input type="text" name="search" class="search" id="search">
				<label for="search">Recherche</label>
			</div>

		</div>

		<div class="row">
			<h4 class="center col s12" style="color: "><b>Demandes Permissions/Absences/Congés effectués pendant le mois de : </b></h4>
			<h5><select class="browser-default mois  col s4 offset-s2 m3 offset-m4 l2 offset-l4 center" name="mois" style="height: 40px; background-color: white;">
				<?php
				for ($i=1; $i <= 12; $i++) {
					echo "<option value='$i'";
						if ($mois[$i]==$datefr) {
							echo "selected";
						}
					echo">$mois[$i]</option>";
				}
				?>
			</select></h5>
		</div>
		<div class="row">
			<div class="col s12   ">
				<table class="bordered highlight centered striped table white">
					<thead>
						<tr style="color: #fff; background-color: #bd8a3e">
							<th>Date demande</th>
							<th>Demandeur</th>
							<th>Type demande</th>
							<th>Durée de la demande</th>
							<th>Fichier</th>
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
		background-color: #999;
		.table
		{
			background: white;
		}
	</style>
	<script type="text/javascript">
		$(document).ready(function(){
			$('.tooltipped').tooltip();
			function l_demandes_personnel(mois, annee,search)
			{
				$.ajax({
				type:'POST',
				url:'l_demandes_personnel_ajax.php',
				data:'mois='+mois+'&annee='+annee+'&search='+search,
				success:function (html) {
					$('.tbody').html(html);
				}
			});
			}
			var mois = $('select:eq(1)').val();
			var annee = $('select:eq(0)').val();
			var search = $('input:first').val();
			l_demandes_personnel(mois, annee,search);
			$('select').change(function(){
				var mois = $('select:eq(1)').val();
				var annee = $('select:eq(0)').val();
				var search = $('input:first').val();
				l_demandes_personnel(mois, annee,search);
				});
			$('input:first').keyup(function(){
			var mois = $('select:eq(1)').val();
			var annee = $('select:eq(0)').val();
			var search = $('input:first').val();
			l_demandes_personnel(mois, annee,search);
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