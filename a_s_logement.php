<?php
include 'connexion.php';

$req=$db->prepare('SELECT nbr FROM logement 
WHERE id=?');
$req->execute(array($_GET['id']));
$donnee=$req->fetch();
$nbr=$donnee['0'];

if (isset($_GET['a']) AND $_GET['a']=="a") 
{
	$req=$db->prepare('UPDATE logement SET nbr=?  
	WHERE id=?');
	$req->execute(array(($_GET['nbr']+1), $_GET['id']));	
}
else
{
	if ($nbr<1) 
	{
		?>
	<script type="text/javascript">
		alert("Impossible de diminuer ce logement car une location est en cours sur ce dernier");
	</script>
	<?php	
	}
	else
	{
		$req=$db->prepare('UPDATE logement SET nbr=?  
	WHERE id=?');
	$req->execute(array(($_GET['nbr']-1), $_GET['id']));
	}
}

//header("location:e_logement.php?id=")
?>
<script type="text/javascript">
	window.history.go(-1);
</script>