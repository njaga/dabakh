<?php
include 'connexion.php';
$nbr=0;
if (isset($_GET['id'])) 
{
	if (isset($_GET['p']))
	{
		$req=$db->prepare('UPDATE  mensualite SET id_mensualite_bailleur=-1  WHERE id=?');
		$req->execute(array($_GET['id'])) or die(print_r($req->errorInfo()));;
		$req->fetch();
		$req->closeCursor();
		$nbr=$req->rowCount();
	}
	else
	{
		$req=$db->prepare('UPDATE  mensualite SET id_mensualite_bailleur=1  WHERE id=?');
		$req->execute(array($_GET['id'])) or die(print_r($req->errorInfo()));;
		$req->fetch();
		$req->closeCursor();
		$nbr=$req->rowCount();
	}
}
if ($nbr>0) 
{
	?>
	<script type="text/javascript">
		alert("Suppression effectuée");
		window.history.go(-1);
	</script>
	<?php
}
else
{
	?>
	<script type="text/javascript">
		alert("Erreur : DX*001. Suppression impossible. Si l'erreur persite contacter votre administrateur");
		window.history.go(-1);
	</script>
	<?php
}
?>
