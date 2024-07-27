<?php
include 'supprim_accents.php';
$service=suppr_accents(ucfirst(strtolower(htmlspecialchars($_POST['service']))));
$pu=$_POST['pu'];
try {
	include 'connexion.php';
	$req=$db->prepare('UPDATE service SET service=?, pu=? WHERE id=?');
	$req->execute(array($service, $pu, $_GET['id'])) or die(print_r($req->errorInfo()));
	} catch (Exception $e) {
	echo "erreu".$e->message();
}
?>
	<script type="text/javascript">
	alert('Modification réussi');
	window.location="service.php";
	</script>