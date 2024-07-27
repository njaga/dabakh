<?php		
session_start();			
include 'connexion.php';
include 'supprim_accents.php';
$jour_d=$_POST['jour_d'];
$jour_f=$_POST['jour_f'];
$mois=date("m", strtotime($jour_d));
$annee=date("Y", strtotime($jour_d));
$som_entree=0;
$som_sortie=0;
$som_solde=0;

$db->query("SET lc_time_names = 'fr_FR';");


//Dernier jour d'opération
/*$reponse=$db->prepare("SELECT CONCAT(DATE_FORMAT(MAX(date_operation), '%d'), '/', DATE_FORMAT(MAX(date_operation), '%m'),'/', DATE_FORMAT(MAX(date_operation), '%Y')),MAX(date_operation) 
FROM `banque`
WHERE date_operation<? AND structure=?");
$reponse->execute(array($jour_d, $_SESSION['service']));*/
//Pour toutes les banques
$reponse=$db->prepare("SELECT CONCAT(DATE_FORMAT(MAX(date_operation), '%d'), '/', DATE_FORMAT(MAX(date_operation), '%m'),'/', DATE_FORMAT(MAX(date_operation), '%Y')),MAX(date_operation) 
FROM `banque`
WHERE date_operation<?");
$reponse->execute(array($jour_d));
$donnees=$reponse->fetch();
$jour_lettre=$donnees['0'];
$annee_passe=date("Y", strtotime($donnees['1']));
$jour_chiffre=$donnees['1'];
$reponse->closeCursor();


//Solde du jour précédent

	//entree

/*$reponse=$db->prepare("SELECT COALESCE(SUM(montant),0) FROM banque WHERE  year(date_operation)=? AND date_operation<=? AND type='entree' AND structure=?");
$reponse->execute(array($annee_passe, $jour_chiffre, $_SESSION['service']));*/
//Pour toutes les banques
$reponse=$db->prepare("SELECT COALESCE(SUM(montant),0) FROM banque WHERE  year(date_operation)=? AND date_operation<=? AND type='entree' ");
$reponse->execute(array($annee_passe, $jour_chiffre));
$donnees=$reponse->fetch();
$entree=$donnees['0'];

/*$reponse=$db->prepare("SELECT COALESCE(SUM(montant),0) FROM banque WHERE  year(date_operation)=? AND date_operation<=? AND type='sortie' AND structure=?");
$reponse->execute(array($annee_passe, $jour_chiffre, $_SESSION['service']));*/
//Pour toutes les banques
$reponse=$db->prepare("SELECT COALESCE(SUM(montant),0) FROM banque WHERE  year(date_operation)=? AND date_operation<=? AND type='sortie'");
$reponse->execute(array($annee_passe, $jour_chiffre));
$donnees=$reponse->fetch();
$sortie=$donnees['0'];

$solde_jour_j=$entree-$sortie;


echo"<tr>";
    echo"<td class='center'colspan='4'> Solde du ".$jour_lettre."</td>";
    echo"<td class=''></td>";
    echo"<td class=''></td>";
    echo "<td class='right-align'>".number_format($solde_jour_j,0,'.',' ')." </td>";
echo"<tr>";
/*$req=$db->prepare("SELECT id, CONCAT(DATE_FORMAT(date_operation, '%d'), '/', DATE_FORMAT(date_operation, '%m'),'/', DATE_FORMAT(date_operation, '%Y')), motif, type, montant, section, num_cheque, id_mensualite_bailleur, id_depense_bailleur, id_user, pj
FROM `banque`
WHERE  date_operation BETWEEN ? AND ? AND structure=? AND type <>'solde' ORDER BY date_operation, id ASC,  section");
$req->execute(array($jour_d, $jour_f, $_SESSION['service']));*/
//pour toutes les banques
$req=$db->prepare("SELECT id, CONCAT(DATE_FORMAT(date_operation, '%d'), '/', DATE_FORMAT(date_operation, '%m'),'/', DATE_FORMAT(date_operation, '%Y')), motif, type, montant, section, num_cheque, id_mensualite_bailleur, id_depense_bailleur, id_user, pj
FROM `banque`
WHERE  date_operation BETWEEN ? AND ? AND type <>'solde' ORDER BY date_operation, id ASC,  section");
$req->execute(array($jour_d, $jour_f));
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
		$motif=ucfirst(strtolower(suppr_accents($donnees['2'])));
        $type=$donnees['3'];
		$montant=$donnees['4'];
		$section=$donnees['5'];
		$num_cheque=$donnees['6'];
		$id_mensualite_bailleur=$donnees['7'];
		$id_depense_bailleur=$donnees['8'];
		$id_user=$donnees['9'];
		$pj=$donnees['10'];
		if ($type=='sortie') 
		{
			$som_sortie=$som_sortie+$montant;			
			if ($_SESSION['service']=="immobilier") 
			{
				echo "<tr class='deep-orange lighten-4'>";	
			}
			else
			{
				echo "<tr class='pink accent-1'>";
			}
		
		}
		elseif ($type=='entree') 
		{
			$som_entree=$som_entree+$montant;		
			if ($_SESSION['service']=="immobilier") 
			{
				echo "<tr class='brown lighten-4'>";		
			}
			else
			{
				echo "<tr class='blue lighten-3'>";			
			}
			
		}
		
		if ($_SESSION['fonction']=='daf' or $_SESSION['fonction']=='administrateur') 
		{
			if (isset($id_depense_bailleur)) 
			{
				echo "<td> <a class='tooltipped' data-position='top' data-delay='50' data-tooltip='cliquez ici pour modifier' href='m_depense_bailleur.php?id=$id_depense_bailleur'> ".$date_operation. "</a> </td>";
			}
			elseif(isset($id_mensualite_bailleur))
			{
				echo "<td> <a class='tooltipped' data-position='top' data-delay='50' data-tooltip='Impossible de le modifier' > ".$date_operation. "</a> </td>";
			}
			else
			{
				echo "<td> <a class='tooltipped' data-position='top' data-delay='50' data-tooltip='cliquez ici pour modifier' href='m_banque.php?id=$id'> ".$date_operation. "</a> </td>";
			}

		}
		else
		{
			echo "<td class=''>".$date_operation."</td>";
		}
		
		//Affichage des pièces jointes
		if (isset($id_mensualite_bailleur)) 
			{
				echo "<td class=''><a href='i_mensualite_bailleur.php?id=".$id_mensualite_bailleur."' >N°".$id_mensualite_bailleur."</a></td>";
			}
		elseif (isset($id_depense_bailleur)) 
			{
				echo "<td><a  href='i_depense_bailleur.php?id=".$id_depense_bailleur."' >N°".$id_depense_bailleur."</a></td>";	
			}
			else
			{
				echo "<td> N°000</td>";	
			}

		echo "<td>".$num_cheque."</td>";
		echo "<td class='left-align'>".$motif."</td>";
		//calcul du solde
		if ($type=="entree") 
		{
			echo "<td class=''>".number_format($montant,0,'.',' ')." </td>";
			echo "<td></td>";
			$solde=$solde+$montant;
		}
		elseif ($type=='sortie') 
		{
			echo "<td></td>";	
			echo "<td class=''>".number_format($montant,0,'.',' ')." </td>";
			$solde=$solde-$montant;
		}
		else
		{
			echo "<td></td>";	
			echo "<td></td>";	
		}
		echo "<td class=''>".number_format($solde,0,'.',' ')." </td>";

			if ($_SESSION['fonction']=='administrateur')
			{
				if (isset($id_mensualite_bailleur)) 
					{
						echo "<td><a class='red btn' href='s_mensualite_bailleur.php?id=".$id_mensualite_bailleur."' onclick='return(confirm(\"Voulez-vous supprimer cette opération ?\"))'>Supprimer </a></td>";
					}
				elseif (isset($id_depense_bailleur)) 
					{
						echo "<td><a class='red btn' href='s_depense_bailleur.php?id=".$id_depense_bailleur."' onclick='return(confirm(\"Voulez-vous supprimer cette opération ?\"))'>Supprimer </a></td>";	
					}
				else
				{
					echo "<td><a class='red btn' href='supprimer_ligne_bancaire.php?id=".$id."' onclick='return(confirm(\"Voulez-vous supprimer cette opération ?\"))'>Supprimer </a></td>";	
				}
				echo "<td>".$id_user."</td>";
			}

	}
	$reponse->closeCursor();
	echo "</tr>";
	
	echo "<tr class=''>";
	echo "<td colspan='4' class='trait center-align'><b>TOTAL</b></td>";
	echo "<td class='trait right-align' ><b>".number_format($som_entree+$solde_jour_j,0,'.',' ')." </b></td>";
	echo "<td class='trait right-align' ><b>".number_format($som_sortie,0,'.',' ')." </b></td>";
	echo "<td class='trait right-align' ><b>".number_format(($solde_jour_j+$som_entree-$som_sortie),0,'.',' ')." </b></td>";
	echo "</tr>";	
}
else
{
	echo "<tr><td class='trait center' colspan='5'><h3>Aucune opération à cette date </td></tr>";
}			
?>
<style type="text/css">
	td
	{
	}
</style>
