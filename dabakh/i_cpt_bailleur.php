<?php
$formatter = \NumberFormatter::create('fr_FR', \NumberFormatter::SPELLOUT);
$formatter->setAttribute(\NumberFormatter::FRACTION_DIGITS, 0);
$formatter->setAttribute(\NumberFormatter::ROUNDING_MODE, \NumberFormatter::ROUND_HALFUP);
include 'connexion.php';
$date_debut=$_GET['date_debut'];
$date_fin=$_GET['date_fin'];
$search=$_GET['id'];
$somme_mens_locataire=0;
$somme_mens_depense=0;
$somme_mens_bailleur=0;
$somme_commission=0;

$req_mens_locataire=$db->prepare('SELECT mensualite.mois, CONCAT(DATE_FORMAT(date_versement, "%d"),"/", DATE_FORMAT(date_versement, "%m"),"/", DATE_FORMAT(date_versement, "%Y")), montant, CONCAT(locataire.prenom," ",locataire.nom), type_logement.type_logement
FROM `mensualite`, location,logement, bailleur, type_logement, locataire
WHERE mensualite.id_location=location.id AND location.id_logement=logement.id AND logement.id_bailleur=bailleur.id
AND logement.id_type=type_logement.id AND locataire.id=location.id_locataire AND mensualite.date_versement BETWEEN ? AND ? AND bailleur.id=? ORDER BY date_versement');
$req_mens_locataire->execute(array($date_debut, $date_fin, $search));
$req_mens_depense=$db->prepare('SELECT depense_bailleur.motif, CONCAT(DATE_FORMAT(depense_bailleur.date_depense, "%d"),"/", DATE_FORMAT(depense_bailleur.date_depense, "%m"),"/", DATE_FORMAT(depense_bailleur.date_depense, "%Y")), depense_bailleur.montant, depense_bailleur.mois
FROM depense_bailleur
WHERE depense_bailleur.date_depense BETWEEN ? AND ? AND depense_bailleur.id_bailleur=?   ORDER BY date_depense');
$req_mens_depense->execute(array($date_debut, $date_fin, $search));
$req_mens_bailleur=$db->prepare("SELECT CONCAT(DATE_FORMAT(date_versement, '%d'),'/', DATE_FORMAT(date_versement, '%m'),'/', DATE_FORMAT(date_versement, '%Y')), montant, mois, commission
FROM `mensualite_bailleur`WHERE date_versement BETWEEN ? AND ? AND id_bailleur=? ORDER BY date_versement");
$req_mens_bailleur->execute(array($date_debut, $date_fin, $search));
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Impression compte du bailleur</title>
		<?php include 'entete.php';?>
		<link type="text/css" rel="stylesheet" href="../css/tables-min.css"  media="screen,projection"/>
	</head>
	<body style="font: 12pt 'times new roman';" >
		<a href="" class="btn "  onclick="window.print();">Imprimer</a>
		<a href="immobilier.php" class="btn " >Retour au menu</a>
		<div class="container  white" style="padding:  10px">
			<div class="row">
				<img class="col s8 offset-s2" src="../css/images/banniere_immo.png" >
				<p class="col s12 right-align" style="font-family: 'times new roman'; font-size: 8px">Utilisateur</p>
			</div>
			<div class="row center" >
				<h3 class="col s12 center" style="margin-bottom: -8px; margin-top: -20px">
				<b>Reçu N°<?=str_pad($_GET['id'], 3,"0", STR_PAD_LEFT) ?></b>
				</h3>
				<p class="col s12 right-align">Imprimé le <?= date('d')."/".date('m')."/".date('Y') ?></p>
			</div>
			<div class="row">
				
				<table class="col s6">
					<thead>
						<tr>
							<th colspan="3" class="center"><h4>Débit</h4></th>
						</tr>
						<tr>
							<th>Date</th>
							<th> Libellé</th>
							<th> Montant</th>
						</tr>
					</thead>
					<tbody>
						<?php
						
						
						while ($donnees=$req_mens_locataire->fetch())
						{
						$mois=$donnees['0'];
						$date_versement=$donnees['1'];
						$montant=$donnees['2'];
						$locataire=$donnees['3'];
						$logement=$donnees['4'];
						echo"<tr>";
							echo "<td>".$date_versement."</td>";
							echo "<td>Loyer <b>".$locataire."</b> de <b>".$mois."</b> pour : <b>".$logement."</b></td>";
							echo "<td>".number_format($montant,0,'.',' ')." Fcfa</td>";
						echo"</tr>";
						$somme_mens_locataire=$somme_mens_locataire+$montant;
						}
						$req_mens_locataire->closeCursor();
						
						
						$req_mens_locataire->closeCursor();
						echo "<tr class='grey white-text'>";
							echo "<td colspan='2'>Total</td>";
							echo "<td >".number_format($somme_mens_locataire,0,'.',' ')." Fcfa</td>";
						echo "</tr>";
						?>
					</tbody>
				</table>
				<table class="col s6 ">
					<thead>
						<tr>
							<th colspan="3" class="center"><h4>Crédit</h4></th>
						</tr>
						<tr>
							<th>Date</th>
							<th> Libellé</th>
							<th> Montant</th>
						</tr>
					</thead>
					<tbody>
						<?php
						while ($donnees=$req_mens_depense->fetch())
						{
						$motif=$donnees['0'];
						$date_depense=$donnees['1'];
						$montant=$donnees['2'];
						echo"<tr>";
							echo "<td>".$date_depense."</td>";
							echo "<td>".$motif."</td>";
							echo "<td>".number_format($montant,0,'.',' ')." Fcfa</td>";
						echo"</tr>";
						$somme_mens_depense=$somme_mens_depense+$montant;
						}
						while ($donnees=$req_mens_bailleur->fetch())
						{
						$date_versement=$donnees['0'];
						$montant=$donnees['1'];
						$mois=$donnees['2'];
						$commission=$donnees['3'];
						echo"<tr>";
							echo "<td>".$date_versement."</td>";
							echo "<td>Payement du mois de ".$mois."</td>";
							echo "<td>".number_format($montant,0,'.',' ')." Fcfa</td>";
						echo"</tr>";
						echo"<tr>";
							echo "<td>".$date_versement."</td>";
							echo "<td>Commission gérence du mois de : ".$mois."</td>";
							echo "<td>".number_format($commission,0,'.',' ')." Fcfa</td>";
						echo"</tr>";
						$somme_mens_bailleur=$somme_mens_bailleur+$montant;
						$somme_commission=$somme_commission+$commission;
						}
						$req_mens_bailleur->closeCursor();
						
						echo "<tr class='grey white-text'>";
							echo "<td style='border:1px solid black;' colspan='2'>Total</td>";
							echo "<td style='border:1px solid black;' >".number_format(($somme_mens_bailleur+$somme_mens_depense+$somme_commission),0,'.',' ')." Fcfa</td>";
						echo "</tr>";
						$solde=$somme_mens_locataire-($somme_mens_bailleur+$somme_mens_depense+$somme_commission);
						?>
					</tbody>
				</table>
			</div>
			<h4 class="col s12 center">
			Le solde du bailleur est de <?= number_format($solde,0,'.',' ')?> Fcfa
			</h4>
		</body>
		<script type="text/javascript">
			$(document).ready(function () {
			})
		</script>
		<style type="text/css">
			/*import du css de materialize*/
			@import "../css/tables-min.css" print;
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
			td{
				text-align: center;
				border:1px solid black;
				padding: inherit;
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