<?php		
session_start();			
include 'connexion.php';
$mois=$_POST['mois'];
$annee=$_POST['annee'];
$jour=$_POST['jour'];

$db->query("SET lc_time_names = 'fr_FR';");
//Solde du jour précédent
$reponse=$db->prepare("SELECT 
(SELECT SUM(montant) FROM caisse_sante WHERE month(date_operation)=? AND year(date_operation)=? AND date_operation<? AND type='solde')+
(SELECT SUM(montant) FROM `caisse_sante` WHERE month(date_operation)=? AND year(date_operation)=? AND date_operation<? AND type='entree')-
(SELECT SUM(montant) FROM `caisse_sante` WHERE month(date_operation)=? AND year(date_operation)=? AND date_operation<? AND type='sortie')");
$reponse->execute(array($mois, $annee, $jour, $mois, $annee, $jour, $mois, $annee, $jour));
$donnees=$reponse->fetch();
$solde_jour_j=$donnees['0'];

//Jour précédent
$reponse=$db->prepare("SELECT CONCAT(day(MAX(date_operation)), ' ', monthname(MAX(date_operation)),' ', year(MAX(date_operation))) 
FROM `caisse_sante`
WHERE date_operation<?");
$reponse->execute(array($jour));
$donnees=$reponse->fetch();
$jour_lettre=$donnees['0'];
$reponse->closeCursor();

echo"<tr>";
    echo"<td class='trait'></td>";    
    echo"<td class='trait'> Solde du ".$jour_lettre."</td>";
    echo"<td class='trait'></td>";
    echo"<td class='trait'></td>";
    echo "<td class='trait'>".number_format($solde_jour_j,0,'.',' ')." Fcfa</td>";
echo"<tr>";
    
$reponse=$db->prepare("SELECT id_operation, CONCAT(day(date_operation), ' ', monthname(date_operation),' ', year(date_operation)), motif, type, montant, id_consultation,section, id_patient_externe, id_consultation_domicile
FROM `caisse_sante`
WHERE month(date_operation)=? AND year(date_operation)=? AND date_operation=? ORDER BY date_operation ASC,  section");
$reponse->execute(array($mois, $annee, $jour));
$nbr=$reponse->rowCount();
if ($nbr>0) 
{
	$solde=$solde_jour_j;
	$entree=0;
	$sortie=0;
	while ($donnees= $reponse->fetch())
	{
		$id_caisse=$donnees['0'];
		$date_operation=$donnees['1'];
		$motif=$donnees['2'];
        $type=$donnees['3'];
		$montant=$donnees['4'];
		$id_consultation=$donnees['5'];
		$section=$donnees['6'];
		$id_patient_externe=$donnees['7'];
		$id_consultation_domicile=$donnees['8'];
		if ($type=='entree') 
		{
			echo "<tr class='blue lighten-3'>";
		}
		elseif($type=='sortie')
		{
			echo "<tr class=' pink accent-1'>";
		}
		else
		{
			echo "<tr>";
		}
		echo "<td>". $date_operation. "</td>";
		echo "<td>".$motif."</td>";
		if ($type=="entree") 
		{
			$solde=$solde+$montant;
			$entree=$entree+$montant;		
		echo "<td>".number_format($montant,0,'.',' ')." Fcfa</td>";
		echo "<td></td>";
		}
		elseif ($type=='sortie') 
		{
			$solde=$solde-$montant;
			$sortie=$sortie+$montant;
			echo "<td></td>";	
			echo "<td>".number_format($montant,0,'.',' ')." Fcfa</td>";
		}
		else
		{
			$solde=$solde+$montant;
			echo "<td></td>";	
			echo "<td></td>";	
		}
		echo "<td>".number_format($solde,0,'.',' ')." Fcfa</td>";
		echo "<td></td>";
	}
	$reponse->closeCursor();
	echo "</tr>";
	$req=$db->prepare("SELECT SUM(montant) 
		FROM `caisse_sante` WHERE type='entree' AND month(date_operation)=?");
	$req->execute(array($mois));
	$donnees= $req->fetch();
	$som_entree=$donnees['0'];
	$req->closeCursor();
	$req=$db->prepare('SELECT SUM(montant) 
		FROM `caisse_sante` WHERE type="sortie" AND month(date_operation)=?');
	$req->execute(array($mois));
	$donnees=$req->fetch();
	$som_sortie=$donnees['0'];
	$req->closeCursor();
	$req=$db->prepare('SELECT SUM(montant) 
		FROM `caisse_sante` WHERE type="solde" AND month(date_operation)=?');
	$req->execute(array($mois));
	$donnees=$req->fetch();
	$som_solde=$donnees['0'];
	$req->closeCursor();
	echo "<tr class=''>";
	echo "<td colspan='2' class='trait'><b>TOTAL</b></td>";
	echo "<td class='trait'><b>".number_format($entree,0,'.',' ')." Fcfa</b></td>";
	echo "<td class='trait'><b>".number_format($sortie,0,'.',' ')." Fcfa</b></td>";
	echo "<td class='trait'><b>".number_format(($solde),0,'.',' ')." Fcfa</b></td>";
	echo "</tr>";	
}
else
{
	echo "<tr><td class='trait'></td><td class='trait'></td><td class='trait'></td><td class='trait'><h3>Aucune opération ce mois ci </td></tr>";
}			
?>
