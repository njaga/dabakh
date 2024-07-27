<?php
include 'connexion.php';
$search=$_POST['search'];
$nbr=0;
if ($search=="") 
{
	$reponse=$db->query('SELECT * FROM soins_domicile WHERE etat=0 order by soins');
}
else
{
	$reponse=$db->prepare('SELECT * FROM soins_domicile WHERE etat=0  AND soins like CONCAT("%",?,"%") order by soins');
	$reponse->execute(array($search));
}
while ($donnees= $reponse->fetch())
{
	$id=$donnees['0'];
	$soins=$donnees['1'];
	$pu=$donnees['2'];
	echo "<tr onmouseover='afficher_bt_modifier(this)' onmouseout='cacher_bt_modifier(this)'>";
	echo "<td> <a class='tooltipped' data-position='top' data-delay='50' data-tooltip='cliquez ici pour modifier' href='m_soins_domicile.php?id=$id'>SAD".$id."</a> </td>";
	echo "<td>".$soins."</td>";
	echo "<td>".number_format($pu,0,'.',' ')."</td>";
	echo "<td class='supprimer'> <a class='btn red tooltipped' data-position='top' data-delay='50' data-tooltip='Supprimer' href='s_soins_domicile.php?s=$id' onclick='return(confirm(\"Voulez-vous supprimer ce soins ?\"))'><i class='material-icons left'>close</i></a></td>";
	echo "<tr>";
	$nbr++;
}
if (!isset($id))
{
echo "<h3 class='center'>Aucun soins à domicile enregistré</h3>";
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