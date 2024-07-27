<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include 'connexion.php';
$search=$_POST['search'];
if ($search=="")
{
	$reponse=$db->prepare("SELECT id, telephone, mail, contact, autres_infos, structure FROM `contact` WHERE structure=? ORDER BY id");
	$reponse->execute(array($_SESSION['service']));
	$resultat=$reponse->rowCount();
}
else
{
	$reponse=$db->prepare("SELECT id, telephone, mail, contact, autres_infos, structure FROM `contact` WHERE contact like CONCAT('%',?,'%') AND structure=? ORDER BY id");
	$reponse->execute(array($search, $_SESSION['service']));
}
$resultat=$reponse->rowCount();
if ($resultat<1)
{
	echo "<tr><td colspan='7'><h3 class='center'>Aucun résultat</h3></td></tr>";
}
$i=0;
while ($donnees= $reponse->fetch())
{
$id=$donnees['0'];
$telephone=$donnees['1'];
$mail=$donnees['2'];
$contact=$donnees['3'];
$aitres_infos=$donnees['4'];
$i++;
echo "<tr>";
	echo "<td class='grey lighten-3'><b>".$i. "</b></td>";
	if ($_SESSION['fonction']=="administrateur" or $_SESSION['fonction']=="daf") 
	{
		echo "<td> <a class='tooltipped' data-position='top' data-delay='50' data-tooltip='cliquez ici pour modifier' href='m_contact.php?id=$id'>".$contact."</a></td>";
	}
	else
	{
		echo "<td> ".$contact."</td>";
	}
	echo "<td>".$telephone."</td>";
	echo "<td>".strtolower($mail)."</td>";
	echo "<td>".nl2br($aitres_infos)."</td>";
	if ($_SESSION['fonction']=="administrateur") 
	{
		echo "<td> <a class='red btn' href='s_contact.php?id=$id'>Supprimer</a></td>";
	}
echo "</tr>";}

?>
<script type="text/javascript">
	$('.tooltipped').tooltip();
</script>