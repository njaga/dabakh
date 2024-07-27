<!DOCTYPE html>
<html>
	<head>
		<title>Liste logements occupé</title>
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
		<div class="row">
			<h3 class="center col s12 white-text" >Liste des logements occupés</h3>
			<div class="col s12 ">
				<table class="bordered highlight centered striped" >
					<thead>
						<tr style="color: #fff; background-color: #bd8a3e">
							<th  data-field=""></th>
							<th data-field="">Bailleur</th>
							<th data-field="">Logement</th>
							<th data-field="">Type</th>
							<th data-field="">Nombre occupé</th>
							<th data-field="">Adresse</th>
							<th data-field="">Prix location HT</th>
						</tr>
					</thead>
					<tbody>
						
						<?php
						include 'connexion.php';
						$total=0;
						$db->query("SET lc_time_names = 'fr_FR';");
						$reponse=$db->query("SELECT logement.id, CONCAT(bailleur.prenom,' ', bailleur.nom), logement.designation, type_logement.type_logement, logement.adresse, logement.pu, logement.nbr_occupe
						FROM `logement`, bailleur, type_logement  
						WHERE logement.id_type=type_logement.id AND logement.id_bailleur=bailleur.id AND logement.nbr_occupe>0");
						$resultat=$reponse->rowCount();
						while ($donnees= $reponse->fetch())
						{
						$id=$donnees['0'];					
						$bailleur=$donnees['1'];					
						$logement=$donnees['2'];					
						$type_logement=$donnees['3'];					
						$adresse=$donnees['4'];									
						$pu=$donnees['5'];					
						$nbr_occupe=$donnees['6'];	
						++$total;				
						echo "<tr>";
							echo "<td></td>";
							echo "<td>".$bailleur."</td>";
							echo "<td>".$logement."</td>";
							echo "<td>".$type_logement."</td>";
							echo "<td>".$nbr_occupe."</td>";
							echo "<td>".$adresse."</td>";
							echo "<td>".number_format($pu,0,'.',' ')." Fcfa</td>";
						echo "</tr>";}
						echo "<tr class='grey'>";
							echo"<td colspan='3'><b>TOTAL</b></td>";
							echo"<td colspan='3'><b>".$total." logements occupés</b></td>";
						echo "</tr>";
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
		<span id="fin"></span>
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