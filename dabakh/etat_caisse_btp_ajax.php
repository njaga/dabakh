<?php		
session_start();			
include 'connexion.php';
$mois=$_POST['mois'];
$annee=$_POST['annee'];
$search=$_POST['search'];
$db->query("SET lc_time_names = 'fr_FR';");
$som_entree=0;
$som_sortie=0;
$som_solde=0;
$date_actuelle=date("Y-m-d");

if ($mois==1) 
{
	//Solde su mois précédent
	$date_dernier_jour=$annee.'-'.$mois.'-01';
	$reponse=$db->prepare("SELECT (SELECT COALESCE(SUM(montant),0) FROM caisse_btp WHERE type='entree' AND date_operation<?)  - (SELECT COALESCE(SUM(montant),0) FROM caisse_btp WHERE type='sortie' AND  date_operation<?)");
	$reponse->execute(array($date_dernier_jour, $date_dernier_jour));
	$donnees=$reponse->fetch();
	$solde=$donnees['0'];
	$solde_init=$donnees['0'];	
	$mois_precedent="Décembre ".($annee-1);
}
else
{
	//Solde su mois précédent
	$date_dernier_jour=$annee.'-'.$mois.'-01';
	$reponse=$db->prepare("SELECT (SELECT COALESCE(SUM(montant),0) FROM caisse_btp WHERE type='entree' AND date_operation<?)  - (SELECT COALESCE(SUM(montant),0) FROM caisse_btp WHERE type='sortie' AND  date_operation<?)");
	$reponse->execute(array($date_dernier_jour, $date_dernier_jour));
	$donnees=$reponse->fetch();
	$solde=$donnees['0'];
	$solde_init=$donnees['0'];
	//mois précédent
	$list_mois = array("","Janvier","Février","Mars","Avril","Mai","Juin","Juillet","Août","Septembre","Octobre","Novembre","Décembre");
	$mois_precedent=$list_mois[($mois-1)];
}

