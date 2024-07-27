<?php	
session_start();				
include 'connexion.php';
$mois=$_POST['mois'];
$annee=$_POST['annee'];
$search=$_POST['search'];
$type_courrier=$_POST['type_courrier'];
$nbr=0;
$total=0;
$db->query("SET lc_time_names = 'fr_FR';");
if ($search=="") 
{
	if ($type_courrier==0) 
	{
		$reponse=$db->prepare("SELECT id, numero, CONCAT(DATE_FORMAT(date_courrier, '%d'), '/', DATE_FORMAT(date_courrier, '%m'),'/', DATE_FORMAT(date_courrier, '%Y')), type_courrier, intitule, description, expediteur, destinataire, chemin, id_user 
        FROM `courrier` 
        WHERE YEAR(date_courrier)=? AND MONTH(date_courrier)=?");
		$reponse->execute(array($annee, $mois));
	}
	else
	{
		$reponse=$db->prepare("SELECT id, numero, CONCAT(DATE_FORMAT(date_courrier, '%d'), '/', DATE_FORMAT(date_courrier, '%m'),'/', DATE_FORMAT(date_courrier, '%Y')), type_courrier, intitule, description, expediteur, destinataire, chemin, id_user 
        FROM `courrier` 
        WHERE YEAR(date_courrier)=? AND MONTH(date_courrier)=? AND type_courrier=?");
		$reponse->execute(array($annee, $mois, $type_courrier));
	}
}
else
{
	if ($type_courrier==0) 
	{
		$reponse=$db->prepare("SELECT id, numero, CONCAT(DATE_FORMAT(date_courrier, '%d'), '/', DATE_FORMAT(date_courrier, '%m'),'/', DATE_FORMAT(date_courrier, '%Y')), type_courrier, intitule, description, expediteur, destinataire, chemin, id_user 
        FROM `courrier` 
        WHERE YEAR(date_courrier)=? AND MONTH(date_courrier)=? AND intitule like CONCAT('%', ?, '%')");
		$reponse->execute(array($annee, $mois, $search));
	}
	else
	{
		$reponse=$db->prepare("SELECT id, numero, CONCAT(DATE_FORMAT(date_courrier, '%d'), '/', DATE_FORMAT(date_courrier, '%m'),'/', DATE_FORMAT(date_courrier, '%Y')), type_courrier, intitule, description, expediteur, destinataire, chemin, id_user 
        FROM `courrier` 
        WHERE YEAR(date_courrier)=? AND MONTH(date_courrier)=? AND type_courrier=? AND intitule like CONCAT('%', ?, '%')");
		$reponse->execute(array($annee, $mois, $type_courrier, $type_courrier, $search));
	}
		
}

$nbr=$reponse->rowCount();
if ($nbr>0) 
{
	$i=0;
	while ($donnees= $reponse->fetch())
	{
		$i++;
		$id=$donnees['0'];
		$numero=$donnees['1'];
		$date_courrier=$donnees['2'];
		$type_courrier=$donnees['3'];
		$intitule=$donnees['4'];
		$description=$donnees['5'];
		$expediteur=$donnees['6'];
		$destinataire=$donnees['7'];
		$chemin=$donnees['8'];
		$id_user=$donnees['9'];
		
		echo "<tr>";

		echo "<td class='grey lighten-3'><b>".$i. "</b></td>";
		if ($_SESSION['fonction']=="administrateur") 
		{
			//echo "<td><a class='tooltipped ' data-position='top' data-delay='50' data-tooltip='cliquez ici pour modifier' href='m_courrier.php?id=$id'>".$date_courrier."</a></td>";
            echo "<td class=' '>".$date_courrier."</td>";
		}
		else
		{
			echo "<td class=' '>".$date_courrier."</td>";
		}
		
		echo "<td>".$numero. "</td>";
		echo "<td>".$type_courrier."</td>";
		echo "<td>".$intitule."</td>";
		echo "<td>".$destinataire."</td>";
		echo "<td>".$expediteur."</td>";
		echo "<td> <a class='btn'   href='".$chemin."'><i class='material-icons left'>print</i></a>";
		echo "</td>";
		if ($_SESSION['fonction']=="administrateur" ) 
		{
		echo "<td>";
		echo"
		<a class='btn red' href='s_courrier.php?id=$id' onclick='return(confirm(\"Voulez-vous supprimer ce courrier ?\"))'><i class='material-icons'>close</i></a><br>";
		echo $id_user."</td>";
			
		}
		echo "</tr>";
		$nbr=$nbr+1;
	}
	
	
}
else
{
	echo "<tr><td></td><td></td><td></td><td><h3>Aucun courrier ce mois ci </td></tr>";
}			
?>
<script type="text/javascript">
	$('.tooltipped').tooltip();
</script>