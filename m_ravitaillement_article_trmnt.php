<?php
session_start();
include 'connexion.php';

$id_article=htmlspecialchars($_POST['id_article']);
$id=htmlspecialchars($_POST['id']);
$qt=htmlspecialchars($_POST['qt']);
$ancien_qt=htmlspecialchars($_POST['ancien_qt']);
$montant=htmlspecialchars($_POST['montant']);
$date_ravitaillement=htmlspecialchars($_POST['date_ravitaillement']);
$nouveau_qt=$qt + $ancien_qt;

$req=$db->prepare('UPDATE ravitaillement_article SET date_ravitaillement=?, qt=?, montant=? WHERE id=?');
$req->execute(array($date_ravitaillement, $nouveau_qt, $montant, $id)) or die(print_r($req->errorInfo()));
$id_ravitaillement=$id;
$nbr=$req->rowCount();

$req=$db->prepare('UPDATE article SET qt=? WHERE id=?');
$req->execute(array($nouveau_qt, $id_article)) or die(print_r($req->errorInfo()));

$req=$db->prepare('SELECT article FROM article WHERE id=?');
$req->execute(array($id_article)) or die(print_r($req->errorInfo()));
$donnees=$req->fetch();
$article=$donnees['0'];

$motif="Achat de ".$article." (".$qt.")";

$req=$db->prepare("UPDATE caisse_commerce SET motif=?, date_operation=?, montant=? WHERE id_ravitaillement_article=?");
$req->execute(array($motif, $date_ravitaillement, $montant, $id_ravitaillement)) or die($req->errorInfo());

?>
<script type="text/javascript">
	alert('Ravitaillement modifé');
	window.history.go(-2);
</script>
