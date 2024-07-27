<?php
session_start();
include 'connexion.php';

$id_article=htmlspecialchars($_POST['id']);
$qt_restante=htmlspecialchars($_POST['qt_restante']);
$qt=htmlspecialchars($_POST['qt']);
$montant=htmlspecialchars($_POST['montant']);
$date_ravitaillement=htmlspecialchars($_POST['date_ravitaillement']);
$qt_total=$qt+$qt_restante;
$id_user=$_SESSION['prenom']." ".$_SESSION['nom'];

$req=$db->prepare('SELECT article FROM article WHERE id=?');
$req->execute(array($id_article)) or die(print_r($req->errorInfo()));
$donnee=$req->fetch();
$article=$donnee['0'];

$req=$db->prepare('INSERT INTO ravitaillement_article (date_ravitaillement, qt, ancien_qt, id_article, montant, id_user) VALUES (?,?,?,?,?,?)');
$req->execute(array($date_ravitaillement, $qt_total, $qt_restante, $id_article, $montant, $id_user)) or die(print_r($req->errorInfo()));
$id_ravitaillement=$db->lastInsertId();
$nbr=$req->rowCount();
if ($nbr>0) {

	$req=$db->prepare('UPDATE article SET qt=? WHERE id=?');
	$req->execute(array($qt_total, $id_article)) or die(print_r($req->errorInfo()));
	$nbr=$req->rowCount();
	if($montant>0)
	{
		$motif="Achat de ".$article." (".$qt.")";
		$req=$db->prepare("INSERT INTO caisse_commerce (type, motif, section, date_operation, montant, id_ravitaillement_article, id_user) VALUES ('sortie', ?, 'Achat article', ?, ?, ?, ?) ");
		$req->execute(array($motif, $date_ravitaillement, $montant, $id_ravitaillement, $id_user)) or die($req->errorInfo());
	}
?>
<script type="text/javascript">
	alert('Ravitaillement ajouté');
	window.location="l_ravitaillement_article.php";
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