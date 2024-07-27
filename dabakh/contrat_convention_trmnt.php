<?php
session_start();
include 'connexion.php';
include 'supprim_accents.php';
$part1=htmlspecialchars($_POST['part1']);
$part2=htmlspecialchars($_POST['part2']);

$req=$db->prepare('UPDATE contrat SET part1=?, part2=? where id=1 ');
$nbr=$req->execute(array($part1, $part2)) or die(print_r($req->errorInfo()));
if ($nbr>0) {
?>
<script type="text/javascript">
	alert('Contrat modifié');
	window.history.go(-2);
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