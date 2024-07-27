<?php
include 'connexion.php';
$req=$db->prepare('DELETE FROM banque WHERE id=?');
	$req->execute(array($_GET['id'])) or die(print_r($req->errorInfo()));;
	$req->fetch();
	$req->closeCursor();
?>
<script type="text/javascript">
		alert("Opération supprimée !");
		window.history.go(-1);
	</script>
