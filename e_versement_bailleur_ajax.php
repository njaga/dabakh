<?php
include 'connexion.php';
$db->query("SET lc_time_names = 'fr_FR';");
if ($_POST['search']=="") 
{
	$req=$db->query("SELECT bailleur.id, CONCAT(bailleur.prenom,' ', bailleur.nom), bailleur.tel, bailleur.adresse, SUM(caisse_caution.montant) 
	FROM `caisse_caution` 
	INNER JOIN location ON caisse_caution.id_location=location.id
	INNER JOIN logement ON location.id_logement=logement.id
	INNER JOIN bailleur ON logement.id_bailleur=bailleur.id
	WHERE bailleur.etat='activer' AND id_versement IS NULL 
	GROUP BY bailleur.id");
}
else
{
	$req=$db->prepare("SELECT bailleur.id, CONCAT(bailleur.prenom,' ', bailleur.nom), bailleur.tel, bailleur.adresse, SUM(caisse_caution.montant) 
	FROM `caisse_caution` 
	INNER JOIN location ON caisse_caution.id_location=location.id
	INNER JOIN logement ON location.id_logement=logement.id
	INNER JOIN bailleur ON logement.id_bailleur=bailleur.id
	WHERE bailleur.etat='activer' AND id_versement IS NULL AND CONCAT (bailleur.prenom,' ',' ',bailleur.nom) like CONCAT('%', ?, '%')
	GROUP BY bailleur.id");	
	$req->execute(array($_POST['search']));
}
$resultat=$req->rowCount();
while ($donnees= $req->fetch())
{
	$id=$donnees['0'];
	$bailleur=$donnees['1'];						
	$telephone=$donnees['2'];
	$adresse=$donnees['3'];						
	$montant=$donnees['4'];	
	if($montant>0)
	{
		echo "<tr>";
			echo "<td>".$bailleur."</td>";
			echo "<td>".$telephone."</td>";
			echo "<td>".$adresse."</td>";
			echo "<td>".$montant."</td>";
			echo "<td><a href='e_versement_trmnt.php?id=".$id."&montant=".$montant."&b=".$bailleur."'  class='btn'>Selectionner</a></td>";
		echo "</tr>";
	}
}					

if ($resultat<1)
{
	echo "<h3 class='center'>Aucun résultat</h3>";
}
?>