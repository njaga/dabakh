<?php
$formatter = \NumberFormatter::create('fr_FR', \NumberFormatter::SPELLOUT);
$formatter->setAttribute(\NumberFormatter::FRACTION_DIGITS, 0);
$formatter->setAttribute(\NumberFormatter::ROUNDING_MODE, \NumberFormatter::ROUND_HALFUP);
include 'connexion.php';
	$db->query("SET lc_time_names='fr_FR';");
	$req=$db->prepare("SELECT patient.prenom, patient.nom, CONCAT(day(consultation.date_consultation),' ', monthname(consultation.date_consultation),' ', year(consultation.date_consultation)) , consultation.montant, service.service, service.pu, patient.profession, CONCAT(day(patient.date_naissance),' ', monthname(patient.date_naissance),' ', year(patient.date_naissance)), patient.num_dossier  
FROM `consultation`, patient, service  
WHERE consultation.id_patient=patient.id_patient AND service.id=consultation.id_service AND consultation.id_consultation=?");
	$req->execute(array($_GET['id']));
	$donnees=$req->fetch();
	$prenom=$donnees['0'];
	$nom=$donnees['1'];
	$date_consultation=$donnees['2'];
	$montant=$donnees['3'];
	$service=$donnees['4'];
	$pu_service=$donnees['5'];
	$profession=$donnees['6'];
	$date_naissance=$donnees['7'];
	$num_dossier=$donnees['8'];
	$date_facture=date("d/m/y");

?>
<!DOCTYPE html>
<html>
	<head>
		<title>Facture</title>
		<?php include 'entete.php';?>
	</head>
	<body style="background-image: url('<?=$image ?>i_facture_cons.jpg'); font: 12pt 'times new roman';">
		<a href="" class="btn" onclick="window.print();">Imprimer</a>
		<a href="" class="btn" onclick="window.close();">Fermer</a>
		<div class="container  white" style="padding:  10px">
			<div class="row center"  >
				<img class="col s12" style="margin-bottom: -30px" src="../css/images/entete.jpg" >
			</div>			
			<h6 class="center"><b  style="font: 22pt 'times new roman';">FACTURE N° <?= str_pad($_GET['id'], 4, "0", STR_PAD_LEFT) ?></b></h6>
			<div class="row">
				<h6 class="col s7 offset-s2 " style="border: 2px solid black; border-radius: 10px">
					Nom : <?= $nom ?> <br>
					Prénom : <?= $prenom ?> <br>
					Date de naissance : <?= $date_naissance ?> <br>
				</h6>
				<p class="right-align">Imprimer le <?=date('d')."/".date('m')."/".date('y') ?></p>
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
					$req=$db->prepare('SELECT service.service, service.pu 
						FROM `consultation`, service 
						WHERE consultation.id_service=service.id AND consultation.id_consultation=?');
					$req->execute(array($_GET['id']));
					$donnees=$req->fetch();
					echo "<tr>";
						echo "<td>".$donnees['0']."</td>";
						echo "<td>01</td>";
						echo "<td>".number_format($donnees['1'],0,'.',' ')."</td>";
						echo "<td>".number_format($donnees['1'],0,'.',' ')."</td>";
					echo "</tr>";
					
					$req=$db->prepare('SELECT produit.produit, produit.pu, vente_produit.quantite, vente_produit.montant  
						FROM  vente_produit, produit 
						WHERE vente_produit.id_produit=produit.id AND vente_produit.id_consultation=?');
					$req->execute(array($_GET['id']));
					while ($donnees=$req->fetch()) {
						echo "<tr>";
							echo "<td>".$donnees['0']."</td>";
							echo "<td>".str_pad($donnees['2'], 2, "0", STR_PAD_LEFT)."</td>";
							echo "<td>".number_format($donnees['1'],0,'.',' ')."</td>";
							echo "<td>".number_format($donnees['3'],0,'.',' ')."</td>";
					echo "</tr>";
					}
					$req=$db->prepare("SELECT hospitalisation.designation, hospitalisation.cout, consultation_hospitalisation.quantite, consultation_hospitalisation.montant, hospitalisation.id
					FROM `consultation_hospitalisation`, hospitalisation 
					WHERE consultation_hospitalisation.id_hospitalisation=hospitalisation.id AND consultation_hospitalisation.id_consultation=?");
				$req->execute(array($_GET['id']));
				while ($donnees=$req->fetch()) {
						echo "<tr>";
							echo "<td>".$donnees['0']."</td>";
							echo "<td>".str_pad($donnees['2'], 2, "0", STR_PAD_LEFT)."</td>";
							echo "<td>".number_format($donnees['1'],0,'.',' ')."</td>";
							echo "<td>".number_format($donnees['3'],0,'.',' ')."</td>";
					echo "</tr>";
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
						Arrété à la présente somme <b><i><?=$formatter->format($montant); ?> Fcfa</i></b>
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