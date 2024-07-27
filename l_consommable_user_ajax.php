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
	$reponse=$db->prepare("SELECT consommable_utilisation.id, CONCAT(day(consommable_utilisation.date_operation),' ', monthname(consommable_utilisation.date_operation),' ', year(consommable_utilisation.date_operation)), consommable.consommable, consommable_utilisation.quantite, consommable_utilisation.demandeur, consommable_utilisation.commentaire, consommable_utilisation.id_user, consommable.id 
FROM `consommable_utilisation`, consommable 
WHERE consommable_utilisation.id_consommable=consommable.id AND YEAR(consommable_utilisation.date_operation)=? AND MONTH(consommable_utilisation.date_operation)=? ORDER BY consommable_utilisation.date_operation DESC");
	$reponse->execute(array($annee, $mois));
}
else
{
	$reponse=$db->prepare("SELECT consommable_utilisation.id, CONCAT(day(consommable_utilisation.date_operation),' ', monthname(consommable_utilisation.date_operation),' ', year(consommable_utilisation.date_operation)), consommable.consommable, consommable_utilisation.quantite, consommable_utilisation.demandeur, consommable_utilisation.commentaire, consommable_utilisation.id_user, consommable.id 
FROM `consommable_utilisation`, consommable 
WHERE consommable_utilisation.id_consommable=consommable.id AND YEAR(consommable_utilisation.date_operation)=? AND MONTH(consommable_utilisation.date_operation)=? AND consommable.consommable like CONCAT('%', ?, '%') ORDER BY ravitaillement_consommable.date_ravitaillement DESC");
	$reponse->execute(array($annee, $mois, $search));
}

$nbr=$reponse->rowCount();
if ($nbr>0) 
{
	$i=0;
	while ($donnees= $reponse->fetch())
	{
		$id=$donnees['0'];
		$date_operation=$donnees['1'];
		$consommable=$donnees['2'];
		$qt=$donnees['3'];
		$demandeur=$donnees['4'];
		$commentaire=$donnees['5'];
		$id_user=$donnees['6'];
		$id_consommable=$donnees['7'];
		$i++;
		echo "<tr>";
		echo "<td class='grey lighten-3'><b>".$i. "</b></td>";
		if ($_SESSION['fonction']=="administrateur")
		{
		echo "<td><a class='tooltipped' data-position='top' data-delay='50' data-tooltip='cliquez ici pour modifier' href='m_consommable_user.php?id=$id'>".$date_operation."</a></td>";
		}	
		else
		{
			echo "<td>".$date_operation. "</td>";
		}	

		echo "<td>".$consommable. "</td>";
		echo "<td>".$qt."</td>";
		echo "<td>".$demandeur."</td>";
		echo "<td>".$commentaire."</td>";

		if ($_SESSION['fonction']=="administrateur")
		{
			echo "<td> <a class='btn red' href='s_consommable_user.php?id=$id&amp;qt=$qt&amp;id_consommable=$id_consommable' onclick='return(confirm(\"Voulez-vous supprimer cette dotation ?\"))'><i class='material-icons left'>close</i></a></td>";
		} 

		echo "</tr>";
	}
	
}
else
{
	echo "<tr><td></td><td></td><td></td><td><h3>Aucune dotation ce mois ci </td></tr>";
}			
?>
<script type="text/javascript">
	$('.tooltipped').tooltip();
</script>