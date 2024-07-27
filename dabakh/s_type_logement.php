<?php
try {
	include 'connexion.php';
	$req=$db->prepare('DELETE FROM type_logement WHERE type_logement=? ');
	$req->execute(array($_GET['id'])) or die(print_r($req->errorInfo()));
} catch (Exception $e) {
	echo "erreu".$e->message();
}
?>
<script type="text/javascript">
alert('Suppression réussi réussi');
window.location="location:type_logement.php";
</script>