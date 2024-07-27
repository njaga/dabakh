<?php
session_start();
include 'connexion.php';

$id_produit=htmlspecialchars($_POST['id_produit']);
$qt_restante=htmlspecialchars($_POST['qt_restante']);
$qt=htmlspecialchars($_POST['qt']);
$date_ravitaillement=htmlspecialchars($_POST['date_ravitaillement']);
$qt_total=$qt+$qt_restante;

$req=$db->prepare('INSERT INTO ravitaillement_produit (date_ravitaillement, qt, ancien_qt, id_produit) VALUES (?,?,?,?)');
$req->execute(array($date_ravitaillement, $qt_total, $qt_restante, $id_produit)) or die(print_r($req->errorInfo()));
$id_ravitaillement=$db->lastInsertId();
$nbr=$req->rowCount();
if ($nbr>0) {

	$req=$db->prepare('UPDATE produit SET qt=?, id_ravitaillement=? WHERE id=?');
	$req->execute(array($qt_total, $id_ravitaillement, $id_produit)) or die(print_r($req->errorInfo()));
	$nbr=$req->rowCount();
?>
<script type="text/javascript">
	alert('Ravitaillement ajouté');
	window.location="l_ravitaillement_produit.php";
</script>
<?php
}
else
{
?>
<script type="text/javascript">
	alert('Erreur Ravitaillement non enregistrée');
	window.history.go(-1);
</script>
<?php
}
?>