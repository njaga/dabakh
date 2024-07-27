<?php
include 'connexion.php';
$search=$_POST['search'];
$id_log=$_POST['id'];
if ($search=="")
{
	$reponse=$db->query("SELECT bailleur.id, bailleur.num_dossier, bailleur.annee_inscription, bailleur.prenom, bailleur.nom, bailleur.tel, bailleur.adresse 
    FROM `bailleur` 
    ORDER BY bailleur.nom");
$resultat=$reponse->rowCount();
}
else
{
	$reponse=$db->prepare("SELECT bailleur.id, bailleur.num_dossier, bailleur.annee_inscription, bailleur.prenom, bailleur.nom, bailleur.tel, bailleur.adresse 
    FROM `bailleur` 
    WHERE  CONCAT (bailleur.prenom,' ',bailleur.nom) like CONCAT('%', ?, '%') 
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
$prenom=$donnees['3'];
$nom=$donnees['4'];
$tel=$donnees['5'];
$adresse=$donnees['6'];
echo "<tr>";											
	echo "<td>".str_pad($num_dossier, 3,"0", STR_PAD_LEFT)."/".substr($annee_inscription, -2)."</td>";
	echo "<td>".$prenom." ".$nom."</td>";
	echo "<td>".$tel."</td>";
	echo "<td>".$adresse."</td>";
	echo "<td> <a class='btn' href='m_logement_bailleur_trmnt.php?id=$id&amp;log=$id_log' onclick='return(confirm(\"Voulez-vous transférer ce logement à ce bailleur ?\"))'>Selectionner</a></td>";
echo "</tr>";}

?>