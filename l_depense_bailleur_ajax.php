<?php
session_start();
include 'connexion.php';
$db->query("SET lc_time_names = 'fr_FR';");

$mois=$_POST['mois'];
$annee=$_POST['annee'];
$search=$_POST['search'];
$type=$_POST['type'];
if ($search=="")
{
	if($type=="tout")
	{
		$reponse=$db->prepare("SELECT depense_bailleur.id, depense_bailleur.type_depense, depense_bailleur.motif, CONCAT(day(depense_bailleur.date_depense), ' ', monthname(depense_bailleur.date_depense),' ', year(depense_bailleur.date_depense)), depense_bailleur.montant, depense_bailleur.type_paiement, CONCAT(bailleur.prenom, ' ', bailleur.nom),depense_bailleur.id_mensualite_bailleur 
		FROM `depense_bailleur`, bailleur 
		WHERE bailleur.id=depense_bailleur.id_bailleur AND depense_bailleur.annee=? AND depense_bailleur.mois=? ORDER BY depense_bailleur.date_depense ASC");
			$reponse->execute(array($annee, $mois));
		$resultat=$reponse->rowCount();
	}
	else
	{
		$reponse=$db->prepare("SELECT depense_bailleur.id, depense_bailleur.type_depense, depense_bailleur.motif, CONCAT(day(depense_bailleur.date_depense), ' ', monthname(depense_bailleur.date_depense),' ', year(depense_bailleur.date_depense)), depense_bailleur.montant, depense_bailleur.type_paiement, CONCAT(bailleur.prenom, ' ', bailleur.nom),depense_bailleur.id_mensualite_bailleur 
		FROM `depense_bailleur`, bailleur 
		WHERE bailleur.id=depense_bailleur.id_bailleur AND depense_bailleur.annee=? AND depense_bailleur.type_depense=? AND depense_bailleur.mois=? ORDER BY depense_bailleur.date_depense ASC");
			$reponse->execute(array($annee, $type, $mois));
		$resultat=$reponse->rowCount();
	}
}
else
{
	if($type=="tout")
	{
		$reponse=$db->prepare("SELECT depense_bailleur.id, depense_bailleur.type_depense, depense_bailleur.motif, CONCAT(day(depense_bailleur.date_depense), ' ', monthname(depense_bailleur.date_depense),' ', year(depense_bailleur.date_depense)), depense_bailleur.montant, depense_bailleur.type_paiement, CONCAT(bailleur.prenom, ' ', bailleur.nom),depense_bailleur.id_mensualite_bailleur 
		FROM `depense_bailleur`, bailleur 
		WHERE bailleur.id=depense_bailleur.id_bailleur AND depense_bailleur.annee=? AND depense_bailleur.mois=? AND CONCAT (bailleur.prenom,' ',bailleur.nom) like CONCAT('%', ?, '%') ORDER BY depense_bailleur.date_depense ASC");
		$reponse->execute(array($annee, $mois, $search));
	}
	else
	{
		$reponse=$db->prepare("SELECT depense_bailleur.id, depense_bailleur.type_depense, depense_bailleur.motif, CONCAT(day(depense_bailleur.date_depense), ' ', monthname(depense_bailleur.date_depense),' ', year(depense_bailleur.date_depense)), depense_bailleur.montant, depense_bailleur.type_paiement, CONCAT(bailleur.prenom, ' ', bailleur.nom),depense_bailleur.id_mensualite_bailleur 
		FROM `depense_bailleur`, bailleur 
		WHERE bailleur.id=depense_bailleur.id_bailleur AND depense_bailleur.annee=? AND depense_bailleur.mois=? AND depense_bailleur.type_depense=? AND CONCAT (bailleur.prenom,' ',bailleur.nom) like CONCAT('%', ?, '%') ORDER BY depense_bailleur.date_depense ASC");
		$reponse->execute(array($annee, $mois, $type, $search));
	}
	
}
$resultat=$reponse->rowCount();
if ($resultat<1)
{
	echo "<tr><td colspan='7'><h3 class='center'>Aucun résultat</h3></td></tr>";
}
$total=0;
while ($donnees= $reponse->fetch())
{
$id=$donnees['0'];
$type_depense=$donnees['1'];
$motif=$donnees['2'];
$date_depense=$donnees['3'];
$montant=$donnees['4'];
$type_paiement=$donnees['5'];
$bailleur=$donnees['6'];
$id_mensualite_bailleur=$donnees['7'];
$total=$total+$montant;
if ($id_mensualite_bailleur>0) 
{
	echo "<tr class='brown lighten-4'>";
}
else
{
	echo "<tr>";
}
	if ($_SESSION['fonction']=="daf" or $_SESSION['fonction']=="administrateur") 
	{										
		echo "<td> <a class='tooltipped' data-position='top' data-delay='50' data-tooltip='cliquez ici pour modifier' href='m_depense_bailleur.php?id=$id'>".$date_depense."</a></td>";
	}
	else
	{
		echo "<td>".$date_depense."</td>";		
	}
	echo "<td>".$bailleur."</td>";
	echo "<td>".strtoupper($type_depense)."</td>";
	echo "<td>".$motif."</td>";
	echo "<td><b>".strtoupper($type_paiement)."</b></td>";
	echo "<td>".number_format($montant,0,'.',' ')." </td>";
	if ($id_mensualite_bailleur>0) 
	{
		echo "<td> <a class='btn tooltipped brown'  data-position='top' data-delay='50' data-tooltip='Reçu bailleur N°".$id_mensualite_bailleur."' href='i_mensualite_bailleur.php?id=$id_mensualite_bailleur'><i class='material-icons left'>print</i></a>";
		echo "&nbsp&nbsp <a class='red btn tooltipped'   data-position='top' data-delay='50' data-tooltip='Supprimer' href='s_depense_bailleur.php?id=$id'><i class='material-icons left'>close</i></a></td>";
	}
	else
	{
		if ($_SESSION['fonction']=="administrateur") 
		{
			echo "<td> <a class='red btn tooltipped'   data-position='top' data-delay='50' data-tooltip='Supprimer' href='s_depense_bailleur.php?id=$id'><i class='material-icons left'>close</i></a></td>";
		}
	}
echo "</tr>";
}
echo "<tr class='brown lighten-4'>";
	echo "<td colspan='4'><b>TOTAL</b>";
	echo "<td colspan='2'><b>".number_format($total,0,'.',' ')." </b>";
echo "</tr>";
?>
<script type="text/javascript">
	$('.tooltipped').tooltip();
</script>