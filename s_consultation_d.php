<?php
include 'connexion.php';
$req=$db->prepare('DELETE FROM consultation_domicile WHERE id_consultation=?');
$req->execute(array($_GET['id']));
$nbr=$req->rowCount();
?>
<script type="text/javascript">
	alert("Soins supprimé");
	window.history.go(-1);
</script>