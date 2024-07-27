<?php	
session_start();				
include 'connexion.php';
$search=$_POST['search'];
$db->query("SET lc_time_names = 'fr_FR';");
if ($search=="") {
	$reponse=$db->query("SELECT id, prenom, nom, fonction, service FROM personnel WHERE etat='activer'");
}
else
{
	$reponse=$db->prepare("SELECT id, prenom, nom, fonction, service FROM personnel WHERE etat='activer' AND prenom like CONCAT('%', ?, '%') OR nom like CONCAT('%', ?, '%')");
	$reponse->execute(array($search,$search));
}

$resultat=$reponse->rowCount();
while ($donnees= $reponse->fetch())
{
$id=$donnees['0'];
$prenom=ucwords(strtolower($donnees['1']));
$nom=ucwords(($donnees['2']));
$fonction=ucfirst($donnees['3']);
$service=ucwords($donnees['4']);

echo "<tr>";
	echo "<td></td>";
	//echo "<td>".str_pad($num_dossier, 3,"0",STR_PAD_LEFT)."/".substr($annee_inscription, -2)."</td>";
	echo "<td></td>";
	echo "<td>".$prenom." ". $nom."</td>";
	echo "<td>".$fonction."</td>";
	echo "<td>".$service."</td>";
	echo "<td><a class='btn ' href='e_personnel_demandes.php?s=".$id."'>Sélectionner</a></td>";	
	
	
echo "</tr>";}
if ($resultat<1)
{
	echo "<tr><td colspan='4'><h3 class='center'>Aucun personnel</h3></td></tr>";
}

?>