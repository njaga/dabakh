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
$req=$db->prepare('SELECT type_logement.type_logement, logement.designation  
FROM logement 
INNER JOIN type_logement ON logement.id_type=type_logement.id
WHERE logement.id=?');
$req->execute(array($_GET['id']));
$donnees=$req->fetch();
$type_logement=$donnees['0'];
$logement=$donnees['1'];
$req->closeCursor();
$totalLibre=0;
$totalOccupe=0;
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Détails logement</title>
		<?php include 'entete.php';?>
	</head>
	<body style="background-image: url(<?= $image ?>bgaccueil.jpg);">
		<?php
		include 'verification_menu_immo.php';
		?>
		<br>
		<a onclick="window.history.go(-1)" class="btn">Retour</a>
		<div class="row">
			<h3 class="center col s12 black-text" >
				<br> <b><?=$logement?> : <?=$type_logement ?></b>
			</h3>
			<div class="col s12   ">
				<table class="bordered highlight centered striped">
					<thead>
						<tr style="color: #fff; background-color: #bd8a3e">
							<th  data-field=""></th>
							<th data-field="">Date</th>
							<th data-field="">Locataire</th>
							<th data-field="">Prix location HT</th>
						</tr>
					</thead>
					<tbody>
						
						<?php
                        include 'connexion.php';
                        $db->query("SET lc_time_names = 'fr_FR';");

						$req=$db->prepare('SELECT CONCAT(locataire.prenom," ", locataire.nom), location.prix_location, CONCAT(day(location.date_debut)," ", monthname(location.date_debut)," ", year(location.date_debut)), CONCAT(day(location.date_fin)," ", monthname(location.date_fin)," ", year(location.date_fin)), location.etat
                        FROM `location`
                        INNER JOIN logement on logement.id=location.id_logement
                        INNER JOIN locataire ON locataire.id=location.id_locataire
                        WHERE logement.id=?
                        ORDER BY location.etat, location.date_debut');
						$req->execute(array($_GET['id']));
                        $resultat=$req->rowCount();
                        $i=0;
						while ($donnees= $req->fetch())
						{
                            $locataire=$donnees['0'];					
                            $prix_location=$donnees['1'];					
                            $date_debut=$donnees['2'];									
                            $date_fin="au ".$donnees['3'];					
                            $etat=$donnees['4'];
                            if($etat=="active")
                            {
                                $date_fin=" à nos jours";
                            }
                                            
                            echo "<tr>";
                                echo "<td><b>".($i=$i+1)."</b></td>";
                                echo "<td>Du ".$date_debut." ".$date_fin."</td>";
                                echo "<td>".$locataire."</td>";
                                echo "<td>".number_format($prix_location,0,'.',' ')." Fcfa</td>";
                                
                                
                            echo "</tr>";
                        }
						
						?>
					</tbody>
				</table>
				<?php
				if ($resultat<1)
				{
					echo "<h3 class='center'>Aucun résultat</h3>";
				}
				?>
			</div>
		</div>
		
		
	</body>
	<style type="text/css">
		table
		{
			background: white;
			font: 12pt "times new roman";
		}
	</style>
	<script type="text/javascript">
		$(document).ready(function(){
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
			a, .btn{
				display: none;
			}
		}
	</style>
</html>