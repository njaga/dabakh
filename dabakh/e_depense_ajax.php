<?php
include 'connexion.php';
$db->query("SET lc_time_names = 'fr_FR';");
if ($_POST['search']=="") 
{
	$req=$db->query('SELECT * FROM bailleur WHERE etat="activer" ORDER BY nom');
}
else
{
	$req=$db->prepare('SELECT * FROM `bailleur`  
WHERE etat="activer" AND CONCAT (bailleur.prenom," "," ",bailleur.nom) like CONCAT("%", ?, "%") ORDER BY nom');	
	$req->execute(array($_POST['search']));
}
$resultat=$req->rowCount();
while ($donnees= $req->fetch())
{
$id=$donnees['0'];
$num_dossier=$donnees['1'];						
$bailleur=$donnees['2']." ".$donnees['3'];;						
$telephone=$donnees['4'];
$adresse=$donnees['5'];						
$annee_inscription=$donnees['6'];						
echo "<tr>";
	echo "<td>".str_pad($num_dossier, 3,"0", STR_PAD_LEFT)."/".substr($annee_inscription, -2)."</td>";
	echo "<td>".$bailleur."</td>";
	echo "<td>".$telephone."</td>";
	echo "<td>".$adresse."</td>";
	echo "<td><a href='e_depense_bailleur1.php?id=".$id."'  class='btn'>Selectionner</a></td>";
echo "</tr>";}

if ($resultat<1)
{
	echo "<h3 class='center'>Aucun résultat</h3>";
}
?>