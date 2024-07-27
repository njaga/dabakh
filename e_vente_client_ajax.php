<?php

include 'connexion.php';
$search=$_POST['search'];
$total=0;
if ($search=="")
{
	$reponse=$db->query("SELECT * FROM `client` WHERE etat='1'");
    $resultat=$reponse->rowCount();
}
else
{
	$reponse=$db->prepare("SELECT * FROM `client` WHERE CONCAT (prenom,' ',nom) like CONCAT('%', ?, '%') AND etat='1' ORDER BY date_inscription DESC");
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
    $sexe=$donnees['3'];
    $telephone=$donnees['4'];
    $adresse=$donnees['5'];
    $i++;
    ++$total;
    echo "<tr>";	
        echo "<td class='grey lighten-3'><b>".$i. "</b></td>";										
        echo "<td>".$prenom."</td>";
        echo "<td>".$nom."</td>";
        echo "<td>".$telephone."</td>";
        echo "<td>".$adresse."</td>";
        echo "<td><a href='e_vente.php?id=".$id."' class='btn'>Selectionner</a></td>";
    echo "</tr>";
}
echo "<tr class='grey'>";
	echo"<td colspan='3'><b>TOTAL</b></td>";
	echo"<td colspan='3'><b>".$total." client</b></td>";
echo "</tr>";

?>