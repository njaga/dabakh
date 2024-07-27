<?php
include 'connexion.php';
$req=$db->prepare("DELETE FROM consommable_utilisation WHERE id=?");
$req->execute(array($_GET['id'])) or die($req->errorInfo());
$nbr=$req->rowCount();
if ($nbr>0) 
{
	$req=$db->prepare('UPDATE consommable SET qt=qt+? WHERE id=? ');
	$nbr=$req->execute(array($_GET['qt'], $_GET['id_consommable'])) or die(print_r($req->errorInfo()));
	?>
	<script type="text/javascript">
		alert("Suppression effectuée");
		window.history.go(-1);
	</script>
	<?php
};
?>
