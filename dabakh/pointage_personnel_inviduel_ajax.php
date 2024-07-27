<?php	
session_start();				
include 'connexion.php';
$mois=$_POST['mois'];
$personnel=$_POST['personnel'];
$annee=$_POST['annee'];

$db->query("SET lc_time_names = 'fr_FR';");	
$reponse=$db->prepare("SELECT CONCAT(day(pointage_personnel.date_pointage),' ', monthname(pointage_personnel.date_pointage),' ', year(pointage_personnel.date_pointage)), CONCAT(personnel.prenom,' ', personnel.nom),  CONCAT(hour(pointage_personnel.heure_debut),'h : ',minute(pointage_personnel.heure_debut), ' - ', hour(pointage_personnel.heure_fin),'h : ',minute(pointage_personnel.heure_fin)) AS HD, (hour(timediff(heure_fin,heure_debut))-1), dayname(pointage_personnel.date_pointage), pointage_personnel.observation, pointage_personnel.observation_ad
FROM personnel, pointage_personnel
WHERE personnel.id= pointage_personnel.id_personnel AND YEAR(pointage_personnel.date_pointage)=? AND month(pointage_personnel.date_pointage)=? AND personnel.id=?
ORDER BY pointage_personnel.date_pointage DESC");
$reponse->execute(array($annee, $mois, $personnel));


$nbr=$reponse->rowCount();
if ($nbr>0) 
{
	$total_ht=0;
	while ($donnees= $reponse->fetch())
	{
		$date_pointage=$donnees['0'];
		$personnel=$donnees['1'];
		$horaire=$donnees['2'];
		$ht=$donnees['3'];
		$jour=$donnees['4'];
		$observation=$donnees['5'];
		$observation_ad=$donnees['6'];
		if ($jour=="samedi" AND $ht>0) {
		$ht=$donnees['3']+1;
		}
		echo "<tr>";
		echo "<td>".$date_pointage."</td>";
		echo "<td>".$personnel."</td>";
		echo "<td>".$horaire."</td>";
		if ($ht<0) {
		echo "<td>0H</td>";
		}
		else
			{
				echo "<td>".$ht."H</td>";
				$total_ht=$total_ht+$ht;
			}
		echo "<td>".$observation."</td>";
		echo "<td>".$observation_ad."</td>";
		
		echo "</tr>";
	}
	echo "<tr class=' orange lighten-4'>";
	echo "<td colspan='4'></td>";
	echo "<td><b>TOTAL : ".$total_ht."H</b></td>";
	echo "<td></td>";
	echo "</tr>";
}
else
{
	echo "<tr><td></td><td></td><td></td><td><h3>Aucun pointage ce mois ci </td></tr>";
}			
?>