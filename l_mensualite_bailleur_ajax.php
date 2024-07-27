<?php	
session_start();				
include 'connexion.php';
$mois=$_POST['mois'];
$annee=$_POST['annee'];
$search=$_POST['search'];
$somme=0;
$db->query("SET lc_time_names = 'fr_FR';");
if ($search=="") 
{
	$reponse=$db->prepare("SELECT mensualite_bailleur.id, CONCAT(day(mensualite_bailleur.date_versement),' ', monthname(mensualite_bailleur.date_versement),' ', year(mensualite_bailleur.date_versement)), CONCAT(bailleur.prenom, ' ', bailleur.nom), mensualite_bailleur.montant, mensualite_bailleur.type_versement, bailleur.id, mensualite_bailleur.id_user
FROM mensualite_bailleur, bailleur
WHERE mensualite_bailleur.id_bailleur=bailleur.id AND mensualite_bailleur.mois=? AND mensualite_bailleur.annee=?");
	$reponse->execute(array($mois, $annee));
}
else
{
	$reponse=$db->prepare("SELECT mensualite_bailleur.id, CONCAT(day(mensualite_bailleur.date_versement),' ', monthname(mensualite_bailleur.date_versement),' ', year(mensualite_bailleur.date_versement)), CONCAT(bailleur.prenom, ' ', bailleur.nom), mensualite_bailleur.montant, mensualite_bailleur.type_versement, bailleur.id, mensualite_bailleur.id_user
FROM mensualite_bailleur, bailleur
WHERE mensualite_bailleur.id_bailleur=bailleur.id AND mensualite_bailleur.mois=? AND mensualite_bailleur.annee=? AND CONCAT (bailleur.prenom,' ',bailleur.nom) like CONCAT('%', ?, '%')");
	$reponse->execute(array($mois, $annee, $search));
}

$nbr=$reponse->rowCount();
if ($nbr>0) 
{
	$i=1;
	while ($donnees= $reponse->fetch())
	{
		$id=$donnees['0'];
		$date_versement=$donnees['1'];
		$bailleur=$donnees['2'];
		$montant=$donnees['3'];
		$type_versment=$donnees['4'];
		$id_bailleur=$donnees['5'];
		$id_user=$donnees['6'];
		//somme dépense(s)
		$req_depense=$db->prepare("SELECT SUM(montant) FROM `depense_bailleur` WHERE id_mensualite_bailleur=?");
		$req_depense->execute(array($id));	
		$donnees_depense=$req_depense->fetch();
		$depense=$donnees_depense['0'];

		echo "<tr>";
		echo "<td class='grey lighten-3'><b>".$i. "</b></td>";
		if ($_SESSION['fonction']!="secretaire" or $_SESSION['fonction']=="administrateur") 

		{
			echo "<td><a class='tooltipped' data-position='top' data-delay='50' data-tooltip='cliquez ici pour modifier' href='m_mensualite_bailleur.php?id=$id'>".$date_versement."</a></td>";
		}
		else
		{
			echo "<td>".$date_versement. "</td>";

		}
		
		echo "<td>".$bailleur. "</td>";
		echo "<td>".ucfirst($type_versment)."</td>";
		echo "<td>".number_format(($montant+$depense),0,'.',' ')." Fcfa</td>";
		echo "<td>".number_format($depense,0,'.',' ')." Fcfa</td>";
		echo "<td>".number_format($montant,0,'.',' ')." Fcfa</td>";
		echo "<td> <a class='btn' href='i_mensualite_bailleur.php?id=$id'><i class='material-icons left'>print</i> Facture N° ".str_pad($id, 3,"0", STR_PAD_LEFT)."</a>";
		if ($_SESSION['fonction']=="administrateur")
		{
			echo "<br><br> <a class='btn red' href='s_mensualite_bailleur.php?id=$id' onclick='return(confirm(\"Voulez-vous supprimer cette mensualité ?\"))'><i class='material-icons left'>close</i></a>";
		} 
		echo "</td>";
		if ($_SESSION['fonction']=="administrateur")
		{
			echo "<td>".$id_user. "</td>";
		}
		

		echo "</tr>";
		$i++;
	}
	
}
else
{
	echo "<tr><td></td><td></td><td></td><td><h3>Aucun versment ce mois ci </td></tr>";
}			
?>
<script type="text/javascript">
	$('.tooltipped').tooltip();
</script>