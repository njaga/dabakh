<?php	
session_start();				
include 'connexion.php';
$mois=$_POST['mois'];
$personnel=$_POST['personnel'];
$annee=$_POST['annee'];

$db->query("SET lc_time_names = 'fr_FR';");
if ($personnel=="") 
{	
	$reponse=$db->prepare("SELECT CONCAT(day(pointage_personnel.date_pointage),' ', monthname(pointage_personnel.date_pointage),' ', year(pointage_personnel.date_pointage)), CONCAT(personnel.prenom,' ', personnel.nom),  CONCAT(hour(pointage_personnel.heure_debut),'h : ',minute(pointage_personnel.heure_debut), ' - ', hour(pointage_personnel.heure_fin),'h : ',minute(pointage_personnel.heure_fin)) AS HD, (hour(timediff(heure_fin,heure_debut))-1), dayname(pointage_personnel.date_pointage), pointage_personnel.observation, pointage_personnel.id, observation_ad, personnel.id
FROM personnel, pointage_personnel
WHERE personnel.id= pointage_personnel.id_personnel AND YEAR(pointage_personnel.date_pointage)=? AND month(pointage_personnel.date_pointage)=?
ORDER BY pointage_personnel.date_pointage DESC");
$reponse->execute(array($annee, $mois));
}
else
{
	$reponse=$db->prepare("SELECT CONCAT(day(pointage_personnel.date_pointage),' ', monthname(pointage_personnel.date_pointage),' ', year(pointage_personnel.date_pointage)), CONCAT(personnel.prenom,' ', personnel.nom),  CONCAT(hour(pointage_personnel.heure_debut),'h : ',minute(pointage_personnel.heure_debut), ' - ', hour(pointage_personnel.heure_fin),'h : ',minute(pointage_personnel.heure_fin)) AS HD, (hour(timediff(heure_fin,heure_debut))-1), dayname(pointage_personnel.date_pointage), pointage_personnel.observation, pointage_personnel.id, observation_ad, personnel.id
FROM personnel, pointage_personnel
WHERE personnel.id= pointage_personnel.id_personnel AND YEAR(pointage_personnel.date_pointage)=? AND month(pointage_personnel.date_pointage)=? AND pointage_personnel.id_personnel=?
ORDER BY pointage_personnel.date_pointage DESC");
$reponse->execute(array($annee, $mois, $personnel));

}
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
		$id_pointage=$donnees['6'];
		$observation_ad=$donnees['7'];
		$id_personnel=$donnees['8'];
		if ($jour=="samedi" AND $ht>0) 
		{
			$ht=$donnees['3']+1;
		}
		echo "<tr>";
		
		if ($_SESSION['fonction']=="administrateur") 
		{
			echo "<td><a href='m_pointage_personnel.php?id=$id_pointage'>".$date_pointage."</a></td>";
		}
		else
		{
		 	echo "<td>".$date_pointage."</td>";
		}
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
		if ($_SESSION['fonction']=="administrateur") 
		{
		echo "<td><a class='btn red' onclick='return(confirm(\"Voulez-vous supprimer ce pointage ?\"))' href='s_pointage_personnel.php?id=$id_pointage'>supprimer</a></td>";
		}
		
		echo "</tr>";
	}
	if ($personnel!="") 
	{
		$req_demande=$db->prepare("SELECT SUM(datediff(date_fin, date_debut)), SUM(TIMESTAMPDIFF(HOUR,heure_debut, heure_fin))
		FROM `demandes_p_a` 
		WHERE id_personnel=? AND month(date_demande)=?");
		$req_demande->execute(array($id_personnel, $mois));
		$donnees_demande=$req_demande->fetch();
		$nbr_jour=$donnees_demande['0'];
		$nbr_heure=$donnees_demande['1'];
		if ($nbr_heure=="") 
		{
			$nbr_heure=0;
		}
		if ($nbr_jour=="") 
		{
			$nbr_jour=0;
		}
	}
	echo "<tr class=' orange lighten-4'>";
	echo "<td colspan='4='></td>";
	echo "<td><b>TOTAL : ".$total_ht."H</b></td>";
	echo "<td></td>";
	echo "</tr>";

	echo "<tr class=' orange lighten-4'>";
	echo "<td colspan='6='><b>Nombre de jours non travaillé : ".$nbr_jour."</b>jours<b>".$nbr_heure."</b>H</td>";
	
	echo "</tr>";
}
else
{
	echo "<tr><td></td><td></td><td></td><td><h3>Aucun pointage ce mois ci </td></tr>";
}			
?>