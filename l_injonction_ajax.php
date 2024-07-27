<?php	
session_start();				
include 'connexion.php';
$mois=$_POST['mois'];
$annee=$_POST['annee'];
$search=$_POST['search'];
$nbr=0;
$db->query("SET lc_time_names = 'fr_FR';");

if ($search=="") 
{
	$reponse=$db->prepare('SELECT injonction.id, CONCAT(DATE_FORMAT(date_injonction, "%d"), "/", DATE_FORMAT(date_injonction, "%m"),"/", DATE_FORMAT(date_injonction, "%Y")), injonction.nbr_mois, injonction.montant, injonction.a_ajouter, CONCAT(DATE_FORMAT(injonction.date_echeance, "%d"), "/", DATE_FORMAT(injonction.date_echeance, "%m"),"/", DATE_FORMAT(injonction.date_echeance, "%Y")), locataire.num_dossier, locataire.annee_inscription, CONCAT(locataire.prenom," ",locataire.nom), CONCAT(bailleur.prenom," ",bailleur.nom), injonction.id_user, injonction.mnt_a_ajouter
FROM `injonction`, locataire, location, logement, bailleur 
WHERE injonction.id_locataire=locataire.id AND locataire.id=location.id_locataire AND location.id_logement=logement.id AND logement.id_bailleur=bailleur.id AND month(injonction.date_injonction)=? AND year(injonction.date_injonction)=?');
	$reponse->execute(array($mois, $annee));
}
else
{
	$reponse=$db->prepare('SELECT injonction.id, CONCAT(DATE_FORMAT(date_injonction, "%d"), "/", DATE_FORMAT(date_injonction, "%m"),"/", DATE_FORMAT(date_injonction, "%Y")), injonction.nbr_mois, injonction.montant, injonction.a_ajouter, CONCAT(DATE_FORMAT(injonction.date_echeance, "%d"), "/", DATE_FORMAT(injonction.date_echeance, "%m"),"/", DATE_FORMAT(injonction.date_echeance, "%Y")), locataire.num_dossier, locataire.annee_inscription, CONCAT(locataire.prenom," ",locataire.nom), CONCAT(bailleur.prenom," ",bailleur.nom), injonction.id_user, injonction.mnt_a_ajouter
FROM `injonction`, locataire, location, logement, bailleur 
WHERE injonction.id_locataire=locataire.id AND locataire.id=location.id_locataire AND location.id_logement=logement.id AND logement.id_bailleur=bailleur.id AND month(injonction.date_injonction)=? AND year(injonction.date_injonction)=? AND CONCAT (locataire.prenom," ",locataire.nom) like CONCAT("%", ?, "%")');
	$reponse->execute(array($mois, $annee, $search));
}

$nbr=$reponse->rowCount();
if ($nbr>0) 
{
	$i=0;
	while ($donnees= $reponse->fetch())
	{
		$id=$donnees['0'];
		$date_injonction=$donnees['1'];
		$nbr_mois=$donnees['2'];
		$montant=$donnees['3'];
		$a_ajouter=$donnees['4'];
		$date_echeance=$donnees['5'];
		$num_dossier=$donnees['6'];
		$annee_inscription=$donnees['7'];
		$locataire=$donnees['8'];
		$bailleur=$donnees['9'];
		$id_user=$donnees['10'];
		$mnt_a_ajouter=$donnees['11'];
		$i++;
		
		echo "<tr>";
		echo "<td class='grey lighten-3'><b>".$i. "</b></td>";
		if ($_SESSION['fonction']=="administrateur") 
		{
			echo "<td><a class='tooltipped ' data-position='top' data-delay='50' data-tooltip='cliquez ici pour modifier' href='m_injonction.php?id=$id'>".$date_injonction."</a></td>";
		}
		else
		{
			echo "<td class=' '>".$date_injonction."</td>";
		}
		echo "<td>".$locataire. "</td>";
		echo "<td>".$bailleur."</td>";
		echo "<td>".number_format(($mnt_a_ajouter+$montant),0,'.',' ')." </td>";
		echo "<td> <a class='btn tooltipped'  data-position='top' data-delay='50' data-tooltip='N° ".$id."' href='i_injonction.php?id=$id'><i class='material-icons left'>print</i></a> ";
		if ($_SESSION['fonction']=="administrateur") 
		{
			echo"
		<a class='btn red tooltipped' data-position='top' data-delay='50' data-tooltip='Supprimer' href='s_injonction.php?id=$id' onclick='return(confirm(\"Voulez-vous supprimer cette injonction ?\"))'><i class='material-icons'>close</i></a>";
		}
		
		echo "</td>";
		if ($_SESSION['fonction']=="administrateur") 
		{
		echo "<td>".$id_user."</td>";
			
		}
		echo "</tr>";
		$nbr=$nbr+1;
	}
}
else
{
	echo "<tr><td></td><td></td><td></td><td><h3>Aucune injonction ce mois ci </td></tr>";
}			
?>
<script type="text/javascript">
	$('.tooltipped').tooltip();
</script>