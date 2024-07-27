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
$dateFormat = date("Y-m-d");
$jour = array("Dimanche","Lundi","Mardi","Mercredi","Jeudi","Vendredi","Samedi");
$mois = array("","Janvier","Février","Mars","Avril","Mai","Juin","Juillet","Août","Septembre","Octobre","Novembre","Décembre");
$datefr = $mois[date("n")];
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Liste mensualités</title>
		<?php include 'entete.php'; ?>
	</head>
	<body style="background-image: url(<?= $image ?>bgaccueil.jpg);">
		<?php
		include 'verification_menu_immo.php';
		?>
		<table>
			<thead style="border : 0px solid">
				<th style="border : 0px solid" >
					<td style="border : 0px solid">
						
					</td>
				</th>
			</thead>
			<tbody>
				<tr>
					<td>
						<div class="row">
						<h4 class="center #0d47a1 col s12" style="color: white">Liste des paiements non effectué du mois de</h4>
						<h5><select class="browser-default col s4" name="mois" class="mois" style="width: 200px; margin-left: 600px; height: 40px; background-color: white;">
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
						<div class="col s12   ">
							<table class="bordered highlight centered striped table">
								<thead>
									<tr style="color: #fff; background-color: #bd8a3e">
										<th>Mois</th>
										<th>Locataire</th>
										<th>Logement</th>
										<th>Montant</th>
										<th>Date versement</th>
									</tr>
								</thead>
								<tbody class="tbody">
								</tbody>
							</table>
						</div>
					</div>
					</td>
				</tr>
			</tbody>
		</table>
		
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
			var mois = $('select').val();
		$.ajax({
			type:'POST',
			url:'l_n_mensualite_ajax.php',
			data:'mois='+mois,
			success:function (html) {
				$('.tbody').html(html);
			}
		});
			$('select').change(function(){
		var mois = $(this).val();
		$.ajax({
			type:'POST',
			url:'l_n_mensualite_ajax.php',
			data:'mois='+mois,
			success:function (html) {
				$('.tbody').html(html);
			}
		});
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