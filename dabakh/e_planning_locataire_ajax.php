<?php
include 'connexion.php';
$search=$_POST['search'];
$id_planning=$_POST['id'];
$annee=date("Y");
if ($search=="")
{
	$reponse=$db->prepare("SELECT DISTINCT locataire.id, CONCAT(locataire.prenom,' ', locataire.nom), locataire.tel, locataire.num_dossier, locataire.annee_inscription, CONCAT(bailleur.prenom,' ', bailleur.nom)
	FROM `mensualite`,logement, locataire, location, bailleur, type_logement 
	WHERE location.id_locataire=locataire.id AND location.id_logement=logement.id AND location.id NOT IN (SELECT mensualite.id_location FROM mensualite WHERE mensualite.annee=?) AND logement.id_bailleur=bailleur.id AND type_logement.id = logement.id_type AND location.etat='active' AND mensualite.annee=? 
ORDER BY bailleur.nom, locataire.annee_inscription, locataire.num_dossier ASC");
$reponse->execute(array($annee,$annee));
$resultat=$reponse->rowCount();
}
else
{
	$reponse=$db->prepare("SELECT DISTINCT locataire.id, CONCAT(locataire.prenom,' ', locataire.nom), locataire.tel, locataire.num_dossier, locataire.annee_inscription, CONCAT(bailleur.prenom,' ', bailleur.nom)
	FROM `mensualite`,logement, locataire, location, bailleur, type_logement 
	WHERE location.id_locataire=locataire.id AND location.id_logement=logement.id AND location.id NOT IN (SELECT mensualite.id_location FROM mensualite WHERE mensualite.annee=?) AND logement.id_bailleur=bailleur.id AND type_logement.id = logement.id_type AND location.etat='active' AND mensualite.annee=?  AND CONCAT (locataire.prenom,' ',locataire.nom) like CONCAT('%', ?, '%') ORDER BY bailleur.nom, locataire.annee_inscription, locataire.num_dossier ASC");
	$reponse->execute(array($annee, $annee, $search));
}
$resultat=$reponse->rowCount();
if ($resultat<1)
{
	echo "<tr><td colspan='7'><h3 class='center'>Aucun résultat</h3></td></tr>";
}
$i=1;
while ($donnees= $reponse->fetch())
{
$id=$donnees['0'];
$locataire=$donnees['1'];
$tel=$donnees['2'];
$num_dossier=$donnees['3'];
$annee_inscription=$donnees['4'];
$bailleur=$donnees['5'];
echo "<tr>";			
	echo "<td class='grey lighten-3'><b>".$i. "</b></td>";								
	echo "<td>".str_pad($num_dossier, 3,"0", STR_PAD_LEFT)."/".substr($annee_inscription, -2)."</td>";
	echo "<td>".$locataire."</td>";
	echo "<td>".$tel."</td>";
	echo "<td>".$bailleur."</td>";
	echo "<td> <a class='btn' href='e_planning_trmnt.php?id=$id&amp;id_planning=$id_planning'>Selectionner</a></td>";
echo "</tr>";
$i++;
}

?>