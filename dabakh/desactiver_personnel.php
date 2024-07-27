<?php
include 'connexion.php';
$id=htmlspecialchars($_GET['id']);
$etat=htmlspecialchars($_GET['etat']);
if ($etat=="desactiver") 
{
	$etat="activer";
}
else
{
	$etat="desactiver";
}

$req=$db->prepare('UPDATE personnel SET etat=? WHERE id=? ');
$req->execute(array($etat,$id)) or die(print_r($req->errorInfo()));
$nbr=$req->rowCount();
?>
<script type="text/javascript">
	alert('personnel <?=$etat?>');
	window.location="l_personnel.php";
</script>