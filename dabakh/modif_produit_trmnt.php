<?php
include 'supprim_accents.php';
$produit=suppr_accents(ucfirst(strtolower(htmlspecialchars($_POST['produit']))));
$pu=$_POST['pu'];
try {
	include 'connexion.php';
	$req=$db->prepare('UPDATE produit SET produit=?, pu=? WHERE id=?');
	$req->execute(array($produit, $pu, $_GET['id'])) or die(print_r($req->errorInfo()));
	} catch (Exception $e) {
	echo "erreu".$e->message();
}
?>
	<script type="text/javascript">
	alert('Modification réussi');
	window.location="produit.php";
	</script>