<?php
include 'supprim_accents.php';
$service=suppr_accents(strtoupper(htmlspecialchars($_POST['service'])));
$pu=$_POST['pu'];
try {
	include 'connexion.php';
	$req=$db->prepare('INSERT INTO service(service, pu) values (?,?) ');
	$req->execute(array($service, $pu)) or die(print_r($req->errorInfo()));
	} catch (Exception $e) {
	echo "erreu".$e->message();
}
?>
	<script type="text/javascript">
	alert('insrtion réussi');
	window.location="service.php";
	</script>