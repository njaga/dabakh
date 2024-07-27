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
		$reponse=$db->prepare("SELECT cotisation_locataire.id, CONCAT(day(cotisation_locataire.date_depense), ' ', monthname(cotisation_locataire.date_depense),' ', year(cotisation_locataire.date_depense)), CONCAT(locataire.prenom,' ', locataire.nom), cotisation_locataire.type_depense, cotisation_locataire.motif, cotisation_locataire.montant
		FROM cotisation_locataire, locataire
		WHERE locataire.id=cotisation_locataire.id_locataire AND cotisation_locataire.annee=? AND cotisation_locataire.mois=? ORDER BY cotisation_locataire.date_depense ASC");
		$reponse->execute(array($annee, $mois));	
	}
	else
	{
		$reponse=$db->prepare("SELECT cotisation_locataire.id, CONCAT(day(cotisation_locataire.date_depense), ' ', monthname(cotisation_locataire.date_depense),' ', year(cotisation_locataire.date_depense)), CONCAT(locataire.prenom,' ', locataire.nom), cotisation_locataire.type_depense, cotisation_locataire.motif, cotisation_locataire.montant
		FROM cotisation_locataire, locataire, location, bailleur, logement
		WHERE locataire.id=cotisation_locataire.id_locataire AND locataire.id=location.id_locataire AND location.id_logement=logement.id AND logement.id_bailleur=bailleur.id AND cotisation_locataire.annee=? AND cotisation_locataire.mois=?  AND bailleur.id=? ORDER BY cotisation_locataire.date_depense ASC");
		$reponse->execute(array($annee, $mois, $bailleur));
	}
}
else
{
	if ($bailleur==0) 
	{
		$reponse=$db->prepare("SELECT cotisation_locataire.id, CONCAT(day(cotisation_locataire.date_depense), ' ', monthname(cotisation_locataire.date_depense),' ', year(cotisation_locataire.date_depense)), CONCAT(locataire.prenom,' ', locataire.nom), cotisation_locataire.type_depense, cotisation_locataire.motif, cotisation_locataire.montant
		FROM cotisation_locataire, locataire
		WHERE locataire.id=cotisation_locataire.id_locataire AND cotisation_locataire.annee=? AND cotisation_locataire.mois=? AND CONCAT (locataire.prenom,' ',' ',locataire.nom) like CONCAT('%', ?, '%') ORDER BY cotisation_locataire.date_depense ASC");
		$reponse->execute(array($annee, $mois, $search));
	}
	else
	{
		$reponse=$db->prepare("SELECT cotisation_locataire.id, CONCAT(day(cotisation_locataire.date_depense), ' ', monthname(cotisation_locataire.date_depense),' ', year(cotisation_locataire.date_depense)), CONCAT(locataire.prenom,' ', locataire.nom), cotisation_locataire.type_depense, cotisation_locataire.motif, cotisation_locataire.montant
		FROM cotisation_locataire, locataire, location, bailleur, logement
		WHERE locataire.id=cotisation_locataire.id_locataire AND locataire.id=location.id_locataire AND location.id_logement=logement.id AND logement.id_bailleur=bailleur.id AND cotisation_locataire.annee=? AND cotisation_locataire.mois=? AND CONCAT (locataire.prenom,' ',' ',locataire.nom) like CONCAT('%', ?, '%') AND bailleur.id=? ORDER BY cotisation_locataire.date_depense ASC");
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
		echo "<td> <a class='tooltipped' data-position='top' data-delay='50' data-tooltip='cliquez ici pour modifier' href='m_cotisation_locataire.php?id=$id'>".$date_depense."</a></td>";
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
		echo "<td> <a onclick='return(confirm(\"Voulez-vous supprimer cette dépense ?\"))' class='red btn' href='s_cotisation_locataire.php?id=$id'>Supprimer</a></td>";
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