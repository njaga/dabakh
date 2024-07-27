<?php
include 'connexion.php';
$db->query("SET lc_time_names = 'fr_FR';");
if ($_POST['search']=="") 
{
	$req=$db->query('SELECT  location.id, CONCAT(type_logement.type_logement," : ",logement.designation), logement.adresse, CONCAT(day(location.date_debut)," ",monthname(location.date_debut)," ",year(location.date_debut)), logement.pu, CONCAT(bailleur.prenom," ",bailleur.nom), CONCAT(locataire.prenom," ", locataire.nom), location.caution, locataire.num_dossier, locataire.annee_inscription
		FROM `location`, logement, bailleur, locataire, type_logement
		WHERE logement.id_bailleur=bailleur.id AND location.id_logement=logement.id AND location.id_locataire=locataire.id AND logement.id_type=type_logement.id AND location.etat="active"
		ORDER BY location.date_debut DESC');
}
else
{
	$req=$db->prepare('SELECT  location.id, CONCAT(type_logement.type_logement," : ",logement.designation), logement.adresse, CONCAT(day(location.date_debut)," ",monthname(location.date_debut)," ",year(location.date_debut)), logement.pu, CONCAT(bailleur.prenom," ",bailleur.nom), CONCAT(locataire.prenom," ", locataire.nom), location.caution, locataire.num_dossier, locataire.annee_inscription
		FROM `location`, logement, bailleur, locataire, type_logement
		WHERE logement.id_bailleur=bailleur.id AND location.id_logement=logement.id AND location.id_locataire=locataire.id AND logement.id_type=type_logement.id AND location.etat="active" AND CONCAT (locataire.prenom," "," ",locataire.nom) like CONCAT("%", ?, "%")
		ORDER BY location.date_debut DESC');	
	$req->execute(array($_POST['search']));
}
$resultat=$req->rowCount();
while ($donnees= $req->fetch())
{
$id=$donnees['0'];
$designation=$donnees['1'];						
$adresse=$donnees['2'];						
$date_debut=$donnees['3'];
$montant_mensuel=$donnees['4'];
$bailleur=$donnees['5'];
$locataire=$donnees['6'];						
$caution=$donnees['7'];						
$num_dossier=$donnees['8'];						
$annee_inscription=$donnees['9'];						
echo "<tr>";
	//echo "<td> <a class='tooltipped' data-position='top' data-delay='50' data-tooltip='cliquez ici pour modifier' href='m_location.php?id=$id'><i class='material-icons'>edit</i></a></td>";
	echo "<td>".str_pad($num_dossier, 3,"0", STR_PAD_LEFT)."/".substr($annee_inscription, -2)."</td>";
	echo "<td>".$locataire."</td>";
	echo "<td>".$designation." à ".$adresse."</td>";
	echo "<td>".$bailleur."</td>";
	echo "<td>".$date_debut."</td>";
	echo "<td>".number_format($montant_mensuel,0,'.',' ')." Fcfa</td>";
	echo "<td><a href='e_mensualite1.php?id=".$id."'  class='btn'>Selectionner</a></td>";
echo "</tr>";}

if ($resultat<1)
{
	echo "<h3 class='center'>Aucun résultat</h3>";
}
?>