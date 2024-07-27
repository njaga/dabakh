<?php
session_start();
include 'connexion.php';

$id_produit=htmlspecialchars($_POST['id_produit']);
$id=htmlspecialchars($_POST['id']);
$qt=htmlspecialchars($_POST['qt']);
$date_ravitaillement=htmlspecialchars($_POST['date_ravitaillement']);
$qt_total=$qt;

$req=$db->prepare('UPDATE ravitaillement_produit SET date_ravitaillement=?, qt=? WHERE id=?');
$req->execute(array($date_ravitaillement, $qt_total, $id)) or die(print_r($req->errorInfo()));
$id_ravitaillement=$id;
$nbr=$req->rowCount();

$req=$db->prepare('UPDATE produit SET qt=?, id_ravitaillement=? WHERE id=?');
$req->execute(array($qt_total, $id_ravitaillement, $id_produit)) or die(print_r($req->errorInfo()));
$nbr=$req->rowCount();
echo $qt_total;
?>
<script type="text/javascript">
	alert('Ravitaillement modifé');
	window.location="l_ravitaillement_produit.php";
</script>
