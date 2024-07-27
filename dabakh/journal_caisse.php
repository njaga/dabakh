<?php
session_start();
if (!isset($_SESSION['poste'])) {
?>
<script type="text/javascript">
	alert("Veillez d'abord vous connectez !");
	window.location='index';
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
		<title>Etat de la caisse</title>
		<?php include 'entete.php'; ?>
	</head>
	<body style="background-image: url(../css/images/etat_caisse_banque.jpg);">
		<?php
		include 'verification_menu.php';
		?>
		<div class="row">
			<h4 class="center #0d47a1 col s12" style="color: #0d47a1">Journal de la caisse du mois de</h4>
			<h5><select class="browser-default" name="mois" class="mois" style="width: 150px; margin-left: 600px; height: 40px; background-color: transparent;">
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
				<table class="bordered highlight centered">
					<thead>
						<tr style="color: #fff; background-color: #bd8a3e">
							<th  data-field="">Date</th>
							<th  data-field="">Libellé</th>
							<th data-field="">Entrée</th>
							<th data-field="">Sortir</th>
							<th data-field="">Solde</th>
						</tr>
					</thead>
					<tbody>
					</tbody>
				</table>
			</div>
		</div>
		
	</body>
	<style type="text/css">
		
		select{
			font-family: georgia;
		}
		
		th{
			font:16pt georgia ;
			font-weight: bold;
		}
		body
		{
			background-position: center center;
			background-repeat:  no-repeat;
			background-attachment: fixed;
			background-size:  cover;
			background-color: #999;
		}
		table
		{
			background: white;
		}
	</style>
	<script type="text/javascript">
		$(document).ready(function(){
			var mois = $('select').val();
		$.ajax({
			type:'POST',
			url:'journal_caisse_ajax.php',
			data:'mois='+mois,
			success:function (html) {
				$('tbody').html(html);
			}
		});
			$('select').change(function(){
		var mois = $(this).val();
		$.ajax({
			type:'POST',
			url:'journal_caisse_ajax.php',
			data:'mois='+mois,
			success:function (html) {
				$('tbody').html(html);
			}
		});
			});
		$('.tooltipped').tooltip();
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
			.btn{
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