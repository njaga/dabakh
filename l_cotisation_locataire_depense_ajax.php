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
	$reponse=$db->prepare("SELECT cotisation_locataire_depense.id, CONCAT(bailleur.prenom,' ',bailleur.nom),CONCAT(day(cotisation_locataire_depense.date_depense),' ', monthname(cotisation_locataire_depense.date_depense),' ', year(cotisation_locataire_depense.date_depense)), cotisation_locataire_depense.motif, cotisation_locataire_depense.montant_a_regler, cotisation_locataire_depense.montant_regler, cotisation_locataire_depense.reliquat, cotisation_locataire_depense.id_user  
    FROM `cotisation_locataire_depense` 
    INNER JOIN bailleur ON cotisation_locataire_depense.id_bailleur=bailleur.id
    WHERE month(cotisation_locataire_depense.date_depense)=? AND YEAR(cotisation_locataire_depense.date_depense)=?");
	$reponse->execute(array($mois, $annee));
}
else
{
	$reponse=$db->prepare("SELECT cotisation_locataire_depense.id, CONCAT(bailleur.prenom,' ',bailleur.nom),CONCAT(day(cotisation_locataire_depense.date_depense),' ', monthname(cotisation_locataire_depense.date_depense),' ', year(cotisation_locataire_depense.date_depense)), cotisation_locataire_depense.motif, cotisation_locataire_depense.montant_a_regler, cotisation_locataire_depense.montant_regler, cotisation_locataire_depense.reliquat, cotisation_locataire_depense.id_user  
    FROM `cotisation_locataire_depense` 
    INNER JOIN bailleur ON cotisation_locataire_depense.id_bailleur=bailleur.id
    WHERE month(cotisation_locataire_depense.date_depense)=? AND YEAR(cotisation_locataire_depense.date_depense)=? AND CONCAT (bailleur.prenom,' ',bailleur.nom) like CONCAT('%', ?, '%')");
	$reponse->execute(array($mois, $annee, $search));
}

$nbr=$reponse->rowCount();
if ($nbr>0) 
{
	$i=1;
	while ($donnees= $reponse->fetch())
	{
		$id=$donnees['0'];
		$bailleur=$donnees['1'];
		$date_depense=$donnees['2'];
		$motif=$donnees['3'];
		$montatnt_a_regler=$donnees['4'];
		$montatnt_regler=$donnees['5'];
		$reliquat=$donnees['6'];
		$id_user=$donnees['7'];
		

		echo "<tr>";
		echo "<td class='grey lighten-3'><b>".$i. "</b></td>";
		if ($_SESSION['fonction']!="secretaire" or $_SESSION['fonction']=="administrateur") 

		{
			echo "<td><a class='tooltipped' data-position='top' data-delay='50' data-tooltip='cliquez ici pour modifier' href='m_cotisation_locataire_depense.php?id=$id'>".$date_depense."</a></td>";
		}
		else
		{
			echo "<td>".$date_depense. "</td>";

		}
		
		echo "<td>".$bailleur. "</td>";
		echo "<td>".$motif. "</td>";
		echo "<td>".number_format(($montatnt_a_regler),0,'.',' ')." Fcfa</td>";
		echo "<td>".number_format($montatnt_regler,0,'.',' ')." Fcfa</td>";
		echo "<td>".number_format($reliquat,0,'.',' ')." Fcfa</td>";
		if ($_SESSION['fonction']=="administrateur")
		{
            echo "<td>".$id_user. "</td>";
            echo "<td>";
			    echo "<br><br> <a class='btn red' href='s_cotisation_locataire_depense.php?id=$id' onclick='return(confirm(\"Voulez-vous supprimer cette dépense cotisation ?\"))'><i class='material-icons left'>close</i></a>";
            echo "</td>";
		} 
		

		echo "</tr>";
		$i++;
	}
	
}
else
{
	echo "<tr><td></td><td></td><td></td><td><h3>Aucun versment ce mois ci </td></tr>";
}			
?>
<script type="text/javascript">
	$('.tooltipped').tooltip();
</script>