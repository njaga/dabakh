<?php
session_start();
include 'connexion.php';

$req=$db->prepare('DELETE FROM injonction WHERE id=?');
	$req->execute(array($_GET['id']));
	$nbr=$req->rowCount();
?>
<script type="text/javascript">
	alert('Injonction supprimé');
	window.history.go(-1);
</script>
