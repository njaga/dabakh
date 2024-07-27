<?php
include 'connexion.php';
$search=htmlspecialchars($_POST['search']);

if ($search=="") 
{
	$reponse=$db->query('SELECT * FROM soins_externes WHERE etat=0 order by soins');
}
else
{
	$reponse=$db->prepare('SELECT * FROM soins_externes WHERE etat=0  AND soins like CONCAT("%",?,"%") order by soins');
	$reponse->execute(array($search));	
}
while ($donnees= $reponse->fetch()) 
{
$id=$donnees['0'];
$soins=$donnees['1'];
$pu=$donnees['2'];
echo "<tr>";
	echo "<td> <a class='tooltipped' data-position='top' data-delay='50' data-tooltip='cliquez ici pour modifier' href='m_soins_externes.php?id=$id'>SOEX".$id."</a></td>";
	echo "<td>".$soins."</td>";
	echo "<td>".number_format($pu,0,'.',' ')." Fcfa</td>";
	echo "<td class='supprimer'> <a class='btn red tooltipped' data-position='top' data-delay='50' data-tooltip='Supprimer' href='s_soins_externes.php?s=$id' onclick='return(confirm(\"Voulez-vous supprimer ce soins ?\"))'><i class='material-icons left'>close</i></a></td>";
echo "<tr>";
}
if (!isset($id))
{
	echo "<h3 class='center'>Aucun soins externe enregistré</h3>";
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