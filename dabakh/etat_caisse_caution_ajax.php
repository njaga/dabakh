<?php
session_start();
include 'connexion.php';
$mois = $_POST['mois'];
$annee = $_POST['annee'];
$db->query("SET lc_time_names = 'fr_FR';");
$som_entree = 0;
$som_sortie = 0;
$som_solde = 0;

if ($mois == 1) {
	//Solde su mois précédent
	$date_dernier_jour = $annee . '-' . $mois . '-01';
	$reponse = $db->prepare("SELECT (SELECT COALESCE(SUM(montant),0) FROM caisse_caution WHERE type='entree' AND date_operation<?)  - (SELECT COALESCE(SUM(montant),0) FROM caisse_caution WHERE type='sortie' AND  date_operation<?)");
	$reponse->execute(array($date_dernier_jour, $date_dernier_jour));
	$donnees = $reponse->fetch();
	$solde = $donnees['0'];
	$solde_init = $donnees['0'];
	$mois_precedent = "Décembre " . ($annee - 1);
} else {
	//Solde su mois précédent
	$date_dernier_jour = $annee . '-' . $mois . '-01';
	$reponse = $db->prepare("SELECT (SELECT COALESCE(SUM(montant),0) FROM caisse_caution WHERE type='entree' AND date_operation<?)  - (SELECT COALESCE(SUM(montant),0) FROM caisse_caution WHERE type='sortie' AND  date_operation<?)");
	$reponse->execute(array($date_dernier_jour, $date_dernier_jour));
	$donnees = $reponse->fetch();
	$solde = $donnees['0'];
	$solde_init = $donnees['0'];
	//mois précédent
	$list_mois = array("", "Janvier", "Février", "Mars", "Avril", "Mai", "Juin", "Juillet", "Août", "Septembre", "Octobre", "Novembre", "Décembre");
	$mois_precedent = $list_mois[($mois - 1)];
}

echo "<tr>";
echo "<td colspan='2' class='center'><b>Solde du mois de " . ucfirst($mois_precedent) . "</b></td>";
echo "<td></td>";
echo "<td></td>";
echo "<td class=''>" . number_format($solde, 0, '.', ' ') . "</td>";
echo "</tr>";

$reponse = $db->prepare("SELECT id, CONCAT(DATE_FORMAT(date_operation, '%d'), '/', DATE_FORMAT(date_operation, '%m'),'/', DATE_FORMAT(date_operation, '%Y')), motif, type, montant, id_location,section, id_user
FROM `caisse_caution`
WHERE month(date_operation)=? AND year(date_operation)=? AND type<>'solde' ORDER BY date_operation ASC");
$reponse->execute(array($mois, $annee));
$nbr = $reponse->rowCount();
if ($nbr > 0) {
	while ($donnees = $reponse->fetch()) {
		$id = $donnees['0'];
		$date_operation = $donnees['1'];
		$motif = $donnees['2'];
		$type = $donnees['3'];
		$montant = $donnees['4'];
		$id_location = $donnees['5'];
		$section = $donnees['6'];
		$id_user = $donnees['7'];
		if ($type == 'entree') {
			echo "<tr class='brown lighten-4'>";
		} elseif ($type == 'sortie') {
			echo "<tr class='deep-orange lighten-4'>";
		} else {
			echo "<tr>";
		}
		if ($_SESSION['fonction'] == 'administrateur') {
			echo "<td> <a class='tooltipped' data-position='top' data-delay='50' data-tooltip='cliquez ici pour modifier' href='m_caisse_caution.php?id=$id'> " . $date_operation . "</a> </td>";
		} else {
			echo "<td>" . $date_operation . "</td>";
		}
		if ($type == 'sortie') {
			echo "<td><a class='tooltipped' data-position='top' data-delay='50' data-tooltip='cliquez ici pour modifier' href='i_caution.php?id=$id'> " . $motif . "</a> </td>";
		} else {
			echo "<td>" . $motif . "</td>";
		}
		if ($type == "entree") {
			$solde = $solde + $montant;
			$som_entree = $som_entree + $montant;
			echo "<td>" . number_format($montant, 0, '.', ' ') . "</td>";
			echo "<td></td>";
		} elseif ($type == 'sortie') {
			$solde = $solde - $montant;
			$som_sortie = $som_sortie + $montant;
			echo "<td></td>";
			echo "<td>" . number_format($montant, 0, '.', ' ') . "</td>";
		} else {
			$solde = $solde + $montant;
			echo "<td></td>";
			echo "<td></td>";
		}
		echo "<td>" . number_format($solde, 0, '.', ' ') . " </td>";
		if ($_SESSION['fonction'] == 'administrateur') {

			echo "<td><a class='red btn' href='supprimer_ligne_caisse.php?id_caisse_caution=" . $id . "' onclick='return(confirm(\"Voulez-vous supprimer cette opération ?\"))'>Supprimer </a>";
			echo "<br>" . $id_user . "</td>";
		} else {
			echo "<td></td>";
		}
	}
	$reponse->closeCursor();
	//Total et solde
	echo "</tr>";

	echo "<tr class='grey darken-3 white-text'>";
	echo "<td colspan='2'><b>TOTAL</b></td>";
	echo "<td><b>" . number_format($som_entree, 0, '.', ' ') . "</b></td>";
	echo "<td><b>" . number_format($som_sortie, 0, '.', ' ') . "</b></td>";
	echo "<td><b>" . number_format(($solde_init + $som_entree - $som_sortie), 0, '.', ' ') . "</b></td>";
	echo "</tr>";
} else {
	echo "<tr><td></td><td></td><td></td><td><h3>Aucune opération ce mois ci </td></tr>";
}
