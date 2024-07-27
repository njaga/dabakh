<?php
session_start();
include 'connexion.php';
$search=$_POST['search'];


$db->query("SET lc_time_names = 'fr_FR';");
if ($search=="") 
{
	$reponse=$db->query("SELECT logement.id, CONCAT(bailleur.prenom,' ', bailleur.nom), logement.designation, type_logement.type_logement, logement.adresse, logement.pu, (logement.nbr-logement.nbr_occupe), logement.nbr,logement.nbr_occupe
	FROM `logement`, bailleur, type_logement  
	WHERE logement.id_type=type_logement.id AND logement.id_bailleur=bailleur.id AND bailleur.etat='activer' AND logement.nbr>0 order by bailleur.nom");
}
else
{
	$reponse=$db->prepare("SELECT logement.id, CONCAT(bailleur.prenom,' ', bailleur.nom), logement.designation, type_logement.type_logement, logement.adresse, logement.pu, (logement.nbr-logement.nbr_occupe), logement.nbr,logement.nbr_occupe
	FROM `logement`, bailleur, type_logement  
	WHERE logement.id_type=type_logement.id AND logement.id_bailleur=bailleur.id AND bailleur.etat='activer' AND logement.nbr>0 AND CONCAT (bailleur.prenom,' ',' ',bailleur.nom) like CONCAT('%', ?, '%') order by bailleur.nom");
	$reponse->execute(array($search));
}
$resultat=$reponse->rowCount();
if ($resultat<1)
{
	echo "<tr><td colspan='7'><h3 class='center'>Aucun résultat</h3></td></tr>";
}
while ($donnees= $reponse->fetch())
{
$id=$donnees['0'];					
$bailleur=$donnees['1'];					
$logement=$donnees['2'];					
$type_logement=$donnees['3'];					
$adresse=$donnees['4'];									
$pu=$donnees['5'];					
$nbr=$donnees['6'];					
$total=$donnees['7']+$donnees['8'];
$tlv = ($pu * 2)/100;
$tom = ($pu * 3.6)/100;					
echo "<tr>";
	echo "<td></td>";
	echo "<td>".$bailleur."</td>";
	echo "<td>".$logement."</td>";
	echo "<td>".$type_logement."</td>";
	echo "<td>".($total-$donnees['8'])."</td>";
	echo "<td>".$adresse."</td>";
	echo "<td>".number_format($pu,0,'.',' ')." Fcfa</td>";
	if (isset($_SESSION['id_locataire'])) 
	{	
		$id_locataire=$_SESSION['id_locataire'];
	echo "<td> <a class='btn' href='e_location1.php?id=$id&amp;id_locataire=$id_locataire'>Choisir<i class='material-icons right'>add</i></a></td>";
	}
	else
	{	
	echo "<td> <a class='btn' href='e_location1.php?id=$id'>Choisir<i class='material-icons right'>add</i></a></td>";
	}
echo "</tr>";}

?>
<script type="text/javascript">
	$('.tooltipped').tooltip();
</script>