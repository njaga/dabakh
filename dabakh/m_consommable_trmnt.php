<?php
include 'supprim_accents.php';
$consommable=suppr_accents(ucfirst(strtolower(htmlspecialchars($_POST['consommable']))));
$pu=$_POST['pu'];
try {
	include 'connexion.php';
	$req=$db->prepare('UPDATE consommable SET consommable=?, pu=? WHERE id=?');
	$req->execute(array($consommable, $pu, $_GET['id'])) or die(print_r($req->errorInfo()));
	} catch (Exception $e) {
	echo "erreu".$e->message();
}
?>
	<script type="text/javascript">
	alert('Modification réussi');
	window.location="consommable.php";
	</script>