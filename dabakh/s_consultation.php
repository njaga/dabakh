<?php
include 'connexion.php';
$req=$db->prepare('DELETE FROM consultation WHERE id_consultation=?');
$req->execute(array($_GET['id']));
$nbr=$req->rowCount();
?>
<script type="text/javascript">
	alert("Consultion supprimée");
	window.history.go(-1);
</script>