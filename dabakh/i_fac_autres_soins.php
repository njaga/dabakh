<?php
$formatter = \NumberFormatter::create('fr_FR', \NumberFormatter::SPELLOUT);
$formatter->setAttribute(\NumberFormatter::FRACTION_DIGITS, 0);
$formatter->setAttribute(\NumberFormatter::ROUNDING_MODE, \NumberFormatter::ROUND_HALFUP);
include 'connexion.php';
	$db->query("SET lc_time_names='fr_FR';");
	$req=$db->prepare("SELECT  patient_externe.id, patient_externe.prenom,patient_externe.nom, CONCAT(day(date_analyse),' ', monthname(date_analyse),' ', year(date_analyse))
FROM  patient_externe WHERE id=?");
	$req->execute(array($_GET['id']));
	$donnees=$req->fetch();
	$id=$donnees['0'];
	$prenom=$donnees['1'];
	$nom=$donnees['2'];
	$date_analyse=$donnees['3'];
	$date_facture=date("d/m/y");
	$montant=0;
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Facture autres soins</title>
		<?php include 'entete.php';?>
	</head>
	<body style="background-image: url('<?=$image ?>1.jpg'); font: 12pt 'times new roman';">
		<a href="" class="btn" onclick="window.print();">Imprimer</a>
		<a href="" class="btn" onclick="window.close();">Fermer</a>
		<div class="container  white" style="padding:  10px">
			<div class="row center" >
				<img class="col s12" src="../css/images/entete.jpg" style="margin-bottom: -30px" >
			</div>
			
			<h6 class="center"><b  style="font: 20pt 'times new roman';">FACTURE AUTRES SOINS N° <?= str_pad($_GET['id'], 4, "0", STR_PAD_LEFT) ?></b></h6>
			<div class="row">
				<h5 class="col s10 offset-s2 " style="border: 2px solid black; border-radius: 10px">
					Nom : <?= $nom ?> <br>
					Prénom : <?= $prenom ?> <br>
				</h5>
			</div>
			<table>
				<thead>
					<tr>
						<th><b>DESIGNATION</b></th>
						<th><b>NBRS</b></th>
						<th><b>PU</b></th>
						<th><b>TOTAL</b></th>
					</tr>
				</thead>
				<tbody>
					<?php
					$req=$db->prepare('SELECT analyse.analyse, analyse.cout,  analyse_patient.quantite, analyse_patient.montant  
					FROM `analyse_patient`, analyse
					WHERE analyse_patient.id_analyse=analyse.id AND analyse_patient.id_patient=?');
					$req->execute(array($_GET['id']));
					$nbr=$req->rowCount();
					if ($nbr>0) 
					{
						?>
						<tr>
							<td colspan="4"><b>ANALYSE(S)</b></td>
						</tr>
						<?php
						while ($donnees=$req->fetch()) 
						{
							echo "<tr>";
								echo "<td>".$donnees['0']."</td>";
								echo "<td>".str_pad($donnees['2'], 2, "0", STR_PAD_LEFT)."</td>";
								echo "<td>".number_format($donnees['1'],0,'.',' ')."</td>";
								echo "<td>".number_format($donnees['3'],0,'.',' ')."</td>";
							echo "</tr>";
							$montant= $montant + $donnees['3'];
						}
						$req->closeCursor();
					}
					$req=$db->prepare('SELECT soins_externes.soins, soins_externes.pu, soins_externes_patient.quantite, soins_externes_patient.montant
						FROM  soins_externes,`soins_externes_patient` 
						WHERE soins_externes_patient.id_soins=soins_externes.id AND soins_externes_patient.id_patient=?');
					$req->execute(array($_GET['id']));
					$nbr=$req->rowCount();
					if ($nbr>0) 
					{
						?>
						<tr>
							<td colspan="4"><b>SOIN(S) EXTERNE(S)</b></td>
						</tr>
						<?php
						while ($donnees=$req->fetch()) 
						{
							echo "<tr>";
								echo "<td>".$donnees['0']."</td>";
								echo "<td>".str_pad($donnees['2'], 2, "0", STR_PAD_LEFT)."</td>";
								echo "<td>".number_format($donnees['1'],0,'.',' ')."</td>";
								echo "<td>".number_format($donnees['3'],0,'.',' ')."</td>";
							echo "</tr>";
							$montant= $montant + $donnees['3'];
						}
					}
					
					
					echo "<tr>";
						echo "<td colspan='3'><b>TOTAL<b></td>";
						echo "<td><b>".number_format($montant,0,'.',' ')." FCFA</b></td>";
					echo "</tr>"
					?>
				</tbody>
			</table>
			<br>
			<div class="row">
				<div class="col s12" style="font: 18pt 'times new roman';">
						Arrétée à la présente somme <b><i><?=$formatter->format($montant); ?> Fcfa</i></b>
				</div>
				<br>
				<br>
				<br>
				<br>
				<br>
				<div class="col s12 center" style="font: 16pt 'comic sans ms';">
					*** Votre santé, nous lui donnons de la valeur***<br>
					--> Merci de votre confiance
				</div>	
			</div>
		</div>
		
	</body>
	<style type="text/css">
		/*import du css de materialize*/
		@import "../css/materialize.min.css" print;
		/*CSS pour la page à imprimer */
		/*Dimension de la page*/
		@page
		{
			size: portrait;
			margin: 0px;
			margin-bottom: 10px;
			margin-top: 1px;
		}
		@media print
		{
			
			.btn{
				display: none;
			}
			
			p {
				margin-top : -5px;
			}
			.row h5{
				margin-top: -5px;
			}
			
		}
		td, th{
	    padding: initial;
	    border-right: 1px solid;
	    padding :2px;
	    }
	    th
	    {
	    border: 1px solid;
	    }
	    table
	    {
	    border: 1px solid;
	    }
		td{
			text-align: center;
			border:1px solid black;
		}
		
		th{
			text-align: center;
			border:1px solid black;
		}

			p{
				

				margin-top : -5px;
			}
			
	</style>
</html>