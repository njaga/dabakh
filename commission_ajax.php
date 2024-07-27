<?php		
session_start();			
include 'connexion.php';
$mois=$_POST['mois'];
$annee=$_POST['annee'];
$db->query("SET lc_time_names = 'fr_FR';");
$reponse=$db->prepare("SELECT CONCAT(bailleur.prenom,' ', bailleur.nom),mensualite_bailleur.montant, mensualite_bailleur.commission, CONCAT(day(mensualite_bailleur.date_versement), ' ', monthname(mensualite_bailleur.date_versement), ' ', year(mensualite_bailleur.date_versement)) 
FROM `mensualite_bailleur`, bailleur 
WHERE bailleur.id=mensualite_bailleur.id_bailleur AND mensualite_bailleur.mois=? AND mensualite_bailleur.annee=? ORDER BY mensualite_bailleur.date_versement ASC");
$reponse->execute(array($mois, $annee));
$nbr=$reponse->rowCount();
if ($nbr>0) 
{
	$total_commission=0;
	$total_montant=0;
	while ($donnees=$reponse->fetch()) 
	{
		echo "<tr>";
		echo "<td>".$donnees['3']."</td>";
		echo "<td>".$donnees['0']."</td>";
		echo "<td>".number_format($donnees['1'],0,'.',' ')." Fcfa</td>";
		echo "<td>".number_format($donnees['2'],0,'.',' ')." Fcfa</td>";
		echo "</tr>";
		$total_commission=$total_commission+$donnees['1'];
		$total_montant=$total_montant+$donnees['2'];
	}	
	echo "<tr class='grey darken-3 white-text'>";
	echo "<td colspan='2'><b>TOTAL</b></td>";
	echo "<td><b>".number_format($total_commission,0,'.',' ')." Fcfa</b></td>";
	echo "<td><b>".number_format($total_montant,0,'.',' ')." Fcfa</b></td>";
	echo "</tr>";
}
else
{
	echo "<tr><td></td><td></td><td></td><td><h3>Aucune opération ce mois ci </td></tr>";
}			
?>
