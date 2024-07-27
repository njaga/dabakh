<?php
$formatter = \NumberFormatter::create('fr_FR', \NumberFormatter::SPELLOUT);
$formatter->setAttribute(\NumberFormatter::FRACTION_DIGITS, 0);
$formatter->setAttribute(\NumberFormatter::ROUNDING_MODE, \NumberFormatter::ROUND_HALFUP);
include 'connexion.php';
$db->query("SET lc_time_names='fr_FR';");
$req = $db->prepare("SELECT CONCAT(bailleur.prenom,' ', bailleur.nom),mensualite_bailleur.montant, CONCAT(day(mensualite_bailleur.date_versement),' ', monthname(mensualite_bailleur.date_versement),' ', year(mensualite_bailleur.date_versement)), mensualite_bailleur.mois, bailleur.id, mensualite_bailleur.mois, mensualite_bailleur.annee, mensualite_bailleur.non_recouvrer, bailleur.pourcentage, mensualite_bailleur.commission, mensualite_bailleur.id_user
	FROM bailleur, mensualite_bailleur 
	WHERE mensualite_bailleur.id_bailleur=bailleur.id AND mensualite_bailleur.id=?");
$req->execute(array($_GET['id']));
$donnees = $req->fetch();
$bailleur = $donnees['0'];
$montant_verse = $donnees['1'];
$date_versement = $donnees['2'];
$mois = $donnees['3'];
$id_bailleur = $donnees['4'];
$mois = $donnees['5'];
$annee = $donnees['6'];
$non_recouvrer = $donnees['7'];
$pourcentage = $donnees['8'];
$commission = $donnees['9'];
$id_user = $donnees['10'];
?>
<!DOCTYPE html>
<html>

<head>
	<title>Mensualité bailleur du mois de<?= $mois ?></title>
	<?php include 'entete.php'; ?>
	<link type="text/css" rel="stylesheet" href="../css/tables-min.css" media="screen,projection" />
</head>

<body style="background-image: url('<?= $image ?>i_recu_bailleur.jpg'); font: 12pt 'times new roman';">
	<a href="" class="btn " onclick="window.print();">Imprimer</a>
	<a href="immobilier.php" class="btn ">Retour au menu</a>
	<div class="container  white" style="padding:  10px">
		<div class="row">
			<img class="col s8 offset-s2" src="css/images/banniere_immo.png">
		</div>
		<div class="row center">
			<h4 class="col s12 center" style="margin-bottom: -8px; margin-top: -20px">
				<b>Reçu Bailleur N°<?= str_pad($_GET['id'], 3, "0", STR_PAD_LEFT) ?></b>
			</h4>
			<p class="col s12 right-align">Imprimé le <?= date('d') . "/" . date('m') . "/" . date('Y') ?></p>
		</div>
		<div class="row">
			<h5 class="col s12" style="margin-bottom: -6px">
				Bénéficiare :<b> <?= $bailleur ?></b>
			</h5>
			<h6 class="col s3">
				Mois : <b><?= $mois ?></b>
				<br>
				Année : <b><?= $annee ?></b>
			</h6>
			<h6 class="col s3"></h6>
			<h6 class="col s6">Date du paiement : <b><?= $date_versement ?></b></h6>
		</div>
		<div class="row">
			<table class="col s12 pure-table pure-table-bordered">
				<thead>
					<th>Logement</th>
					<th></th>
					<th>&nbsp&nbsp&nbspPU&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp</th>
					<th>Montant</th>
				</thead>
				<tbody>

					<?php
					$db->query("SET lc_time_names = 'fr_FR';");
					$req = $db->prepare("SELECT CONCAT(CONCAT(locataire.prenom,' ', locataire.nom),' : ', logement.designation), location.prix_location,  mensualite.type, mensualite.montant, mensualite.mois, mensualite.annee
							FROM mensualite, logement, location, type_logement, locataire
							WHERE logement.id_type=type_logement.id AND mensualite.id_location=location.id AND location.id_locataire=locataire.id AND location.id_logement=logement.id AND mensualite.id_mensualite_bailleur=?");
					$req->execute(array($_GET['id']));
					$sous_total = 0;
					$sous_total_depense = 0;
					$i = 0;
					while ($donnees = $req->fetch()) {
						$i++;
						$designation = $donnees['0'];
						$pu = $donnees['1'];
						$type = $donnees['2'];
						$montant = $donnees['3'];
						$mois_locataire = $donnees['4'];
						$annee_locataire = $donnees['5'];
						$sous_total = $sous_total + $montant;
						echo "<tr>";
						echo "<td  class='designation'><b>" . $i . ") </b> <b>" . $designation . " pour " . $mois_locataire . " " . $annee_locataire . "</b></td>";
						echo "<td>" . $type . "</td>";
						echo "<td>" . number_format($pu, 0, '.', ' ') . " </td>";
						echo "<td>" . number_format($montant, 0, '.', ' ') . " </td>";
						echo "</tr>";
					}
					$total_depense = 0;
					$req = $db->prepare("SELECT SUM(depense_bailleur.montant)  
						FROM `depense_bailleur`
						WHERE  depense_bailleur.id_mensualite_bailleur=?");
					$req->execute(array($_GET['id']));
					$donnees = $req->fetch();
					$total_depense = $donnees['0'];

					echo "<tr class=''>";
					echo "<td colspan='2'><b>SOUS TOTAL LOCATIONS</td></td>";
					echo "<td colspan='2'>" . number_format(($sous_total), 0, '.', ' ') . " </td>";
					echo "</tr>";
					echo "<tr class=''>";
					echo "<td colspan='2'>Commission gérence " . $pourcentage . "%</td>";
					echo "<td colspan='2'>" . number_format($commission, 0, '.', ' ') . " </td>";
					echo "</tr>";

					//Dépenses
					echo "<tr>";
					echo "<td colspan='4'><b>DEPENSES</b></td>";
					echo "</tr>";
					$total_depense = 0;
					$req = $db->prepare("SELECT depense_bailleur.motif, depense_bailleur.montant 
						FROM `depense_bailleur`
						WHERE  depense_bailleur.id_mensualite_bailleur=?");
					$req->execute(array($_GET['id']));
					while ($donnees = $req->fetch()) {
						echo "<tr>";
						echo "<td colspan='3'>" . $donnees['0'] . "</td>";
						echo "<td>" . number_format($donnees['1'], 0, '.', ' ') . " </td>";
						echo "</tr>";
						$sous_total_depense = $sous_total_depense + $donnees['1'];
					}
					echo "<tr class=''>";
					echo "<td colspan='2'><b>SOUS TOTAL DEPENSES</td></td>";
					echo "<td colspan='2'>" . number_format(($sous_total_depense), 0, '.', ' ') . " </td>";
					echo "</tr>";

					echo "<tr class='grey '>";
					echo "<td colspan='2'><b>TOTAL</b></td>";
					echo "<td colspan='2'><b>" . number_format(($montant_verse), 0, '.', ' ') . " </b></td>";
					echo "</tr>";
					?>
				</tbody>
			</table>
		</div>
		<div class="row">
			<h6 class="col s12">
				Arrêté à la présente somme de <b><?= number_format($montant_verse, 0, '.', ' ') ?> </b> Fcfa.....<b><i><?= $formatter->format($montant_verse); ?> </i></b>....
			</h6>
			<h6 class="col s12">
				<b>
					<?php
					if ($non_recouvrer != "") {
						echo nl2br($non_recouvrer);
					}
					?>
				</b>
			</h6>
		</div>
		<div class="row">
			<h6 class="col s6 center"><b><u>Le bénéficiare</u></b></h6>
			<h6 class="col s6 center"><b><u>L'agence</u></b></h6>
		</div>
	</div>
</body>
<script type="text/javascript">
	$(document).ready(function() {})
</script>
<style type="text/css">
	/*import du css de materialize*/
	@import "../css/tables-min.css" print;
	@import "../css/materialize.min.css" print;

	/*CSS pour la page à imprimer */
	/*Dimension de la page*/
	@page {
		size: portrait;
		margin: 0px;
		margin-bottom: 10px;
		margin-top: 1px;
	}

	@media print {

		.btn {
			display: none;
		}

		p {
			margin-top: -5px;
		}

		.row h5 {
			margin-top: -5px;
		}

		td,
		th {
			padding: initial;
			border-right: 1px solid;
			padding: 2px;
		}

		th {
			border: 1px solid;
		}

		table {
			border: 1px solid;
		}

	}

	.designation {
		text-align: left;
	}

	td {
		text-align: center;
		border: 1px solid black;
	}

	th {
		text-align: center;
		border: 1px solid black;
	}

	p {


		margin-top: -5px;
	}
</style>

</html>