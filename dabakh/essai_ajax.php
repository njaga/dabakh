<?php	
session_start();				
include 'connexion.php';
$mois=$_POST['mois'];
$db->query("SET lc_time_names = 'fr_FR';");
$reponse=$db->prepare("SELECT mensualite.id, CONCAT(locataire.prenom,' ', locataire.nom), CONCAT(logement.designation,' à ',logement.adresse), mensualite.montant, mensualite.mois, CONCAT(day(mensualite.date_versement), ' ', monthname(mensualite.date_versement),' ', year(mensualite.date_versement)), CONCAT(bailleur.prenom,' ', bailleur.nom)  
FROM `mensualite`,logement, locataire, location, bailleur 
WHERE location.id_locataire=locataire.id AND location.id_logement=logement.id AND location.id=mensualite.id_location AND month(mensualite.date_versement)=?");
$reponse->execute(array($mois));
$nbr=$reponse->rowCount();
if ($nbr>0) 
{
	while ($donnees= $reponse->fetch())
	{
		$id=$donnees['0'];
		$locataire=$donnees['1'];
		$logement=$donnees['2'];
		$montant=$donnees['3'];
		$mois=$donnees['4'];
		$date_versement=$donnees['5'];
		$bailleur=$donnees['6'];
		echo "<tr>";
		echo "<td>".$mois."</td>";
		echo "<td>".$locataire. "</td>";
		echo "<td>".$logement."</td>";
		echo "<td>".$bailleur."</td>";
		echo "<td>".number_format($montant,0,'.',' ')." Fcfa</td>";
		echo "<td>".$date_versement."</td>";
		echo "<td> <a class='btn' href='i_mensualite.php?id=$id'><i class='material-icons left'>print</i> Facture</a> </td>";
		echo "</tr>";}
}
else
{
	echo "<tr><td></td><td></td><td></td><td><h3>Aucun payement ce mois ci </td></tr>";
}			
?>