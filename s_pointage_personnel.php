<?php
session_start();
include 'connexion.php';

$req=$db->prepare('DELETE FROM pointage_personnel WHERE id=?');
	$req->execute(array($_GET['id']));
	$nbr=$req->rowCount();
?>
<script type="text/javascript">
	alert('Pointage supprimé');
	window.history.go(-1);
</script>
