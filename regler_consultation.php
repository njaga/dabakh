<?php
session_start();
include 'connexion.php';
$id_user=$_SESSION['prenom']." ".$_SESSION['nom'];

$error1=false;
$error3=false;
$db->beginTransaction();
$req=$db->prepare('UPDATE consultation SET reglement="oui" WHERE id_consultation=?');
$req->execute(array($_GET['id']));
$nbr1=$req->rowCount();
$req->closeCursor();
if ($nbr1<0) {
	$error1=true;
}

$req=$db->prepare('SELECT montant FROM consultation WHERE id_consultation=?');
$req->execute(array($_GET['id']));
$donnees=$req->fetch();
$montant=$donnees['0'];
$nbr2=$req->rowCount();
$req->closeCursor();
if ($nbr2<0) {
	$error2=true;
}

$motif='Reglement facture consultation ';
//$motif='Reglement facture consultation '.str_pad($_GET['id'], 3, "0", STR_PAD_LEFT);
$date_operation=date("Y-m-d");
$req=$db->prepare('INSERT INTO caisse_sante (type, motif, section, date_operation, montant, id_consultation, id_user) VALUES (?,?,?,?,?,?,?)');
$req->execute(array('entree',$motif,'Reglement facture',$date_operation, $montant, $_GET['id'], $id_user));
$nbr3=$req->rowCount();
$req->closeCursor();
if ($nbr3<0) {
	$error3=true;
}

$db->commit();
?>
<script type="text/javascript">
	alert("Facture réglée !");
	window.location='l_consultation.php';
</script>
