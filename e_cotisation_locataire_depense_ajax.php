<?php
include 'connexion.php';
$search=$_POST['search'];
if ($search=="")
{
	$reponse=$db->query("SELECT bailleur.id, bailleur.num_dossier, bailleur.annee_inscription, SUM(cotisation_locataire.montant), bailleur.prenom, bailleur.nom, bailleur.tel 
    FROM `cotisation_locataire` 
    INNER JOIN locataire ON cotisation_locataire.id_locataire=locataire.id
    INNER JOIN location ON location.id_locataire=locataire.id
    INNER JOIN logement ON location.id_logement=logement.id
    INNER JOIN bailleur ON logement.id_bailleur=bailleur.id
    WHERE cotisation_locataire.id_cotisation_depense=0 
    GROUP BY bailleur.id
    ORDER BY bailleur.nom");
$resultat=$reponse->rowCount();
}
else
{
	$reponse=$db->prepare("SELECT bailleur.id, bailleur.num_dossier, bailleur.annee_inscription, SUM(cotisation_locataire.montant), bailleur.prenom, bailleur.nom, bailleur.tel 
    FROM `cotisation_locataire` 
    INNER JOIN locataire ON cotisation_locataire.id_locataire=locataire.id
    INNER JOIN location ON location.id_locataire=locataire.id
    INNER JOIN logement ON location.id_logement=logement.id
    INNER JOIN bailleur ON logement.id_bailleur=bailleur.id
    WHERE cotisation_locataire.id_cotisation_depense=0 AND CONCAT (bailleur.prenom,' ',bailleur.nom) like CONCAT('%', ?, '%') 
    GROUP BY bailleur.id
    ORDER BY bailleur.nom");
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
$num_dossier=$donnees['1'];
$annee_inscription=$donnees['2'];
$montant=$donnees['3'];
$prenom=$donnees['4'];
$nom=$donnees['5'];
$tel=$donnees['6'];
echo "<tr>";											
	echo "<td>".str_pad($num_dossier, 3,"0", STR_PAD_LEFT)."/".substr($annee_inscription, -2)."</td>";
	echo "<td>".$prenom." ".$nom."</td>";
	echo "<td>".$tel."</td>";
	echo "<td>".number_format($montant,0,'.',' ')."</td>";
	echo "<td> <a class='btn' href='e_cotisation_locataire_depense1.php?id=$id&amp;mnt=$montant'>Selectionner</a></td>";
echo "</tr>";}

?>