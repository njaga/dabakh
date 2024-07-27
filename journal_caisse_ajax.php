<?php		
session_start();			
include 'connexion.php';
$mois=$_POST['mois'];
$db->query("SET lc_time_names = 'fr_FR';");
//ancien solde
$reponse=$db->prepare("SELECT  monthname(date_operation),(SUM(montant)-(SELECT SUM(montant) FROM `caisse` WHERE type='sortie' and month(date_operation)=? AND day(date_operation)=1)) 
FROM `caisse` WHERE type<>'sortie' and month(date_operation)=? AND day(date_operation)=1");
$reponse->execute(array($mois, $mois));
$donnees= $reponse->fetch();
$solde_p=$donnees['1'];
$mois_p=$donnees['0'];
$reponse->closeCursor();
//
echo "<tr>";
	echo "<td></td>";
	echo "<td>SOLDE DU MOIS ".$mois_p."</td>";
	echo "<td></td>";
	echo "<td></td>";
	echo "<td>".$solde_p."</td>";
echo "</tr>";
$reponse=$db->prepare("SELECT id_operation, CONCAT(day(date_operation), ' ', monthname(date_operation),' ', year(date_operation)), motif, type, montant, id_depense,section 
FROM `caisse`
WHERE month(date_operation)=? AND day(date_operation)=1");
$reponse->execute(array($mois));
$nbr=$reponse->rowCount();
if ($nbr>0) 
{
	$solde=0;
	while ($donnees= $reponse->fetch())
	{
		$id_caisse=$donnees['0'];
		$date_operation=$donnees['1'];
		$motif=$donnees['2'];
		$type=$donnees['3'];
		$montant=$donnees['4'];
		$id_depense=$donnees['5'];
		$section=$donnees['6'];
		if ($section=='Approvisionnement') {
			echo "<tr class='teal lighten-3'>";
		}
		else
		{

		echo "<tr>";
		}
		echo "<td>". $date_operation. "</td>";
		echo "<td>".$motif."</td>";
		if ($type=="entree") {
			$solde=$solde+$montant;
		echo "<td>".number_format($montant,0,'.',' ')." Fcfa</td>";
		echo "<td></td>";
		}
		elseif ($type=='sortie') 
		{
			$solde=$solde-$montant;
			echo "<td></td>";	
			echo "<td>".number_format($montant,0,'.',' ')." Fcfa</td>";
		}
		
		echo "<td>".number_format($solde,0,'.',' ')." Fcfa</td>";
		if ($_SESSION['poste']=='administration')
		{
			echo "<td><a class='red btn' href='supprimer_ligne_caisse.php?id_caisse=".$id_caisse."&amp;id_depense=$id_depense' onclick='return(confirm(\"Voulez-vous supprimer cette opération ?\"))'>Supprimer </a></td>";
		}

	}
	$reponse->closeCursor();
	echo "</tr>";
	$req=$db->prepare("SELECT SUM(montant) 
		FROM `caisse` WHERE type='entree' AND month(date_operation)=?");
	$req->execute(array($mois));
	$donnees= $req->fetch();
	$som_entree=$donnees['0'];
	$req->closeCursor();
	$req=$db->prepare('SELECT SUM(montant) 
		FROM `caisse` WHERE type="sortie" AND month(date_operation)=?');
	$req->execute(array($mois));
	$donnees=$req->fetch();
	$som_sortie=$donnees['0'];
	$req->closeCursor();
	$req=$db->prepare('SELECT SUM(montant) 
		FROM `caisse` WHERE type="solde" AND month(date_operation)=?');
	$req->execute(array($mois));
	$donnees=$req->fetch();
	$som_solde=$donnees['0'];
	$req->closeCursor();
	echo "<tr class='teal darken-3 white-text'>";
	echo "<td colspan='2'><b>TOTAL</b></td>";
	echo "<td><b>".number_format($som_entree,0,'.',' ')." Fcfa</b></td>";
	echo "<td><b>".number_format($som_sortie,0,'.',' ')." Fcfa</b></td>";
	echo "<td><b>".number_format(($som_solde+$som_entree-$som_sortie),0,'.',' ')." Fcfa</b></td>";
	echo "</tr>";	
}
else
{
	echo "<tr><td></td><td></td><td></td><td><h3>Aucune opération ce mois ci </td></tr>";
}			
?>