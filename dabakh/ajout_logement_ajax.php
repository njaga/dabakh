<?php
include 'connexion.php';
include 'supprim_accents.php';
$req=$db->prepare('INSERT INTO logement (designation, adresse, pu, nbr, id_type, id_bailleur, nbr_occupe, etat) VALUES (?, ?, ?, ?, ?, ?, 0, "actif")');
$req->execute(array(strtoupper(suppr_accents($_POST['designation'])), strtoupper(suppr_accents($_POST['adresse'])), $_POST['pu'], $_POST['nbr'], $_POST['type_logement'], $_POST['id_bailleur']) ) or die(print_r($req->errorInfo()));
$req->closeCursor();

$req=$db->prepare("SELECT logement.id, logement.designation, type_logement.type_logement, logement.pu, logement.nbr, logement.adresse 
FROM logement, type_logement 
WHERE logement.id_type=type_logement.id AND logement.id_bailleur=?") or die(print_r($req->errorInfo()));
$req->execute(array($_POST['id_bailleur']));
$nbr=$req->rowCount();

while ($donnees=$req->fetch()) 
{
echo "<tr>";
	echo "<td>".$donnees['1']."</td>";
	echo "<td>".$donnees['2']."</td>";
	echo "<td>".$donnees['3']."</td>";
	echo "<td>".$donnees['4']."</td>";
	echo "<td>".$donnees['5']."</td>";
	if (!isset($_POST['p'])) 
	{
		echo "<td><a href='supprimer_logement_ajax.php?id=".$donnees['0']."'><i class='material-icons red-text'>clear</i></a></td>";
	}
echo "</tr>";		
}	

?>