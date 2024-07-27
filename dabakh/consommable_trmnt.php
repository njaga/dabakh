<?php
session_start();
include 'supprim_accents.php';
$consommable=suppr_accents(ucfirst(strtolower(htmlspecialchars($_POST['consommable']))));
$pu=$_POST['pu'];
$qt=$_POST['qt'];
$montant=$qt*$pu;
$date_ravitaillement=date('y')."-".date('m')."-".date('d');
$id_user=$_SESSION['prenom']." ".$_SESSION['nom'];

try {
	include 'connexion.php';
	$req=$db->query('SELECT COUNT(*) FROM ravitaillement_consommable');
	$donnee=$req->fetch();
	$id_ravitaillement=$donnee['0'];

	$req=$db->prepare('INSERT INTO consommable(consommable, pu, qt, id_ravitaillement) values (?,?, ?, ?) ');
	$req->execute(array($consommable, $pu, $qt, $id_ravitaillement)) or die(print_r($req->errorInfo()));
	$id_consommable=$db->lastInsertId();

	$req=$db->prepare('INSERT INTO ravitaillement_consommable (date_ravitaillement, qt, ancien_qt, id_consommable, montant, id_user) VALUES (?,?,?,?,?,?)');
	$req->execute(array($date_ravitaillement, $qt, 0, $id_consommable, $montant, $id_user)) or die(print_r($req->errorInfo()));
	} catch (Exception $e) {
	echo "Erreur".$e->message();
}
?>
	<script type="text/javascript">
	alert('insrtion réussi');
	window.location="consommable.php";
	</script>