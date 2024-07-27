<?php
include 'connexion.php';
$search=$_POST['search'];
$total=0;
if ($search=="")
{
	$reponse=$db->query("SELECT locataire.id, locataire.prenom, locataire.nom, locataire.tel, locataire.cni, locataire.num_dossier, locataire.annee_inscription, locataire.statut, CONCAT(DATE_FORMAT(location.date_debut,'%d'),'/',DATE_FORMAT(location.date_debut,'%m'),'/',DATE_FORMAT(location.date_debut,'%Y')), CONCAT(DATE_FORMAT(location.date_fin,'%d'),'/',DATE_FORMAT(location.date_fin,'%m'),'/',DATE_FORMAT(location.date_fin,'%Y'))
FROM `location`, locataire 
WHERE location.id_locataire=locataire.id AND locataire.statut='inactif' 
ORDER BY annee_inscription, num_dossier ASC");
$resultat=$reponse->rowCount();
}
else
{
	$reponse=$db->prepare("SELECT locataire.id, locataire.prenom, locataire.nom, locataire.tel, locataire.cni, locataire.num_dossier, locataire.annee_inscription, locataire.statut, CONCAT(DATE_FORMAT(location.date_debut,'%d'),'/',DATE_FORMAT(location.date_debut,'%m'),'/',DATE_FORMAT(location.date_debut,'%Y')), CONCAT(DATE_FORMAT(location.date_fin,'%d'),'/',DATE_FORMAT(location.date_fin,'%m'),'/',DATE_FORMAT(location.date_fin,'%Y'))
FROM `location`, locataire 
WHERE location.id_locataire=locataire.id AND locataire.statut='inactif' AND CONCAT (prenom,' ',nom) like CONCAT('%', ?, '%') 
ORDER BY annee_inscription, num_dossier ASC");
	$reponse->execute(array($search));
}
$resultat=$reponse->rowCount();
if ($resultat<1)
{
	echo "<tr><td colspan='7'><h3 class='center'>Aucun résultat</h3></td></tr>";
}
$i=0;
while ($donnees= $reponse->fetch())
{
$id=$donnees['0'];
$prenom=$donnees['1'];
$nom=$donnees['2'];
$telephone=$donnees['3'];
$cni=$donnees['4'];
$num_dossier=$donnees['5'];
$annee_inscription=$donnees['6'];
$statut=$donnees['7'];
$date_debut=$donnees['8'];
$date_fin=$donnees['9'];
++$total;
$i++;
echo "<tr>";
	echo "<td class='grey lighten-3'><b>".$i. "</b></td>";											
	echo "<td> <a class='tooltipped' data-position='top' data-delay='50' data-tooltip='cliquez ici pour modifier' href='m_locataire.php?id=$id'><i class='material-icons'>edit</i></a></td>";
	echo "<td>".str_pad($num_dossier, 3,"0", STR_PAD_LEFT)."/".substr($annee_inscription, -2)."</td>";
	echo "<td>".$prenom." ".$nom."</td>";
	echo "<td>".$cni."</td>";
	echo "<td>".$telephone."</td>";
	echo "<td>Du <b>".$date_debut."</b> au <b>".$date_fin."</b></td>";
	echo "<td> <a href='infos_mens_loc.php?id=$id'>Détail</a></td>";
	echo "<td><a class='red btn' href='s_locataire.php?id=".$id."' onclick='return(confirm(\"Voulez-vous supprimer ce locataire ?\"))'>Supprimer </a></td>";
	echo "<td><a class='brown btn' href='e_location.php?id=".$id."' onclick='return(confirm(\"Voulez-vous réactiver ce locataire ?\"))'>Réactiver </a></td>";
echo "</tr>";}
echo "<tr class='grey'>";
	echo"<td colspan='3'><b>TOTAL</b></td>";
	echo"<td colspan='3'><b>".$total." locataires</b></td>";
echo "</tr>";
?>