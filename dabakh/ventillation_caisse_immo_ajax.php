<?php		
session_start();			
include 'connexion.php';
$jour_d=$_POST['jour_d'];
$jour_f=$_POST['jour_f'];
$mois=date("m", strtotime($jour_d));
$annee=date("Y", strtotime($jour_d));
$total=0;

$db->query("SET lc_time_names = 'fr_FR';");



$req=$db->prepare("SELECT SUM(montant), section FROM `caisse_immo` WHERE section <>'solde' AND date_operation BETWEEN ? AND ? GROUP BY section");
$req->execute(array($jour_d, $jour_f));
$nbr=$req->rowCount();
if ($nbr>0) 
{
	
	$sortie=0;
	while ($donnees= $req->fetch())
	{
		echo "<tr class=''>";
		echo "<td class='center'><a class=' blue-text' href='detail_ventillation_immo.php?d=".$jour_d."&amp;f=".$jour_f."&amp;s=".$donnees['1']."'><b>".ucfirst(strtolower($donnees['1']))." </b></a></td>";
		echo "<td class='center black-text'><b>".number_format($donnees['0'],0,'.',' ')." </b></td>";
		echo "</tr>";	
		$total=$total+$donnees['0'];
	}
	
	echo "<tr class='grey'>";
	echo "<td  class='center black-text'><b>TOTAL</b></td>";
	echo "<td class='center right-align black-text'><b>".number_format($total,0,'.',' ')." </b></td>";
	echo "</tr>";	
}
else
{
	echo "<tr><td class='center black-text' colspan='2'><h3>Aucune opération à cette date </td></tr>";
}			
?>