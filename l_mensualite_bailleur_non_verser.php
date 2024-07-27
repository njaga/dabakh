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
$req_annee=$db->query('SELECT DISTINCT YEAR(date_versement) FROM `mensualite` WHERE date_versement IS NOT NULL');
$dateFormat = date("Y-m-d");
$jour = array("Dimanche","Lundi","Mardi","Mercredi","Jeudi","Vendredi","Samedi");
$mois = array("","Janvier","Février","Mars","Avril","Mai","Juin","Juillet","Août","Septembre","Octobre","Novembre","Décembre");
if (date("n")==1)
{
	$datefr = $mois[12];
}
else
{
	$datefr = $mois[date("n")-1];
}
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Mensualité Bailleur non versé</title>
		<?php include 'entete.php'; ?>
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
		<br>
		<div class="row">
			<select class="browser-default col s3 m2" name="annee">
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
			</select>
			<div class="col s6 offset-s2 m4 offset-m2 input-field white" style="border-radius: 45px">
				<i class="material-icons prefix">search</i>
				<input type="text" name="search" id="search">
				<label for="search">Bailleur</label>
			</div>
		</div>
		<div class="row">
			<h4 class="center #0d47a1 col s12" style="color: white">Locations non reversé au bailleur </h4>
			<h5><select class="browser-default mois  col s4 offset-s2 m2 offset-m6  " name="mois" style="height: 40px; background-color: white;">
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
		<div class="row tbody">
			
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
			function l_mensualite_paye(mois, annee,search)
			{
				$.ajax({
				type:'POST',
				url:'l_mensualite_bailleur_non_verser_ajax.php',
				data:'mois='+mois+'&annee='+annee+'&search='+search,
				success:function (html) {
					$('.tbody').html(html);
				}
			});
			}
			var mois = $('select:eq(1)').val();
			var annee = $('select:eq(0)').val();
			var search = $('input:first').val();
			l_mensualite_paye(mois, annee,search);
			$('select').change(function(){
				var mois = $('select:eq(1)').val();
				var annee = $('select:eq(0)').val();
				var search = $('input:first').val();
				l_mensualite_paye(mois, annee,search);
				});
			$('input:first').keyup(function(){
			var mois = $('select:eq(1)').val();
			var annee = $('select:eq(0)').val();
			var search = $('input:first').val();
			l_mensualite_paye(mois, annee,search);
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