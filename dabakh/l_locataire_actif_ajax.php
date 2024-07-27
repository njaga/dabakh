<?php
include 'connexion.php';
$search = $_POST['search'];
$total = 0;
if ($search == "") {
	$reponse = $db->query("SELECT `id`, `prenom`, `nom`, `tel`, `cni`, `num_dossier`, `annee_inscription`, `statut`,`email` FROM `locataire` WHERE statut='actif' 
ORDER BY annee_inscription DESC, num_dossier DESC");
	$resultat = $reponse->rowCount();
} else {
	$reponse = $db->prepare("SELECT `id`, `prenom`, `nom`, `tel`, `cni`, `num_dossier`, `annee_inscription`, `statut`,`email` FROM `locataire` WHERE CONCAT (prenom,' ',nom) like CONCAT('%', ?, '%') AND statut='actif' ORDER BY annee_inscription DESC, num_dossier DESC");
	$reponse->execute(array($search));
}
$resultat = $reponse->rowCount();
if ($resultat < 1) {
	echo "<tr><td colspan='7'><h3 class='center'>Aucun résultat</h3></td></tr>";
}
$i = 0;
while ($donnees = $reponse->fetch()) {
	$id = $donnees['0'];
	$prenom = $donnees['1'];
	$nom = $donnees['2'];
	$telephone = $donnees['3'];
	$cni = $donnees['4'];
	$num_dossier = $donnees['5'];
	$annee_inscription = $donnees['6'];
	$statut = $donnees['7'];
	$email = $donnees['8'];
	$loc = $prenom . " " . $nom;
	$i++;
	++$total;
	echo "<tr>";
	echo "<td class='grey lighten-3'><b>" . $i . "</b></td>";
	echo "<td> <a class='tooltipped' data-position='top' data-delay='50' data-tooltip='cliquez ici pour modifier' href='m_locataire.php?id=$id'><i class='material-icons'>edit</i></a></td>";
	echo "<td>" . str_pad($num_dossier, 3, "0", STR_PAD_LEFT) . "/" . substr($annee_inscription, -2) . "</td>";
	echo "<td>" . $prenom . " " . $nom . "</td>";
	echo "<td>" . $cni . "</td>";
	echo "<td>" . $telephone . "</td>";
	echo "<td>" . $email . "</td>";
	echo "<td> <a class='btn' href='infos_mens_loc.php?id=$id'>Détail</a> ";
	echo "<a class='btn brown' href='statistique_mens_loc.php?id=$id&amp;loc=$loc'>Statistiques</a>";
	echo "</td>";
	echo "</tr>";
}
echo "<tr class='grey'>";
echo "<td colspan='3'><b>TOTAL</b></td>";
echo "<td colspan='3'><b>" . $total . " locataires</b></td>";
echo "</tr>";
