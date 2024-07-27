<?php		
session_start();			
include 'connexion.php';
include 'supprim_accents.php';
$mois=$_POST['mois'];
$annee=$_POST['annee'];
$som_entree=0;
$som_sortie=0;
$som_solde=0;

$structure="btp";
$db->query("SET lc_time_names = 'fr_FR';");

if ($mois==1) 
{
	//Solde su mois précédent
	$date_dernier_jour=$annee.'-'.$mois.'-01';
	$reponse=$db->prepare("SELECT (SELECT COALESCE(SUM(montant),0) FROM banque WHERE type='entree' AND date_operation<? AND structure=?)  - (SELECT COALESCE(SUM(montant),0) FROM banque WHERE type='sortie' AND date_operation<? AND structure=?)");
	$reponse->execute(array($date_dernier_jour, $structure, $date_dernier_jour, $structure));
	$donnees=$reponse->fetch();
	$solde=$donnees['0'];
	$solde_init=$donnees['0'];	
	$mois_precedent="Décembre ".($annee-1);
}
else
{
	//Solde su mois précédent
	$date_dernier_jour=$annee.'-'.$mois.'-01';
	$reponse=$db->prepare("SELECT (SELECT COALESCE(SUM(montant),0) FROM banque WHERE type='entree' AND date_operation<? AND structure=?)  - (SELECT COALESCE(SUM(montant),0) FROM banque WHERE type='sortie' AND date_operation<? AND structure=?)");
	$reponse->execute(array($date_dernier_jour, $structure, $date_dernier_jour, $structure));
	$donnees=$reponse->fetch();
	$solde=$donnees['0'];
	$solde_init=$donnees['0'];
	$list_mois = array("","Janvier","Février","Mars","Avril","Mai","Juin","Juillet","Août","Septembre","Octobre","Novembre","Décembre");
	$mois_precedent=$list_mois[($mois-1)];
}

//Affichege du solde du mois précédent
echo "<tr>";
	echo "<td colspan='3' class='center'><b>Solde du mois de ".ucfirst($mois_precedent)."</b></td>";
	echo "<td></td>";
	echo "<td></td>";
	echo "<td class='right'>".number_format($solde,0,'.',' ')."</td>";
echo "</tr>";

$reponse=$db->prepare("SELECT id, CONCAT(day(date_operation), '/', month(date_operation),'/', year(date_operation)), motif, type, montant, section, num_cheque, id_mensualite_bailleur, id_depense_bailleur, id_user, pj
FROM banque
WHERE type<>'solde' AND month(date_operation)=? AND year(date_operation)=? AND structure=? ORDER BY date_operation, id  ASC");
$reponse->execute(array($mois, $annee, $structure));
$nbr=$reponse->rowCount();
if ($nbr>0) 
{
	while ($donnees= $reponse->fetch())
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
		
		if ($_SESSION['fonction']=='administrateur') 
		{
		
				echo "<td> <a class='tooltipped' data-position='top' data-delay='50' data-tooltip='cliquez ici pour modifier' href='m_banque.php?id=$id'> ".$date_operation. "</a> </td>";
			

		}
		else
		{
			echo "<td class='center'>".$date_operation."</td>";
		}
		
		//Affichage des pièces jointes
		
            echo "<td> N°".str_pad($pj, 3, "0", STR_PAD_LEFT)."</td>";	

		echo "<td>".$num_cheque."</td>";
		echo "<td>".$motif."</td>";
		//calcul du solde
		if ($type=="entree") 
		{
			echo "<td class='right-align'>".number_format($montant,0,'.',' ')." </td>";
			echo "<td></td>";
			$solde=$solde+$montant;
		}
		elseif ($type=='sortie') 
		{
			echo "<td></td>";	
			echo "<td class='right-align'>".number_format($montant,0,'.',' ')." </td>";
			$solde=$solde-$montant;
		}
		else
		{
			echo "<td></td>";	
			echo "<td></td>";	
		}
		echo "<td class='right-align'>".number_format($solde,0,'.',' ')." </td>";

			if ($_SESSION['fonction']=='administrateur')
			{
                echo "<td><a class='red btn' href='supprimer_ligne_bancaire.php?id=".$id."' onclick='return(confirm(\"Voulez-vous supprimer cette opération ?\"))'>Supprimer </a></td>";	
				echo "<td>".$id_user."</td>";
			}

	}
	$reponse->closeCursor();
	//Total et solde
	echo "</tr>";
	
	echo "<tr class='white darken-3 '>";
	echo "<td colspan='4'><b>TOTAL</b></td>";
	echo "<td class='right-align'><b>".number_format($som_entree,0,'.',' ')." </b></td>";
	echo "<td class='right-align'><b>".number_format($som_sortie,0,'.',' ')." </b></td>";
	echo "<td class='right-align'><b>".number_format(($solde_init+$som_entree-$som_sortie),0,'.',' ')." </b></td>";
	echo "</tr>";	
}
else
{
	echo "<tr><td colspan='6' class='center'><h3>Aucune opération ce mois ci </td></tr>";
}			
?>
<script type="text/javascript">
	$('.tooltipped').tooltip();
</script>
<style type="text/css">
	td {
    font: 14pt "times new roman";
        
    }
</style>