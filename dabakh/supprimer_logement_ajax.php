<?php
include 'connexion.php';
$req=$db->prepare('SELECT nbr_occupe FROM logement 
WHERE id=?');
$req->execute(array($_GET['id']));
$donnee=$req->fetch();
$nbr_occupe=$donnee['0'];
if ($nbr_occupe>0) 
{
	?>
<script type="text/javascript">
	alert("Impossible de supprimer ce logement car une location est en cours sur ce dernier");
</script>
<?php	
}
else
{
	$req=$db->prepare('UPDATE logement set etat="inactif" WHERE id=?');
	$req->execute(array($_GET['id']));	
}

//header("location:e_logement.php?id=".$_GET['id_bailleur']);
?>
<script type="text/javascript">
	alert("logement supprimé");
	window.history.go(-1);
</script>