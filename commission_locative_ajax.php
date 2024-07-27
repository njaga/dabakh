<?php		
session_start();			
include 'connexion.php';
$mois=$_POST['mois'];
$annee=$_POST['annee'];
$db->query("SET lc_time_names = 'fr_FR';");
$reponse=$db->prepare('SELECT CONCAT(day(caisse_immo.date_operation), " ", monthname(caisse_immo.date_operation), " ", year(caisse_immo.date_operation)), caisse_immo.motif, caisse_immo.montant 
FROM `caisse_immo` 
WHERE caisse_immo.motif LIKE "Commision locataire%" AND month(caisse_immo.date_operation)=?  AND year(caisse_immo.date_operation)=? ORDER BY caisse_immo.date_operation');
$reponse->execute(array($mois, $annee));
$nbr=$reponse->rowCount();
if ($nbr>0) 
{
	$total_commission=0;
	$total_montant=0;
	while ($donnees=$reponse->fetch()) 
	{
		echo "<tr>";
		echo "<td>".$donnees['0']."</td>";
		echo "<td>".$donnees['1']."</td>";
		echo "<td>".number_format($donnees['2'],0,'.',' ')." Fcfa</td>";
		echo "</tr>";
		$total_commission=$total_commission+$donnees['2'];
	}	
	echo "<tr class='grey darken-3 white-text'>";
	echo "<td colspan='2'><b>TOTAL</b></td>";
	echo "<td><b>".number_format($total_commission,0,'.',' ')." Fcfa</b></td>";
	echo "</tr>";
}
else
{
	echo "<tr><td></td><td></td><td></td><td><h3>Aucune opération ce mois ci </td></tr>";
}			
?>
