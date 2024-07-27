<?php
session_start();
include 'connexion.php';
include 'supprim_accents.php';
$date_cmpt=htmlspecialchars($_POST['date_cmpt']);
$compte_rendu=htmlspecialchars(suppr_accents($_POST['compte_rendu']));

$req=$db->prepare('INSERT INTO compte_rendu(compte_rendu, date_redaction, id_personnel) values (?,?,?) ');
$req->execute(array($compte_rendu, $date_cmpt, $_SESSION['id'])) or die(print_r($req->errorInfo()));
$nbr=$req->rowCount();
if ($nbr>0) 
{
	header("location:l_compte_rendu.php");

}
else
{
?>
<script type="text/javascript">
	alert('Erreur compte rendu non enregistré');
	window.history.go(-1);
</script>
<?php
}
?>