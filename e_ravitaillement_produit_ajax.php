<?php
include 'connexion.php';
$db->query("SET lc_time_names = 'fr_FR';");
/* code à utiliser si tous les produits ont été ravitaillé
if ($_POST['search']=="") 
{
	$req=$db->query("SELECT produit.produit, produit.pu, CONCAT(day(ravitaillement_produit.date_ravitaillement),' ', monthname(ravitaillement_produit.date_ravitaillement),' ', year(ravitaillement_produit.date_ravitaillement)), produit.qt, produit.id 
		FROM ravitaillement_produit, produit 
		WHERE produit.id=ravitaillement_produit.id_produit ORDER BY produit.produit");
}
else
{
	$req=$db->prepare("SELECT produit.produit, produit.pu, CONCAT(day(ravitaillement_produit.date_ravitaillement),' ', monthname(ravitaillement_produit.date_ravitaillement),' ', year(ravitaillement_produit.date_ravitaillement)), produit.qt, produit.id 
		FROM ravitaillement_produit, produit 
		WHERE produit.id=ravitaillement_produit.id_produit AND produit.produit like CONCAT('%', ?, '%') ORDER BY produit.produit");	
	$req->execute(array($_POST['search']));
}
*/
if ($_POST['search']=="") 
{
	$req=$db->query("SELECT produit.produit, produit.pu, produit.qt, produit.id 
		FROM produit 
		ORDER BY produit.produit");
}
else
{
	$req=$db->prepare("SELECT produit.produit, produit.pu, produit.qt, produit.id 
		FROM produit 
		WHERE produit.produit like CONCAT('%', ?, '%') ORDER BY produit.produit");	
	$req->execute(array($_POST['search']));
}
$resultat=$req->rowCount();
while ($donnees= $req->fetch())
{

$produit=$donnees['0'];
$pu=$donnees['1'];						
$qt=$donnees['2'];						
$id=$donnees['3'];
echo "<tr>";
	//echo "<td> <a class='tooltipped' data-position='top' data-delay='50' data-tooltip='cliquez ici pour modifier' href='m_location.php?id=$id'><i class='material-icons'>edit</i></a></td>";
	echo "<td>".$produit."</td>";
	echo "<td>".$qt."</td>";
	echo "<td>".number_format($pu,0,'.',' ')." Fcfa</td>";
	echo "<td><a href='e_ravitaillement_produit1.php?id=".$id."'  class='btn'>Selectionner</a></td>";
echo "</tr>";}

if ($resultat<1)
{
	echo "<h3 class='center'>Aucun résultat</h3>";
}
?>