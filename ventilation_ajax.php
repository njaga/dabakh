<?php		
session_start();			
include 'connexion.php';
$mois=$_POST['mois'];
$annee=$_POST['annee'];
$db->query("SET lc_time_names = 'fr_FR';");
$reponse=$db->prepare("SELECT id, CONCAT(day(date_operation), ' ', monthname(date_operation),' ', year(date_operation)), motif, type, montant, id_mensualite,section, id_mensualite_bailleur, id_depense_bailleur
FROM `caisse_immo`
WHERE month(date_operation)=? AND year(date_operation)=? ORDER BY date_operation ASC");
$reponse->execute(array($mois, $annee));
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
		$id_mensualite=$donnees['5'];
		$section=$donnees['6'];
		$id_mensualite_bailleur=$donnees['7'];
		$id_depense_bailleur=$donnees['8'];
		if ($type=='entree') 
		{
			echo "<tr class='brown lighten-4'>";		
		}
		elseif ($type=='sortie') 
		{
			echo "<tr class='deep-orange lighten-4'>";
		}
		else
		{
			echo "<tr>";
		}

		if ($id_mensualite_bailleur!=NULL) 
		{
			echo "<td> <a class='tooltipped' data-position='top' data-delay='50' data-tooltip='Impossible de le modifier' > ".$date_operation. "</a> </td>";	
		}
		elseif ($id_depense_bailleur!=NULL) 
		{
			echo "<td> <a class='tooltipped' data-position='top' data-delay='50' data-tooltip='cliquez ici pour modifier' href='m_depense_bailleur.php?id=$id_depense_bailleur'> ".$date_operation. "</a> </td>";	
		}
		else
		{
			echo "<td> <a class='tooltipped' data-position='top' data-delay='50' data-tooltip='cliquez ici pour modifier' href='m_caisse_immo.php?id=$id'> ".$date_operation. "</a> </td>";
		}
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
		else
		{
			$solde=$solde+$montant;
			echo "<td></td>";	
			echo "<td></td>";	
		}
			echo "<td>".number_format($solde,0,'.',' ')." Fcfa</td>";
		if ($_SESSION['fonction']!='secretaire')
		{
			if (isset($id_mensualite)) 
			{
				echo "<td><a class='red btn' href='s_mensualite.php?id=$id_mensualite' onclick='return(confirm(\"Voulez-vous supprimer cette opération ?\"))'>Supprimer </a></td>";	
			}
			elseif (isset($id_depense_bailleur)) 
			{
				echo "<td><a class='red btn' href='s_depense_bailleur.php?id=".$id_depense_bailleur."' onclick='return(confirm(\"Voulez-vous supprimer cette opération ?\"))'>Supprimer </a></td>";	
			}
			elseif (isset($id_mensualite_bailleur)) 
			{
				echo "<td><a class='red btn' href='s_mensualite_bailleur.php?id=".$id_mensualite_bailleur."' onclick='return(confirm(\"Voulez-vous supprimer cette opération ?\"))'>Supprimer </a></td>";	
			}
			else
			{
				echo "<td><a class='red btn' href='supprimer_ligne_caisse.php?id_caisse_immo=".$id."' onclick='return(confirm(\"Voulez-vous supprimer cette opération ?\"))'>Supprimer </a></td>";
			}
			
		}
		else
		{
			echo "<td></td>";
		}

	}
	$reponse->closeCursor();
	//Total et solde
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
	echo "<tr class='grey darken-3 white-text'>";
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
<script type="text/javascript">
	$('.tooltipped').tooltip();
</script>
