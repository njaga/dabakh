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
$req_annee=$db->query('SELECT DISTINCT YEAR(date_courrier) FROM `courrier` ORDER BY date_courrier');
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
		<title>Courrier</title>
		 <?php include 'entete.php'; ?>
	</head>
	<body id="debut" style="background-image: url(<?= $image ?>bgaccueil.jpg);">
        <?php
            if ($_SESSION['service']=="immobilier") 
            {
                include 'verification_menu_immo.php';
            }
            elseif ($_SESSION['service']=="sante")
            {
                include 'verification_menu_sante.php';
            }
            else
            {
                include 'verification_menu_cm.php';
            }

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
				<option value="" actived>--Choisir Annee--</option>
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
				<label for="search">Recherche</label>
			</div>
			<select class="browser-default col s12 m3 offset-m1" name="annee">
				<option value="0" selected="">--Tous les courriers--</option>
				<option value="Arriver">Arriver</option>
				<option value="Depart">Départ</option>
			</select>
		</div>
		<div class="row">
			<h4 class="center #0d47a1 col s12" style="color: #252525">Courriers reçues/envoyés pendant le mois de :</h4>
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
					<tr style="color: white; background-color :#bd8a3e; border:#bd8a3e 1px solid">
						<th></th>
						<th>Date </th>
						<th>N°</th>
						<th>Type courrier</th>
						<th>Courrier</th>
						<th>Destinataire</th>
						<th>Expéditeur</th>
						<th></th>
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
			function l_courrier()
			{
				var mois = $('select:eq(2)').val();
				var type_courrier = $('select:eq(1)').val();
				var annee = $('select:eq(0)').val();
				var search = $('input:first').val();
				$.ajax({
				type:'POST',
				url:'l_courrier_ajax.php',
				data:'mois='+mois+'&annee='+annee+'&search='+search+'&type_courrier='+type_courrier,
				success:function (html) {
					$('.tbody').html(html);
				}
			});
			}
			
			l_courrier();

			$('select').change(function(){
				l_courrier();
				});
			$('input:first').keyup(function(){
			l_courrier();
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