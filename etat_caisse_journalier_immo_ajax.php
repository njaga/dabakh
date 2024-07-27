<?php		
session_start();			
include 'connexion.php';
$jour_d=$_POST['jour_d'];
$jour_f=$_POST['jour_f'];
$mois=date("m", strtotime($jour_d));
$annee=date("Y", strtotime($jour_d));
$som_entree=0;
$som_sortie=0;
$som_solde=0;

$db->query("SET lc_time_names = 'fr_FR';");

//Dernier jour d'opération
$reponse=$db->prepare("SELECT CONCAT(DATE_FORMAT(MAX(date_operation), '%d'), '/', DATE_FORMAT(MAX(date_operation), '%m'),'/', DATE_FORMAT(MAX(date_operation), '%Y')),MAX(date_operation) 
FROM `caisse_immo`
WHERE date_operation<?");
$reponse->execute(array($jour_d));
$donnees=$reponse->fetch();
$jour_lettre=$donnees['0'];
$annee_passe=date("Y", strtotime($donnees['1']));
$jour_chiffre=$donnees['1'];
$reponse->closeCursor();
///Solde du jour précédent

	//entree
$reponse=$db->prepare("SELECT COALESCE(SUM(montant),0) FROM caisse_immo WHERE  date_operation<=? AND type='entree'");
$reponse->execute(array($jour_chiffre));
$donnees=$reponse->fetch();
$entree=$donnees['0'];
	//sortie
$reponse=$db->prepare("SELECT COALESCE(SUM(montant),0) FROM caisse_immo WHERE  date_operation<=? AND type='sortie'");
$reponse->execute(array( $jour_chiffre));
$donnees=$reponse->fetch();
$sortie=$donnees['0'];


$solde_jour_j=$entree-$sortie;


echo"<tr>";
    echo"<td class='center' colspan='3'> Solde du ".$jour_lettre."</td>";
    echo"<td class=''></td>";
    echo"<td class=''></td>";
    echo "<td class='right-align'>".number_format($solde_jour_j,0,'.',' ')." </td>";
echo"<tr>";
    
$req=$db->prepare("SELECT id, CONCAT(DATE_FORMAT(date_operation, '%d'), '/', DATE_FORMAT(date_operation, '%m'),'/', DATE_FORMAT(date_operation, '%Y')), motif, type, montant, id_mensualite,section, id_mensualite_bailleur, id_depense_bailleur, id_user, id_cotisation_locataire,pj, id_location
FROM `caisse_immo`
WHERE   type<>'solde' AND date_operation BETWEEN ? AND ? ORDER BY date_operation, id ASC,  section");
$req->execute(array( $jour_d, $jour_f));
$nbr=$req->rowCount();
if ($nbr>0) 
{
	$solde=$solde_jour_j;
	$entree=0;
	$sortie=0;
	while ($donnees= $req->fetch())
	{
		$id=$donnees['0'];
			$date_operation=$donnees['1'];
			$motif=$donnees['2'];
	        $type=$donnees['3'];
			$montant=$donnees['4'];
			$id_mensualite=$donnees['5'];
			$section=$donnees['6'];
			$id_mensualite_bailleur=$donnees['7'];
			$id_depense_bailleur=$donnees['8'];
			$id_user=$donnees['9'];
			$id_depense_locataire=$donnees['10'];
			$pj=$donnees['11'];
			$id_location=$donnees['12'];
		if ($type=='entree') 
		{
			echo "<tr class='brown lighten-4'>";
			$som_entree=$som_entree+$montant;
		}
		elseif($type=='sortie')
		{
			echo "<tr class=' deep-orange lighten-4'>";
			$som_sortie=$som_sortie+$montant;
		}
		else
		{
		//	echo "<tr>";
		}
		echo "<td>". $date_operation. "</td>";
		
		//Affichage des pièces jointes
		if (isset($id_mensualite)) 
			{
				echo "<td class='center'>N°".str_pad($id_mensualite, 3, "0", STR_PAD_LEFT)."</td>";	
			}
		elseif (isset($id_depense_bailleur)) 
			{
				echo "<td class='center'>N°".str_pad($id_depense_bailleur, 3, "0", STR_PAD_LEFT)."</td>";						
			}
		elseif (isset($id_mensualite_bailleur)) 
			{
				echo "<td class='center'>N°".str_pad($id_mensualite_bailleur, 3, "0", STR_PAD_LEFT)."</td>";	
			}
			elseif (isset($id_location)) 
			{
				echo "<td class='center'>N°".str_pad($id_location, 3, "0", STR_PAD_LEFT)."</td>";	
			}
		else
			{
				if ($section<>"solde") 
				{
					echo "<td class='center'>N°".str_pad($pj, 3, "0", STR_PAD_LEFT)." </td>";
				}
				else
				{
					echo "<td></td>";

				}
				
			}
		echo "<td>".$motif."</td>";
		if ($type=="entree") 
		{
			$solde=$solde+$montant;
			$entree=$entree+$montant;		
		echo "<td class='right-align'>".number_format($montant,0,'.',' ')." </td>";
		echo "<td></td>";
		}
		elseif ($type=='sortie') 
		{
			$solde=$solde-$montant;
			$sortie=$sortie+$montant;
			echo "<td></td>";	
			echo "<td class='right-align'>".number_format($montant,0,'.',' ')." </td>";
		}
		else
		{
			$solde=$solde+$montant;
			echo "<td></td>";	
			echo "<td></td>";	
		}
		echo "<td class='right-align'>".number_format($solde,0,'.',' ')." </td>";
	}
	$reponse->closeCursor();
	echo "</tr>";
	$req=$db->prepare("SELECT SUM(montant) 
		FROM `caisse_immo` WHERE type='entree' AND month(date_operation)=?");
	$req->execute(array($mois));
	$donnees= $req->fetch();
	$som_entree=$donnees['0'];
	$req->closeCursor();
	$req=$db->prepare('SELECT SUM(montant) 
		FROM `caisse_immo` WHERE type="sortie" AND month(date_operation)=?');
	$req->execute(array($mois));
	$donnees=$req->fetch();
	$som_sortie=$donnees['0'];
	$req->closeCursor();
	$req=$db->prepare('SELECT SUM(montant) 
		FROM `caisse_immo` WHERE type="solde" AND month(date_operation)=?');
	$req->execute(array($mois));
	$donnees=$req->fetch();
	$som_solde=$donnees['0'];
	$req->closeCursor();
	echo "<tr class=''>";
	echo "<td colspan='3' class='trait'><b>TOTAL</b></td>";
	echo "<td class='trait right-align'><b>".number_format($entree,0,'.',' ')." </b></td>";
	echo "<td class='trait right-align'><b>".number_format($sortie,0,'.',' ')." </b></td>";
	echo "<td class='trait right-align'><b>".number_format(($solde),0,'.',' ')." </b></td>";
	echo "</tr>";	
}
else
{
	echo "<tr><td class='trait center' colspan='5'><h3>Aucune opération à cette date </td></tr>";
}			
?>
