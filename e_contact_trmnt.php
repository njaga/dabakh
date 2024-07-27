<?php
session_start();
include 'connexion.php';
include 'supprim_accents.php';
$telephone=htmlspecialchars($_POST['telephone']);
$mail=htmlspecialchars(suppr_accents($_POST['mail']));
$contact=htmlspecialchars(ucwords(strtolower(suppr_accents($_POST['contact']))));
$autres_infos=htmlspecialchars(ucfirst(strtolower(suppr_accents($_POST['autres_infos']))));


$req=$db->prepare('INSERT INTO contact(contact, mail, autres_infos, telephone, structure) values (?,?,?,?,?) ');
$nbr=$req->execute(array($contact, $mail, $autres_infos, $telephone, $_SESSION['service'])) or die(print_r($req->errorInfo()));
if ($nbr>0) {
?>
<script type="text/javascript">
	alert('Contact enregistrée');
	window.location="l_contact.php";
</script>
<?php
}
else
{
?>
<script type="text/javascript">
	alert('Erreur contact non enregistrée');
	window.history.go(-1);
</script>
<?php
}
?>