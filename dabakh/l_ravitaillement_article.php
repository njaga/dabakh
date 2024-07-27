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
$req_annee=$db->query('SELECT DISTINCT YEAR(date_ravitaillement) FROM `ravitaillement_article`');
$dateFormat = date("Y-m-d");
$jour = array("Dimanche","Lundi","Mardi","Mercredi","Jeudi","Vendredi","Samedi");
$mois = array("","Janvier","Février","Mars","Avril","Mai","Juin","Juillet","Août","Septembre","Octobre","Novembre","Décembre");
$datefr = $mois[date("n")];
?>
<!DOCTYPE html>
<html>
	<head>
		<?php include 'entete.php'; ?>
		<title>Liste ravitaillements</title>
	</head>
	<body style="background-image: url(<?= $image ?>bgaccueil.jpg);">
		<?php
		include 'verification_menu_cm.php';
		?>
		<br>
		<div class="row">
			<select class="browser-default col s4 m2 l2" name="annee">
				<option value="" disabled>--Choisir Annee--</option>
				<?php
				while ($donnee=$req_annee->fetch())
				{
					echo '<option value="'. $donnee['0'] .'">'. $donnee['0'] .'</option>';
				}
				?>
			</select>
			<div class="col s6 offset-s1 m5 offset-m2 l5 offset-l2 input-field white" style="border-radius: 45px">
				<i class="material-icons prefix">search</i>
				<input type="text" name="search" id="search">
				<label for="search">Article</label>
			</div>
		</div>
		<div class="row">
			<h4 class="center #0d47a1 col s12" style="color: white">Ravitaillement article effectués pendant le mois de : </h4>
		      <li>
		        <a href="article.php" class="btn col s8 m3 l3">Nouveau ravitaillement</a>
		      </li>			
			<h5><select class="browser-default col s8 offset-s3 m2 offset-m2" name="mois" class="mois" style="background-color: white;">
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
							<th></th>
							<th>Date</th>
							<th>Article</th>
							<th>Montant</th>
							<th>QT avant ravitaillement</th>
							<th>Nouvelle Quantitée</th>
						</tr>
					</thead>
					<tbody class="tbody">
					</tbody>
				</table>
			</div>
		</div>
		
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
			function l_ravitaillement_article_ajax(mois, annee,search)
			{
				$.ajax({
				type:'POST',
				url:'l_ravitaillement_article_ajax.php',
				data:'mois='+mois+'&annee='+annee+'&search='+search,
				success:function (html) {
					$('.tbody').html(html);
				}
			});
			}
			var mois = $('select:eq(1)').val();
			var annee = $('select:eq(0)').val();
			var search = $('input:first').val();
			l_ravitaillement_article_ajax(mois, annee,search);

			$('select').change(function(){
				var mois = $('select:eq(1)').val();
				var annee = $('select:eq(0)').val();
				var search = $('input:first').val();
				l_ravitaillement_article_ajax(mois, annee,search);
				});
			$('input:first').keyup(function(){
			var mois = $('select:eq(1)').val();
			var annee = $('select:eq(0)').val();
			var search = $('input:first').val();
			l_ravitaillement_article_ajax(mois, annee,search);
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