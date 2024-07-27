<?php
session_start();
include 'connexion.php';
$db->query("SET lc_time_names = 'fr_FR';");

$mois=$_POST['mois'];
$annee=$_POST['annee'];
$search=$_POST['search'];
$bailleur=$_POST['bailleur'];
$total=0;
if ($search=="")
{
	if ($bailleur==0) 
	{
		$reponse=$db->prepare("SELECT depense_locataire.id, CONCAT(day(depense_locataire.date_depense), ' ', monthname(depense_locataire.date_depense),' ', year(depense_locataire.date_depense)), CONCAT(locataire.prenom,' ', locataire.nom), depense_locataire.type_depense, depense_locataire.motif, depense_locataire.montant
		FROM depense_locataire, locataire
		WHERE locataire.id=depense_locataire.id_locataire AND depense_locataire.annee=? AND depense_locataire.mois=? ORDER BY depense_locataire.date_depense ASC");
		$reponse->execute(array($annee, $mois));	
	}
	else
	{
		$reponse=$db->prepare("SELECT depense_locataire.id, CONCAT(day(depense_locataire.date_depense), ' ', monthname(depense_locataire.date_depense),' ', year(depense_locataire.date_depense)), CONCAT(locataire.prenom,' ', locataire.nom), depense_locataire.type_depense, depense_locataire.motif, depense_locataire.montant
		FROM depense_locataire, locataire, location, bailleur, logement
		WHERE locataire.id=depense_locataire.id_locataire AND locataire.id=location.id_locataire AND location.id_logement=logement.id AND logement.id_bailleur=bailleur.id AND depense_locataire.annee=? AND depense_locataire.mois=?  AND bailleur.id=? ORDER BY depense_locataire.date_depense ASC");
		$reponse->execute(array($annee, $mois, $bailleur));
	}
}
else
{
	if ($bailleur==0) 
	{
		$reponse=$db->prepare("SELECT depense_locataire.id, CONCAT(day(depense_locataire.date_depense), ' ', monthname(depense_locataire.date_depense),' ', year(depense_locataire.date_depense)), CONCAT(locataire.prenom,' ', locataire.nom), depense_locataire.type_depense, depense_locataire.motif, depense_locataire.montant
		FROM depense_locataire, locataire
		WHERE locataire.id=depense_locataire.id_locataire AND depense_locataire.annee=? AND depense_locataire.mois=? AND CONCAT (locataire.prenom,' ',' ',locataire.nom) like CONCAT('%', ?, '%') ORDER BY depense_locataire.date_depense ASC");
		$reponse->execute(array($annee, $mois, $search));
	}
	else
	{
		$reponse=$db->prepare("SELECT depense_locataire.id, CONCAT(day(depense_locataire.date_depense), ' ', monthname(depense_locataire.date_depense),' ', year(depense_locataire.date_depense)), CONCAT(locataire.prenom,' ', locataire.nom), depense_locataire.type_depense, depense_locataire.motif, depense_locataire.montant
		FROM depense_locataire, locataire, location, bailleur, logement
		WHERE locataire.id=depense_locataire.id_locataire AND locataire.id=location.id_locataire AND location.id_logement=logement.id AND logement.id_bailleur=bailleur.id AND depense_locataire.annee=? AND depense_locataire.mois=? AND CONCAT (locataire.prenom,' ',' ',locataire.nom) like CONCAT('%', ?, '%') AND bailleur.id=? ORDER BY depense_locataire.date_depense ASC");
		$reponse->execute(array($annee, $mois, $search, $bailleur));
	}
}
$resultat=$reponse->rowCount();
if ($resultat<1)
{
	echo "<tr><td colspan='7'><h3 class='center'>Aucun résultat</h3></td></tr>";
}
$i=0;
while ($donnees= $reponse->fetch())
{
$id=$donnees['0'];
$date_depense=$donnees['1'];
$locataire=$donnees['2'];
$type_depense=$donnees['3'];
$motif=$donnees['4'];
$montant=$donnees['5'];
$total=$total+$montant;
$i++;
echo "<tr>";
	echo "<td class='grey lighten-3'><b>".$i. "</b></td>";
	if ($_SESSION['fonction']=="daf" or $_SESSION['fonction']=="administrateur") 
	{										
		echo "<td> <a class='tooltipped' data-position='top' data-delay='50' data-tooltip='cliquez ici pour modifier' href='m_depense_locataire.php?id=$id'>".$date_depense."</a></td>";
	}
	else
	{
		echo "<td>".$date_depense."</td>";		
	}
	echo "<td>".$locataire."</td>";
	echo "<td>".strtoupper($type_depense)."</td>";
	echo "<td>".$motif."</td>";
	echo "<td>".number_format($montant,0,'.',' ')." Fcfa</td>";
	if ($_SESSION['fonction']=="administrateur") 
	{
		echo "<td> <a onclick='return(confirm(\"Voulez-vous supprimer cette dépense ?\"))' class='red btn' href='s_depense_locataire.php?id=$id'>Supprimer</a></td>";
	}
echo "</tr>";}
echo "<tr class=''>";
echo "<td colspan='4' class='trait'><b>TOTAL</b></td>";
echo "<td class='trait right-align'><b>".number_format($total,0,'.',' ')." </b></td>";

echo "</tr>";	

?>
<script type="text/javascript">
	$('.tooltipped').tooltip();
</script>