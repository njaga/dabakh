<?php
$formatter = \NumberFormatter::create('fr_FR', \NumberFormatter::SPELLOUT);
$formatter->setAttribute(\NumberFormatter::FRACTION_DIGITS, 0);
$formatter->setAttribute(\NumberFormatter::ROUNDING_MODE, \NumberFormatter::ROUND_HALFUP);
include 'connexion.php';
$db->query("SET lc_time_names='fr_FR';");
$req = $db->prepare("SELECT CONCAT(locataire.prenom,' ',locataire.nom), CONCAT(day(location.date_debut),' ', monthname(location.date_debut),' ', year(location.date_debut)), location.caution, location.commission, location.prix_location, type_logement.type_logement, logement.designation, logement.adresse, location.type_contrat, bailleur.id
FROM `location`, locataire, logement, type_logement, bailleur
WHERE type_logement.id=logement.id_type AND location.id_logement=logement.id AND location.id_locataire=locataire.id AND bailleur.id=logement.id_bailleur AND  location.id=?");
$req->execute(array($_GET['id']));
$donnees = $req->fetch();
$locataire = $donnees['0'];
$date_debut = $donnees['1'];
$caution = $donnees['2'];
$commission = $donnees['3'];
$type_logement = $donnees['5'];
$designation = $donnees['6'];
//$prix_location=$donnees['4'];
$adresse = $donnees['7'];
//$depot_garantie = $donnees['8'];
$type_contrat = $donnees['8'];
$id_bailleur = $donnees['9'];
if ($type_contrat == "habitation") {
	if ($id_bailleur == 2) {

		$lien = "i_contrat_location1.php?id=" . $_GET['id'];
	} elseif ($id_bailleur == 3) {

		$lien = "i_contrat_location2.php?id=" . $_GET['id'];
	} else {

		$lien = "i_contrat_location.php?id=" . $_GET['id'];
	}
} else {
	$lien = "i_contrat_location_prof.php?id=" . $_GET['id'];
}
if (isset($_GET['m'])) {
	$mensualite = $_GET['m'];
} else {
	$req = $db->prepare("SELECT mensualite.montant 
		FROM `location`, mensualite 
		WHERE location.id=mensualite.id_location AND location.id=? AND mensualite.date_versement=location.date_debut");
	$req->execute(array($_GET['id']));
	$donnees = $req->fetch();
	$mensualite = $donnees['0'];
}
$total = $commission + $mensualite + $caution;
?>
<!DOCTYPE html>
<html>

<head>
	<title>Nouveau contrat de location</title>
	<?php include 'entete.php'; ?>
</head>

<body style="background-image: url('<?= $image ?>bgaccueil.jpg'); font: 12pt 'times new roman';">
	<a href="" class="btn " onclick="window.print();">Imprimer</a>
	<a href="immobilier.php" class="btn ">Retour</a>
	<a href="<?= $lien ?>" class="btn">Contrat de location</a>
	<div class="container  white" style="padding:  10px">
		<div class="row center">
			<img class="col s12" src="css/images/banniere_immo1.png">
			<p class="col s12 right-align">Imprimé le <?= date('d') . "/" . date('m') . "/" . date('Y') ?></p>
			<h3 class="row center">
				<b>Facture N°<?= str_pad($_GET['id'], 3, "0", STR_PAD_LEFT) ?></b>
			</h3>
		</div>
		<div class="row">
			<h5 class="col s8">
				Locataire :<?= $locataire ?>
			</h5>
			<h6 class="col s8">Date du paiement : <b><?= $date_debut ?></b></h6>
		</div>
		<div class="row">
			<table class="col s12 highlight centered">

				<tbody>
					<tr>
						<th>Caution</th>
						<td><b><?= number_format($caution, 0, '.', ' ') ?> Fcfa</b> </td>
					</tr>
					<tr>
						<th>Commission</th>
						<td><b><?= number_format($commission, 0, '.', ' ') ?> Fcfa</b> </td>
					</tr>
					<tr>
						<th>Mensualité</th>
						<td><b><?= number_format($mensualite, 0, '.', ' ') ?> Fcfa</b> </td>
					</tr>


					<tr class="grey">
						<th><b>TOTAL</b></th>
						<td><b><?= number_format($total, 0, '.', ' ') ?> Fcfa</b> </td>
					</tr>
				</tbody>
			</table>
		</div>
		<div class="row">
			<h6 class="col s12 center">
			Arrêtée la présente facture à la somme de : <b><?= number_format($total, 0, '.', ' ') ?> FCFA</b> (<b><i><?= $formatter->format($total); ?> Francs CFA</i></b>)
			</h6>
		</div>
		<div class="row">
			<p style="font-family: 'comic sans ms'"><b><u>NB</u></b> : La commission non remboursable</p>
		</div>
		<div class="row">
			<h6 class="col s6 center"><b><u>Le locataire</u></b></h6>
			<h6 class="col s6 center"><b><u>L'agence</u></b></h6>
		</div>
	</div>
</body>
<script type="text/javascript">
	$(document).ready(function() {})
</script>
<style type="text/css">
	/*import du css de materialize*/
	@import "../css/materialize.min.css"print;

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