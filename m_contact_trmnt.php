<?php
session_start();
include 'connexion.php';
include 'supprim_accents.php';
$telephone=htmlspecialchars($_POST['telephone']);
$mail=htmlspecialchars(strtolower(suppr_accents($_POST['mail'])));
$contact=htmlspecialchars(ucwords(strtolower(suppr_accents($_POST['contact']))));
$autres_infos=htmlspecialchars(ucfirst(strtolower(suppr_accents($_POST['autres_infos']))));


$req=$db->prepare('UPDATE contact SET contact=?, mail=?, autres_infos=?, telephone=?WHERE id=? ');
$nbr=$req->execute(array($contact, $mail, $autres_infos, $telephone, $_GET['id'])) or die(print_r($req->errorInfo()));
?>
<script type="text/javascript">
	alert('Contact modifié');
	window.location="l_contact.php";
</script>
	