<?php
session_start();
include 'connexion.php';

$req=$db->prepare('DELETE FROM rapport_consultation_domicile WHERE id=?');
	$req->execute(array($_GET['id']));
	$nbr=$req->rowCount();
?>
<script type="text/javascript">
	alert('Rapport supprimé');
	window.history.go(-1);
</script>
