<?php	
session_start();				
include 'connexion.php';
$mois=$_POST['mois'];
$db->query("SET lc_time_names = 'fr_FR';");

$reponse=$db->prepare("SELECT rapport_consultation_domicile.id, CONCAT(patient.prenom,' ', patient.nom),  CONCAT(day(patient.date_naissance), '/', month(patient.date_naissance),'/', year(patient.date_naissance))  ,CONCAT(day(consultation_domicile.date_consultation), ' ', monthname(consultation_domicile.date_consultation),' ', year(consultation_domicile.date_consultation)), CONCAT(day(rapport_consultation_domicile.date_rapport),' ',monthname(rapport_consultation_domicile.date_rapport),' ',year(rapport_consultation_domicile.date_rapport)),rapport_consultation_domicile.infirmier
FROM consultation_domicile, patient, rapport_consultation_domicile
WHERE patient.id_patient=consultation_domicile.id_patient AND consultation_domicile.id_consultation=rapport_consultation_domicile.id_consultation AND month(rapport_consultation_domicile.date_rapport)=?");
$reponse->execute(array($mois));
$nbr=$reponse->rowCount();
if ($nbr>0) 
{
	while ($donnees= $reponse->fetch())
	{
		$id=$donnees['0'];
		$patient=$donnees['1'];
		$date_naissance=$donnees['2'];
		$date_consultation=$donnees['3'];
		$date_rapport=$donnees['4'];
		$infirmier=$donnees['5'];
		
		echo "<tr>";
		if ($_SESSION['fonction']=="administrateur" or $_SESSION['fonction']=="daf") 
		{
			echo "<td><a class='tooltipped' data-position='top' data-delay='50' data-tooltip='cliquez ici pour modifier' href='m_rapport_assis.php?id=$id'>".$date_rapport."</a></td>>";
		}
		else
		{
			echo "<td>".$date_rapport."</td>";
		}
			echo "<td>".$patient." né le ".$date_naissance."</td>";
			echo "<td>".$date_consultation."</td>";
			echo "<td>".$infirmier."</td>";
			echo "<td><a class='' href='i_rapport_assis.php?id=$id'>Détails</a></td>>";
			if ($_SESSION['fonction']=="administrateur" or $_SESSION['fonction']=="daf") 
			{
			echo "<td><a class='tooltipped btn red' data-position='top' data-delay='50' data-tooltip='supprimer' href='s_rapport_assis.php?id=$id' onclick='return(confirm(\"Voulez-vous supprimer ce rapport?\"))'><i class='material-icons left'>close</i></a></td>";
			}
		echo "</tr>";
	}
	
}
else
{
	echo "<tr><td></td><td></td><td><h3>Aucun rapport ce mois ci </td></tr>";
}			
?>
<script type="text/javascript">
	$('.tooltipped').tooltip();
</script>