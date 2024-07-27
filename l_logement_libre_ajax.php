<?php
session_start();
include 'connexion.php';
$total = 0;
if ($_POST['search'] == "") {
	$db->query("SET lc_time_names = 'fr_FR';");
	$req = $db->query("SELECT logement.id, CONCAT(bailleur.prenom,' ', bailleur.nom), logement.designation, type_logement.type_logement, logement.adresse, logement.pu, logement.nbr, bailleur.id
	FROM `logement`, bailleur, type_logement  
	WHERE logement.etat='actif' AND logement.id_type=type_logement.id AND logement.id_bailleur=bailleur.id AND logement.nbr>0 AND bailleur.etat='activer' ORDER BY nom, prenom");
} else {
	$req = $db->prepare("SELECT logement.id, CONCAT(bailleur.prenom,' ', bailleur.nom), logement.designation, type_logement.type_logement, logement.adresse, logement.pu, logement.nbr, bailleur.id
	FROM `logement`, bailleur, type_logement  
	WHERE logement.etat='actif' AND logement.id_type=type_logement.id AND logement.id_bailleur=bailleur.id AND logement.nbr>0 AND bailleur.etat='activer' AND CONCAT (prenom,' ',nom) like CONCAT('%', ?, '%') ORDER BY nom, prenom");
	$req->execute(array($_POST['search']));
}
$resultat = $req->rowCount();
while ($donnees = $req->fetch()) {
	$id = $donnees['0'];
	$bailleur = $donnees['1'];
	$logement = $donnees['2'];
	$type_logement = $donnees['3'];
	$adresse = $donnees['4'];
	$pu = $donnees['5'];
	$nbr = $donnees['6'];
	$id_bailleur = $donnees['7'];
	++$total;
	$tlv = ($pu * 2)/100;
	$tom = ($pu * 3.6)/100;
	echo "<tr>";
	echo "<td></td>";
	if ($_SESSION['fonction'] == "administrateur") {
		echo "<td> <a class='tooltipped' data-position='top' data-delay='20' data-tooltip='Modifier' href='m_bailleur.php?id=$id_bailleur'>" . $bailleur . "</a>";
	} else {
		echo "<td>" . $bailleur . "</td>";
	}
	echo "<td>" . $logement . "</td>";
	echo "<td>" . $type_logement . "</td>";
	echo "<td>" . $nbr . "</td>";
	echo "<td>" . $adresse . "</td>";
	echo "<td>" . number_format($pu +$tlv +$tom, 0, '.', ' ') . " Fcfa</td>";
	echo "</tr>";
}
echo "<tr class='grey'>";
echo "<td colspan='3'><b>TOTAL</b></td>";
echo "<td colspan='3'><b>" . $total . " logements libres</b></td>";
echo "</tr>";
if ($resultat < 1) {
	echo "<h3 class='center'>Aucun résultat</h3>";
}

?>
<script type="text/javascript">
	$('.tooltipped').tooltip();
</script>