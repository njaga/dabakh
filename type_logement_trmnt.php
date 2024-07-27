<?php
include 'supprim_accents.php';
$type_logement=strtoupper(suppr_accents($_POST['type_logement']));
try {
	include 'connexion.php';
	$req=$db->prepare('INSERT INTO type_logement(type_logement) values (?) ');
	$req->execute(array($type_logement)) or die(print_r($req->errorInfo()));
	} catch (Exception $e) {
	echo "erreu".$e->message();
}
?>
	<script type="text/javascript">
	alert('insrtion réussi');
	window.location="type_logement.php";
	</script>