<?php
include 'connexion.php';
$db->query("SET lc_time_names = 'fr_FR';");
if ($_POST['search']=="") 
{
	$req=$db->query('SELECT * FROM consommable ORDER BY consommable');
}
else
{
	$req=$db->prepare('SELECT * FROM `consommable`  
WHERE  consommable like CONCAT("%", ?, "%") ORDER BY consommable');	
	$req->execute(array($_POST['search']));
}
$resultat=$req->rowCount();
while ($donnees= $req->fetch())
{
$id=$donnees['0'];
$consommable=$donnees['1'];						
$pu=$donnees['2'];						
$qt=$donnees['3'];
echo "<tr>";
	echo "<td>".$consommable."</td>";
	echo "<td>".$qt."</td>";
	echo "<td><a href='e_consommable_user1.php?id=".$id."'  class='btn'>Selectionner</a></td>";
echo "</tr>";}

if ($resultat<1)
{
	echo "<h3 class='center'>Aucun résultat</h3>";
}
?>