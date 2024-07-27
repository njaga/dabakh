<?php
include 'supprim_accents.php';
$soins=suppr_accents(strtoupper(htmlspecialchars($_POST['soins'])));
$pu=$_POST['pu'];
try {
	include 'connexion.php';
	$req=$db->prepare('INSERT INTO soins_domicile(soins, pu) values (?,?) ');
	$req->execute(array($soins, $pu)) or die(print_r($req->errorInfo()));
	} catch (Exception $e) {
	echo "erreu".$e->message();
}
?>
	<script type="text/javascript">
	alert('insrtion réussi');
	window.location="soins_domicile.php";
	</script>