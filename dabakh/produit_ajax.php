<?php
session_start();	
include 'connexion.php';
$search=$_POST['search'];
$nbr=0;

if ($search=="")  
{
	$reponse=$db->query('SELECT * FROM produit WHERE etat=0 order by produit');
}
else
{
	$reponse=$db->prepare("SELECT * FROM produit WHERE etat=0  AND produit like CONCAT('%',?,'%') order by produit");
	$reponse->execute(array($search));	
}
while ($donnees= $reponse->fetch()) {
	$id=$donnees['0'];
	$produit=$donnees['1'];
	$pu=$donnees['2'];
	$qt=$donnees['3'];
	$id_ravitaillement=$donnees['4'];
	if ($qt<1) 
	{
		echo "<tr class='red lighten-3'>";
	}
	elseif ($qt<11) 
	{
		echo "<tr class='yellow lighten-3'>";
	}
	else
	{
		echo "<tr>";
	}
	
      if (($_SESSION['fonction']=='medecin') OR ($_SESSION['fonction']=='administrateur') OR ($_SESSION['fonction']=='secretaire'))
      {
		echo "<td> <a class='tooltipped' data-position='top' data-delay='50' data-tooltip='cliquez ici pour modifier' href='modif_produit.php?id=$id'>PROD".$id."</a></td>";
      }
      else
      {
		echo "<td></td>";
      }
     
        
	echo "<td>".$produit."</td>";
	echo "<td>".number_format($pu,0,'.',' ')." Fcfa</td>";
	echo "<td>".$qt."</td>";
	echo "<td class='supprimer'> <a class='btn red tooltipped' data-position='top' data-delay='50' data-tooltip='Supprimer' href='s_produit.php?s=$id' onclick='return(confirm(\"Voulez-vous supprimer ce produit ?\"))'><i class='material-icons left'>close</i></a></td>";	
	echo "<tr>";
	$nbr++;
}
if (!isset($id)) 
{
	echo "<tr><td colspan='4'><h3 class='center'>Aucun produit enregistré</h3></td></tr>";
}
else
{
	echo "<tr class='grey lighten-1'>";
		echo "<td colspan='2'><b>Total</td>";
		echo "<td><b>".$nbr."</td>";
	echo "</tr>";
}
?>
<style type="text/css">
	.supprimer
	{
		display: none;
	}
</style>
<script type="text/javascript">
	$('.tooltipped').tooltip();
	$('tr').mouseover(function  () {
		$(this).children('.supprimer').css({"display": "inline"});
	});
	$('tr').mouseout(function  () {
		$(this).children('.supprimer').css({"display": "none"});
	});
</script>