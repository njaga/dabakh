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
include 'connexion.php';
$req=$db->query('SELECT DISTINCT YEAR(date_versement) FROM `mensualite` WHERE date_versement IS NOT NULL');
$dateFormat = date("Y-m-d");
$jour = array("Dimanche","Lundi","Mardi","Mercredi","Jeudi","Vendredi","Samedi");
$mois = array("","Janvier","Février","Mars","Avril","Mai","Juin","Juillet","Août","Septembre","Octobre","Novembre","Décembre");
$datefr = $mois[date("n")];
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Liste mensualités</title>
		<?php include 'entete.php';?>
	</head>
	<body style="background-image: url(<?= $image ?>bgaccueil.jpg);">
		<?php
		include 'verification_menu_immo.php';
		?>
		<br>
		<div class="row">
			<input type="text" name="" hidden="" value="<?=$_GET['id'] ?>">
			<select class="browser-default col s12 m2" name="annee">
				<option value="" disabled>--Choisir Annee--</option>
				<?php
				while ($donnee=$req->fetch())
				{
					echo '<option value="'. $donnee['0'] .'">'. $donnee['0'] .'</option>';
				}
				?>
			</select>
		</div>
		<div class="row">
			<h4 class="center #0d47a1 col s12 m12" style="color: white">Consultation bailleur du mois de :</h4>
			<h5><select class="browser-default col s4" name="mois" class="mois" style="width: 200px; margin-left: 600px; height: 40px; background-color: white;">
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
		<div class="row">
			<div class="col s12   ">
				<table class="bordered highlight centered striped table">
					<thead>
						<tr style="color: #fff; background-color: #bd8a3e">
							<th>Locataire</th>
							<th>Logement</th>
							<th>Prix location HT</th>
							<th>Date versement</th>
							<th>Type</th>
							<th>Montant versé</th>
						</tr>
					</thead>
					<tbody class="tbody">
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
		.table
		{
			background: white;
		}
	</style>
	<script type="text/javascript">
		$(document).ready(function(){
			$('.tooltipped').tooltip();
			function l_locataire_bailleur()
			{
				var mois = $('select:eq(1)').val();
				var annee = $('select:eq(0)').val();
				var search = $('input:first').val();
				$.ajax({
				type:'POST',
				url:'l_locataire_bailleur_ajax.php',
				data:'mois='+mois+'&annee='+annee+'&search='+search,
				success:function (html) {
					alert(html);
					$('.tbody').html(html);
				}
			});
			}
			
			l_locataire_bailleur();

			$('select').change(function()
			{
				l_locataire_bailleur();
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
</html>l_locataire_bailleur