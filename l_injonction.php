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
$req_annee=$db->query('SELECT DISTINCT YEAR(date_injonction) FROM `injonction` WHERE date_injonction IS NOT NULL ORDER BY date_injonction');
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
		<title>Liste injonctions</title>
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
			<select class="browser-default col s4 m2" name="annee">
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
			<div class="col s12 m3 offset-m2 input-field white" style="border-radius: 45px">
				<i class="material-icons prefix">search</i>
				<input type="text" name="search" id="search">
				<label for="search">Locataire</label>
			</div>
		</div>
		<a href="e_injonction_locataire.php" class="btn col s8 m3 l2 offset-s3  offset-m1 offset-l1">Nouveau</a>
		<div class="row">
			<h4 class="center #0d47a1 col s12" style="color: white">Injonctions effectués pendant le mois de :</h4>
			<h5><select class="browser-default col s6 offset-s3 m2 offset-m4" name="mois" class="mois" style="background-color: white;">
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
		<div class=" row  ">
			<table class="bordered highlight centered striped table col s12">
				<thead>
					<tr style="color: #fff; background-color: #bd8a3e">
						<th></th>
						<th>Date injonction</th>
						<th>Locataire</th>
						<th>Bailleur</th>
						<th>Montant</th>
					</tr>
				</thead>
				<tbody class="tbody">
				</tbody>
			</table>
		</div>
		<span id="fin"></span>
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
			$('.fixed-action-btn').floatingActionButton();
			function l_mensualite_paye()
			{
				var mois = $('select:eq(1)').val();
				var annee = $('select:eq(0)').val();
				var search = $('input:first').val();
				$.ajax({
				type:'POST',
				url:'l_injonction_ajax.php',
				data:'mois='+mois+'&annee='+annee+'&search='+search,
				success:function (html) {
					$('.tbody').html(html);
				}
			});
			}
			
			l_mensualite_paye();

			$('select').change(function(){
				l_mensualite_paye();
				});
			$('input:first').keyup(function(){
			l_mensualite_paye();
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