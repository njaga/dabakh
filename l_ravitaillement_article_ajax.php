<?php	
session_start();				
include 'connexion.php';
$mois=$_POST['mois'];
$annee=$_POST['annee'];
$search=$_POST['search'];
$somme=0;
$db->query("SET lc_time_names = 'fr_FR';");
if ($search=="") 
{
	$reponse=$db->prepare("SELECT ravitaillement_article.id, CONCAT(day(ravitaillement_article.date_ravitaillement),' ', monthname(ravitaillement_article.date_ravitaillement),' ', year(ravitaillement_article.date_ravitaillement)), article.article, ravitaillement_article.montant,ravitaillement_article.qt, ravitaillement_article.ancien_qt
FROM ravitaillement_article, article 
WHERE article.id=ravitaillement_article.id_article AND month(ravitaillement_article.date_ravitaillement)=? AND year(ravitaillement_article.date_ravitaillement)=? ORDER BY ravitaillement_article.date_ravitaillement DESC");
	$reponse->execute(array($mois, $annee));
}
else
{
	$reponse=$db->prepare("SELECT ravitaillement_article.id, CONCAT(day(ravitaillement_article.date_ravitaillement),' ', monthname(ravitaillement_article.date_ravitaillement),' ', year(ravitaillement_article.date_ravitaillement)), article.article, ravitaillement_article.montant,ravitaillement_article.qt, ravitaillement_article.ancien_qt
FROM ravitaillement_article, article 
WHERE article.id=ravitaillement_article.id_article AND month(ravitaillement_article.date_ravitaillement)=? AND year(ravitaillement_article.date_ravitaillement)=? AND article.article like CONCAT('%', ?, '%') ORDER BY ravitaillement_article.date_ravitaillement DESC");
	$reponse->execute(array($mois, $annee, $search));
}

$nbr=$reponse->rowCount();
if ($nbr>0) 
{
	$i=0;
	while ($donnees= $reponse->fetch())
	{
		$i++;
		$id=$donnees['0'];
		$date_ravitaillement=$donnees['1'];
		$article=$donnees['2'];
		$montant=$donnees['3'];
		$qt=$donnees['4'];
		$ancien_qt=$donnees['5'];
		echo "<tr>";
		echo "<td class='grey lighten-3'><b>".$i. "</b></td>";
		echo "<td><a class='tooltipped' data-position='top' data-delay='50' data-tooltip='cliquez ici pour modifier' href='m_ravitaillement_article.php?id=$id'>".$date_ravitaillement."</a></td>";
		echo "<td>".$article. "</td>";
		echo "<td>".number_format($montant,0,'.',' ')." Fcfa</td>";
		echo "<td>".$ancien_qt."</td>";
		echo "<td>".$qt."</td>";
		echo "<td> <a class='btn red' href='s_ravitaillement_article.php?id=$id&amp;a_qt=$ancien_qt' onclick='return(confirm(\"Voulez-vous supprimer ce ravitaillement ?\"))'><i class='material-icons left'>close</i></a></td>";

		echo "</tr>";
	}
	
}
else
{
	echo "<tr><td></td><td></td><td></td><td><h3>Aucun ravitaillement ce mois ci </td></tr>";
}			
?>
<script type="text/javascript">
	$('.tooltipped').tooltip();
</script>