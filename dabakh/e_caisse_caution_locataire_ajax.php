<?php
include 'connexion.php';
$search=$_POST['search'];
$total=0;
if ($search=="")
{
	$reponse=$db->query("SELECT locataire.id, locataire.prenom, locataire.nom, locataire.tel, locataire.cni, locataire.num_dossier, locataire.annee_inscription,locataire.statut, caisse_caution.montant 
FROM `caisse_caution`, location, locataire 
WHERE caisse_caution.id_location=location.id AND location.id_locataire=locataire.id AND locataire.statut='actif'
ORDER BY locataire.annee_inscription, locataire.num_dossier ASC");
$resultat=$reponse->rowCount();
}
else
{
	$reponse=$db->prepare("SELECT locataire.id, locataire.prenom, locataire.nom, locataire.tel, locataire.cni, locataire.num_dossier, locataire.annee_inscription,locataire.statut, caisse_caution.montant 
FROM `caisse_caution`, location, locataire 
WHERE caisse_caution.id_location=location.id AND location.id_locataire=locataire.id AND locataire.statut='actif' AND CONCAT (prenom,' ',nom) like CONCAT('%',?,'%')
ORDER BY locataire.annee_inscription, locataire.num_dossier ASC  ");
	$reponse->execute(array($search));
}
$resultat=$reponse->rowCount();
if ($resultat<1)
{
	echo "<tr><td colspan='7'><h3 class='center'>Aucun résultat</h3></td></tr>";
}
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
	++$total;
	echo "<tr>";											
		echo "<td> <a class='tooltipped' data-position='top' data-delay='50' data-tooltip='cliquez ici pour modifier' href='m_locataire.php?id=$id'><i class='material-icons'>edit</i></a></td>";
		echo "<td>".str_pad($num_dossier, 3,"0", STR_PAD_LEFT)."/".substr($annee_inscription, -2)."</td>";
		echo "<td>".$prenom." ".$nom."</td>";
		echo "<td>".$cni."</td>";
		echo "<td>".$telephone."</td>";
		echo "<td> <a class='btn' href='e_caisse_caution.php?id=$id'>Sélectionner</a></td>";
		echo "</tr>";
}
echo "<tr class='grey'>";
	echo"<td colspan='3'><b>TOTAL</b></td>";
	echo"<td colspan='3'><b>".$total." locataires</b></td>";
echo "</tr>";

?>