if ($search=="") 
{
	echo "<tr>";
		echo "<td colspan='3' class='center'><b>Solde du mois de ".ucfirst($mois_precedent)."</b></td>";
		echo "<td></td>";
		echo "<td></td>";
		echo "<td class='right'>".number_format($solde,0,'.',' ')."</td>";
	echo "</tr>";
	$reponse=$db->prepare("SELECT caisse_btp.id, CONCAT(DATE_FORMAT(date_operation, '%d'), '/', DATE_FORMAT(date_operation, '%m'),'/', DATE_FORMAT(date_operation, '%Y')), motif, type, montant, section, id_user, pj_caisse.chemin, pj, date_operation
	FROM `caisse_btp`
	LEFT JOIN pj_caisse ON caisse_btp.id=pj_caisse.id_caisse_btp
	WHERE type<>'solde' AND month(date_operation)=? AND year(date_operation)=? ORDER BY date_operation, id ASC");
	$reponse->execute(array($mois, $annee));
	$nbr=$reponse->rowCount();
	if ($nbr>0) 
	{
		while ($donnees= $reponse->fetch())
		{
			$id=$donnees['0'];
			$date_operation=$donnees['1'];
			$motif=$donnees['2'];
	        $type=$donnees['3'];
			$montant=$donnees['4'];
			$section=$donnees['5'];
			$id_user=$donnees['6'];
			$chemin=$donnees['7'];
			$pj=$donnees['8'];
			$date_operation1=$donnees['9'];
			if ($type=='entree') 
			{
				echo "<tr class='brown lighten-4'>";
				$som_entree=$som_entree+$montant;		
			}
			elseif ($type=='sortie') 
			{
				echo "<tr class='deep-orange lighten-4'>";
				$som_sortie=$som_sortie+$montant;
			}
			else
			{
				echo "<tr>";
			}
			//Boutons modification
			if ($_SESSION['fonction']=="administrateur" || ($date_operation1==$date_actuelle)) 
			{
				
                echo "<td> <a class='tooltipped' data-position='top' data-delay='50' data-tooltip='cliquez ici pour modifier' href='m_caisse_btp.php?id=$id'> ".$date_operation. "</a> </td>";
			}
			else
			{
				echo "<td class='center'>".$date_operation. "</td>";
			}
			//Affichage des pièces jointes
			
            if ($section<>"solde") 
                {
                    if ($chemin!=NULL)
                    {
                        echo "<td class='center'><a href='".$chemin."'>N°".str_pad($pj, 3, "0", STR_PAD_LEFT)."</a></td>";	
                    }
                    else
                    {
                        echo "<td class='center'>N° ".str_pad($pj, 3, "0", STR_PAD_LEFT)." </td>";
                    }
                }
                else
                {
                    echo "<td></td>";

                }
                
            

			echo "<td>".$motif."</td>";
			echo "<td>".$section."</td>";
			if ($type=="entree") {
				$solde=$solde+$montant;
			echo "<td class='right-align'>".number_format($montant,0,'.',' ')."</td>";
			echo "<td></td>";
			}
			elseif ($type=='sortie') 
			{
				$solde=$solde-$montant;
				echo "<td></td>";	
				echo "<td class='right-align'>".number_format($montant,0,'.',' ')."</td>";
			}
			else
			{
				$solde=$solde+$montant;
				echo "<td></td>";	
				echo "<td></td>";	
			}
				echo "<td class='right-align'>".number_format($solde,0,'.',' ')."</td>";
			if ($_SESSION['fonction']=='administrateur')
			{
				
                echo "<td><a class='red btn' href='supprimer_ligne_caisse.php?id_caisse_btp=".$id."' onclick='return(confirm(\"Voulez-vous supprimer cette opération ?\"))'>Supprimer </a></td>";
				echo "<td>".$id_user. "</td>";
				
			}
			
		}
		$reponse->closeCursor();
		//Total et solde
		echo "</tr>";
		
		echo "<tr class='white darken-3 '>";
		echo "<td colspan='4' class='center'><b>TOTAL</b></td>";
		echo "<td class='right-align'><b>".number_format($som_entree,0,'.',' ')." </b></td>";
		echo "<td class='right-align'><b>".number_format($som_sortie,0,'.',' ')." </b></td>";
		echo "<td class='right-align'><b>".number_format(($solde_init+$som_entree-$som_sortie),0,'.',' ')." </b></td>";
		echo "</tr>";	
	}
	else
	{
		echo "<tr><td class='trait center' colspan='5'><h3>Aucune ce mois ci </td></tr>";
	}			
}

else
{
	$reponse=$db->prepare("SELECT caisse_btp.id, CONCAT(DATE_FORMAT(date_operation, '%d'), '/', DATE_FORMAT(date_operation, '%m'),'/', DATE_FORMAT(date_operation, '%Y')), motif, type, montant, section, id_user, pj_caisse.chemin, pj, date_operation
	FROM `caisse_btp`
	LEFT JOIN pj_caisse ON caisse_btp.id=pj_caisse.id_caisse_btp
	WHERE type<>'solde' AND month(date_operation)=? AND year(date_operation)=? AND motif like CONCAT('%',?,'%') ORDER BY date_operation ASC");
	$reponse->execute(array($mois, $annee, $search));
	$nbr=$reponse->rowCount();
	if ($nbr>0) 
	{
		$solde=0;
		while ($donnees= $reponse->fetch())
		{
			$id=$donnees['0'];
			$date_operation=$donnees['1'];
			$motif=$donnees['2'];
	        $type=$donnees['3'];
			$montant=$donnees['4'];
			$section=$donnees['5'];
			$id_user=$donnees['6'];
			$chemin=$donnees['7'];
			$pj=$donnees['8'];
			if ($type=='entree') 
			{
				echo "<tr class='brown lighten-4'>";	
				$som_entree=$som_entree+$montant;		
			}
			elseif ($type=='sortie') 
			{
				echo "<tr class='deep-orange lighten-4'>";
				$som_sortie=$som_sortie+$montant;
			}
			else
			{
				echo "<tr>";
			}

			if ($_SESSION['fonction']=="daf") 
			{
					echo "<td> <a class='tooltipped' data-position='top' data-delay='50' data-tooltip='cliquez ici pour modifier' href='m_caisse_btp.php?id=$id'> ".$date_operation. "</a> </td>";
			}
			else
			{
				echo "<td>".$date_operation. "</td>";
			}
			echo "<td >".$motif."</td>";
			if ($type=="entree") {
				$solde=$solde+$montant;
			echo "<td>".number_format($montant,0,'.',' ')."</td>";
			echo "<td></td>";
			}
			elseif ($type=='sortie') 
			{
				$solde=$solde-$montant;
				echo "<td></td>";	
				echo "<td>".number_format($montant,0,'.',' ')."</td>";
			}
			else
			{
				$solde=$solde+$montant;
				echo "<td></td>";	
				echo "<td></td>";	
			}
				echo "<td>".number_format($solde,0,'.',' ')."</td>";
			if ($_SESSION['fonction']=='administrateur')
			{
				
					echo "<td><a class='red btn ' href='supprimer_ligne_caisse.php?id_caisse_btp=".$id."' onclick='return(confirm(\"Voulez-vous supprimer cette opération ?\"))'>Supprimer </a></td>";
				echo "<td>".$id_user. "</td>";
				
			}

		}
		$reponse->closeCursor();
		//Total et solde
		echo "</tr>";
	}
	else
	{
		echo "<tr><td></td><td></td><td></td><td><h3>Aucune opération ce mois ci </td></tr>";
	}	
}
?>
<script type="text/javascript">
	$('.tooltipped').tooltip();
</script>
	