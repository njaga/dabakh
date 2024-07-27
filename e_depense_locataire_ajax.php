<?php
include 'connexion.php';
$search=$_POST['search'];
if ($search=="")
{
	$reponse=$db->query("SELECT locataire.id, CONCAT(locataire.prenom,' ', locataire.nom), locataire.tel, locataire.num_dossier, locataire.annee_inscription, CONCAT(bailleur.prenom,' ', bailleur.nom) 
		FROM `location`, logement, bailleur, locataire
		WHERE logement.id_bailleur=bailleur.id AND location.id_logement=logement.id AND location.id_locataire=locataire.id AND statut='actif' 
ORDER BY bailleur.nom, locataire.annee_inscription, locataire.num_dossier ASC");
$resultat=$reponse->rowCount();
}
else
{
	$reponse=$db->prepare("SELECT locataire.id, CONCAT(locataire.prenom,' ', locataire.nom), locataire.tel, locataire.num_dossier, locataire.annee_inscription, CONCAT(bailleur.prenom,' ', bailleur.nom)
		FROM `location`, logement, bailleur, locataire
		WHERE logement.id_bailleur=bailleur.id AND location.id_logement=logement.id AND location.id_locataire=locataire.id AND CONCAT (locataire.prenom,' ',locataire.nom) like CONCAT('%', ?, '%') AND locataire.statut='actif' ORDER BY bailleur.nom, locataire.annee_inscription, locataire.num_dossier ASC");
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
$locataire=$donnees['1'];
$tel=$donnees['2'];
$num_dossier=$donnees['3'];
$annee_inscription=$donnees['4'];
$bailleur=$donnees['5'];
echo "<tr>";											
	echo "<td>".str_pad($num_dossier, 3,"0", STR_PAD_LEFT)."/".substr($annee_inscription, -2)."</td>";
	echo "<td>".$locataire."</td>";
	echo "<td>".$tel."</td>";
	echo "<td>".$bailleur."</td>";
	echo "<td> <a class='btn' href='e_depense_locataire1.php?id=$id'>Selectionner</a></td>";
echo "</tr>";}

?>