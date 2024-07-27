<?php
session_start();
include 'connexion.php';
$annee = $_POST['annee'];
$mois = $_POST['mois'];
$id = $_POST['id'];
$mois_a_payer = $_POST['mois_a_payer'];
$total = 0;
$non_recouvrer = "";
$non_reverser = "";
$deja_reverser = "";
$depense_bailleur = "";
$montant_depense = 0;
$montant_net_payer = 0;
//recupération du pourcentage
$req = $db->prepare("SELECT  bailleur.pourcentage FROM bailleur WHERE bailleur.id=?");
$req->execute(array($id));
$donnees = $req->fetch();
$pourcentage = $donnees['0'];
echo '<table class="col s11 offset-s1 highlight centered">
		<thead>
			<th>Logement</th>
			<th>Mois</th>
			<th></th>
			<th>PU</th>
			<th>Montant</th>
		</thead>
		<tbody class="tbody">
						';
$db->query("SET lc_time_names = 'fr_FR';");

// recupération des mensualités payer mais pas reverser au bailleur
$req = $db->prepare("SELECT CONCAT(CONCAT(locataire.prenom,' ', locataire.nom),' : ', logement.designation, ' - ',type_logement.type_logement), location.prix_location,  mensualite.type, mensualite.montant, mensualite.id_mensualite_bailleur, mensualite.mois, mensualite.annee
	FROM mensualite, logement, bailleur, location, type_logement, locataire
	WHERE logement.id_type=type_logement.id AND mensualite.id_location=location.id AND location.id_locataire=locataire.id AND location.id_logement=logement.id AND logement.id_bailleur=bailleur.id AND mensualite.mois=? AND mensualite.annee=? AND bailleur.id=? ORDER BY mensualite.date_versement");
$req->execute(array($mois, $annee, $id));
while ($donnees = $req->fetch()) {

	$logement = $donnees['0'];
	$pu = $donnees['1'];
	$type = $donnees['2'];
	$montant = $donnees['3'];
	$id_mens_bailleur = $donnees['4'];
	$mois = $donnees['5'];
	$annee = $donnees['6'];

	if ($id_mens_bailleur == 0) {
		echo "<tr>";
		echo "<td>" . $logement . "</td>";
		echo "<td>" . $mois . " " . $annee . "</td>";
		echo "<td>" . $type . "</td>";
		echo "<td>" . number_format($pu, 0, '.', ' ') . " </td>";
		echo "<td>" . number_format(($montant), 0, '.', ' ') . " </td>";
		$total = $total + $montant;
		echo "</tr>";
	} else {
		$deja_reverser = $deja_reverser . "\n" . $logement;
	}
}

//mensualité des mois passés non reverséés au bailleur
if ($mois_a_payer == "tous") {
	echo "<tr><td colspan='5' class='grey white-text'>Mensualités passée non reversé au bailleur</td></tr>";
	$req = $db->prepare("SELECT CONCAT(CONCAT(locataire.prenom,' ', locataire.nom),' : ', type_logement.type_logement), logement.pu,  mensualite.type, mensualite.montant, mensualite.id_mensualite_bailleur, mensualite.mois, mensualite.annee, mensualite.id
		FROM mensualite, logement, bailleur, location, type_logement, locataire
		WHERE logement.id_type=type_logement.id AND mensualite.id_location=location.id AND location.id_locataire=locataire.id AND location.id_logement=logement.id AND logement.id_bailleur=bailleur.id AND mensualite.mois<>? AND bailleur.id=? AND mensualite.id_mensualite_bailleur=0
	    ORDER BY mensualite.date_versement");
	$req->execute(array($mois, $id));
	while ($donnees = $req->fetch()) {

		$locataire = $donnees['0'];
		$pu = $donnees['1'];
		$type = $donnees['2'];
		$montant = $donnees['3'];
		$id_mens_bailleur = $donnees['4'];
		$m = $donnees['5'];
		$a = $donnees['6'];
		$id_mensualite = $donnees['7'];
		//$id=$donnees['7'];

		echo "<tr>";
		echo "<td>" . $locataire . "</td>";
		echo "<td>" . $m . " " . $a . "</td>";
		echo "<td>" . $type . "</td>";
		echo "<td>" . number_format($pu, 0, '.', ' ') . " </td>";
		echo "<td>" . number_format(($montant), 0, '.', ' ') . " </td>";
		if ($_SESSION['fonction'] == 'administrateur') {
			echo "<td><a class='red btn' href='s_mensualite_ancien.php?id=" . $id_mensualite . "' onclick='return(confirm(\"Voulez-vous supprimer cette mensualité ?\"))'>Supprimer </a></td>";
		}
		$total = $total + $montant;
		echo "</tr>";
	}
}
echo "<tr>";
echo "<td colspan='3'>Sous Total </td>";
echo "<td colspan='2'>" . number_format(($total), 0, '.', ' ') . "</td>";
echo "</tr>";
//recupéreration des logements non recouvrer 
$req = $db->prepare("SELECT CONCAT(CONCAT(locataire.prenom,' ', locataire.nom),' : ', type_logement.type_logement)
FROM logement, bailleur, location, type_logement, locataire
WHERE logement.id_type=type_logement.id AND  location.id_locataire=locataire.id AND location.id_logement=logement.id AND logement.id_bailleur= bailleur.id AND bailleur.id=? AND location.etat='active' AND location.id NOT IN
(SELECT location.id
FROM mensualite, logement, bailleur, location, type_logement
	WHERE logement.id_type=type_logement.id AND mensualite.id_location=location.id AND location.id_logement=logement.id AND logement.id_bailleur=bailleur.id AND mensualite.mois=? AND mensualite.annee=? AND bailleur.id=?)");
$req->execute(array($id, $mois, $annee, $id));
while ($donnees = $req->fetch()) {

	$non_recouvrer = $non_recouvrer . "\n" . $donnees['0'];
}
$commission = ($total * $pourcentage) / 100;
$total = $total - $commission;
echo "<tr class=''>";
echo "<td colspan='3'>Commission gérence " . $pourcentage . "%</td>";
echo "<td colspan='2'>" . number_format($commission, 0, '.', ' ') . " </td>";
echo "</tr>";
echo "<tr class='grey white-text'>";
echo "<td colspan='3'>TOTAL</td>";
echo "<td colspan='2'>" . number_format($total, 0, '.', ' ') . " </td>";
echo "</tr>";

echo "</tbody></table>";
//recupération des dépenses
if ($mois_a_payer == "tous") {
	$req = $db->prepare("SELECT depense_bailleur.motif, depense_bailleur.montant, depense_bailleur.mois, depense_bailleur.annee 
	FROM `depense_bailleur`
	WHERE  depense_bailleur.id_bailleur=? AND depense_bailleur.id_mensualite_bailleur=0");
	$req->execute(array($id));
} else {
	$req = $db->prepare("SELECT depense_bailleur.motif, depense_bailleur.montant, depense_bailleur.mois, depense_bailleur.annee 
	FROM `depense_bailleur`
	WHERE depense_bailleur.mois=? AND depense_bailleur.annee=? AND depense_bailleur.id_bailleur=? AND depense_bailleur.id_mensualite_bailleur=0");
	$req->execute(array($mois, $annee, $id));
}
while ($donnees = $req->fetch()) {
	$depense_bailleur = $depense_bailleur . "\n<b>" . $donnees['2'] . "</b> : " . ucfirst(strtolower($donnees['0'])) . " => <b>" . number_format($donnees['1'], 0, '.', ' ') . " </b>";
	$montant_depense = $montant_depense + $donnees['1'];
}
$depense_bailleur = $depense_bailleur . "\n \n  <b>TOTAL dépenses : " . number_format($montant_depense, 0, '.', ' ') . "  </b>";
$montant_net_payer = $total - $montant_depense;
?>
<div class="row">
	<p class="col m6 s12"><b><u>NON RECOUVRER</u></b><?= nl2br($non_recouvrer) ?></p>
	<p class="col m6 s12"><b><u>Déjà reverser</u></b><?= nl2br($deja_reverser) ?></p>
</div>
<div class="row">
	<h6 class="col m12 s12"><b><u>Dépenses Bailleur</u></b><?= nl2br($depense_bailleur) ?></h6>
</div>
<div class="row">
	<h3>
		Net à payer : <?= number_format($montant_net_payer, 0, '.', ' ') . " " ?>
	</h3>
</div>
<textarea id="non_recouvrer" hidden name="non_recouvrer" class="materialize-textarea"><?= $non_recouvrer ?></textarea>
<input type="number" class="montant" hidden value="<?= $montant_net_payer ?>" name="montant" id="montant" required>
<input type="number" class="commission" hidden value="<?= $commission ?>" name="commission" id="commission" required>