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
$req=$db->prepare('SELECT CONCAT(bailleur.prenom," ", bailleur.nom), bailleur.pourcentage 
FROM bailleur
WHERE bailleur.id=?');
$req->execute(array($_GET['id']));
$donnees=$req->fetch();
$bailleur=$donnees['0'];
$pourcentage=$donnees['1'];
$req->closeCursor();
$totalLibre=0;
$totalOccupe=0;
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Logements bailleur</title>
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
				Logement(s) du bailleur <b><?=$bailleur?></b>
				<br>Pourcentage commission : <b><?=$pourcentage?>%</b>
			</h3>
			<div class="col s12   ">
				<table class="bordered highlight centered striped">
					<thead>
						<tr style="color: #fff; background-color: #bd8a3e">
							<th  data-field=""></th>
							<th data-field="">Logement</th>
							<th data-field="">Adresse</th>
							<th data-field="">Nbr libre</th>
							<th data-field="">Nbr occupé</th>
							<th data-field="">Nbr Total</th>
							<th data-field="">Prix location HT</th>
						</tr>
					</thead>
					<tbody>
						
						<?php
						include 'connexion.php';
						$req=$db->prepare('SELECT CONCAT(bailleur.prenom," ", bailleur.nom), type_logement.type_logement, logement.designation, logement.adresse, logement.nbr, logement.nbr_occupe,(logement.nbr+ logement.nbr_occupe), logement.pu, logement.id
						FROM bailleur, logement, type_logement
						WHERE bailleur.id=logement.id_bailleur AND logement.id_type=type_logement.id AND bailleur.id=? and logement.etat="actif"');
						$req->execute(array($_GET['id']));
						$resultat=$req->rowCount();
						while ($donnees= $req->fetch())
						{
						$id=$donnees['0'];					
						$type_logement=$donnees['1'];					
						$logement=$donnees['2'];					
						$adresse=$donnees['3'];									
						$nbr=$donnees['4'];					
						$nbr_occupe=$donnees['5'];					
						$nbr_total=$donnees['6'];					
						$pu=$donnees['7'];										
						echo "<tr>";
							echo "<td><a href='supprimer_logement_ajax.php?id=".$donnees['8']."'><i class='material-icons red-text'>clear</i></a>&nbsp&nbsp";
								echo "<a href='a_s_logement.php?id=".$donnees['8']."&amp;nbr=".$donnees['4']."&amp;a=a'>+1</a>&nbsp&nbsp";
								echo "<a href='a_s_logement.php?id=".$donnees['8']."&amp;nbr=".$donnees['4']."&amp;a=s'>-1</a>&nbsp&nbsp</td>";
							echo "<td>".$type_logement." : ".$logement."</td>";
							echo "<td>".$adresse."</td>";
							echo "<td>".str_pad($nbr, 2,"0",STR_PAD_LEFT)."</td>";
							echo "<td>".str_pad($nbr_occupe, 2,"0",STR_PAD_LEFT)."</td>";
							echo "<td>".str_pad($nbr_total, 2,"0",STR_PAD_LEFT)."</td>";
							echo "<td>".number_format($pu,0,'.',' ')." Fcfa</td>";
							$totalOccupe=$totalOccupe+$nbr_occupe;
							$totalLibre=$totalLibre+$nbr;
							
						echo "</tr>";}
						echo "<tr>";
						echo "<td colspan='3'><b>TOTAL</b></td>";
						echo "<td ><b>".str_pad($totalLibre, 2,"0",STR_PAD_LEFT)."</b></td>";
						echo "<td ><b>".str_pad($totalOccupe, 2,"0",STR_PAD_LEFT)."</b></td>";
						echo "<td ><b>".str_pad(($totalLibre+$totalOccupe), 2,"0",STR_PAD_LEFT)."</b></td>";
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
		
		<div class="row">
			<h3 class="center col s12 white-text" >Locataire(s) du bailleur <b><?=$bailleur?></b></h3>
			<div class="col s12   ">
				<table class="bordered highlight centered striped">
					<thead>
						<tr style="color: #fff; background-color: #bd8a3e">
							<th  data-field=""></th>
							<th data-field="">Locataire</th>
							<th data-field="">Logement</th>
							<th data-field="">Prix location HT</th>	
						</tr>
					</thead>
					<tbody>
						
						<?php
						include 'connexion.php';
						$req=$db->prepare("SELECT DISTINCT location.id, CONCAT(locataire.prenom,' ', locataire.nom), CONCAT(type_logement.type_logement,' à ',logement.adresse), location.prix_location FROM logement, locataire, location, bailleur, type_logement WHERE location.id_locataire=locataire.id AND location.id_logement=logement.id AND logement.id_bailleur=bailleur.id AND type_logement.id = logement.id_type AND location.etat='active' AND bailleur.id=? order by location.prix_location ASC");
						$req->execute(array($_GET['id']));
						$resultat=$req->rowCount();
						$i=0;
						while ($donnees= $req->fetch())
						{
						$id=$donnees['0'];					
						$locataire=$donnees['1'];					
						$logement=$donnees['2'];					
						$prix_location=$donnees['3'];							
						echo "<tr>";
						$i=$i+1;
							echo "<td class=' grey white-text'><a href='i_contrat_location.php?id=".$id."'>".($i)."</a></td>";
							echo "<td>".$locataire."</td>";
							echo "<td>".$logement."</td>";
							echo "<td>".number_format($prix_location,0,'.',' ')." Fcfa</td>";
							echo "<td><a class='red btn' href='desactiver_location.php?id=".$id."' onclick='return(confirm(\"Voulez-vous mettre fin au contrat de location ?\"))'>Désactiver</a>
								</td>";
							
						echo "</tr>";}
						
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