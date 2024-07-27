<?php
include 'supprim_accents.php';
$produit=suppr_accents(strtoupper(htmlspecialchars($_POST['produit'])));
$pu=$_POST['pu'];
try {
	include 'connexion.php';
	$req=$db->prepare('INSERT INTO produit(produit, pu) values (?,?) ');
	$req->execute(array($produit, $pu)) or die(print_r($req->errorInfo()));
	} catch (Exception $e) {
	echo "Erreur".$e->message();
}
?>
	<script type="text/javascript">
	alert('insrtion réussi');
	window.location="produit.php";
	</script>