<?php
include 'connexion.php';
$nbr=0;
if (isset($_GET['id'])) 
{
	$req=$db->prepare('DELETE FROM mensualite WHERE id=?');
	$req->execute(array($_GET['id'])) or die(print_r($req->errorInfo()));;
	$req->fetch();
	$req->closeCursor();
	$nbr=$req->rowCount();

	$req=$db->prepare('DELETE FROM caisse_immo WHERE id_mensualite=?');
	$req->execute(array($_GET['id'])) or die(print_r($req->errorInfo()));;
	$req->fetch();
	$req->closeCursor();
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
