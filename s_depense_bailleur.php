<?php
include 'connexion.php';
$req=$db->prepare("DELETE FROM depense_bailleur WHERE id=?");
$req->execute(array($_GET['id'])) or die($req->errorInfo());
$nbr=$req->rowCount();

$req=$db->prepare('DELETE FROM caisse_immo  WHERE id_depense_bailleur=?');
$req->execute(array($_GET['id'])) or die($req->errorInfo());
$req->fetch();

$req=$db->prepare('DELETE FROM banque  WHERE id_depense_bailleur=?');
$req->execute(array($_GET['id'])) or die($req->errorInfo());
$req->fetch();

if ($nbr>0) 
{
	?>
	<script type="text/javascript">
		alert("Suppression effectuée");
		window.history.go(-1);
	</script>
	<?php
};
?>
