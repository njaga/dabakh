<?php
session_start();
include 'supprim_accents.php';
$article=suppr_accents(ucfirst(strtolower(htmlspecialchars($_POST['article']))));
$montant=$_POST['montant'];
$qt=$_POST['qt'];
$pu=$_POST['pu'];
$pj=$_POST['pj'];
$date_ravitaillement=date('y')."-".date('m')."-".date('d');
$id_user=$_SESSION['prenom'].".".$_SESSION['nom'];

try {
	include 'connexion.php';

	$req=$db->prepare('INSERT INTO article(article, pu, qt, etat) values (?,?,?,1)');
	$req->execute(array($article, $pu, $qt)) or die(print_r($req->errorInfo()));
	$id_article=$db->lastInsertId();
	
	$req=$db->prepare('INSERT INTO ravitaillement_article (date_ravitaillement, qt, ancien_qt, id_article, montant, id_user) VALUES (?,?,?,?,?,?)');
	$req->execute(array($date_ravitaillement, $qt, 0, $id_article, $montant, $id_user)) or die(print_r($req->errorInfo()));
	$id_ravitaillement_article=$db->lastInsertId();
	
	if($montant>0)
	{
		$motif="Achat de ".$article." (".$qt.")";
		$req=$db->prepare("INSERT INTO caisse_commerce (type, motif, section, date_operation, montant, id_ravitaillement_article, pj, id_user) VALUES ('sortie', ?, 'Achat article', ?, ?, ?,?, ?) ");
		$req->execute(array($motif, $date_ravitaillement, $montant, $id_ravitaillement_article, $pj, $id_user)) or die($req->errorInfo());
	}
	
} catch (Exception $e) {
	echo "Erreur".$e->message();
}
?>
	<script type="text/javascript">
	alert('insrtion réussi');
	window.location="article.php";
	</script>