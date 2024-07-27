<?php
include 'connexion.php';
$nbr=0;
if (isset($_GET['id'])) 
{
	$req=$db->prepare("DELETE FROM mensualite_bailleur WHERE id=?");
	$req->execute(array($_GET['id'])) or die($req->errorInfo());
	$nbr=$req->rowCount();

	$req=$db->prepare("DELETE FROM banque WHERE id_mensualite_bailleur=?");
	$req->execute(array($_GET['id'])) or die($req->errorInfo());

	$req=$db->prepare("DELETE FROM caisse_immo WHERE id_mensualite_bailleur=?");
	$req->execute(array($_GET['id'])) or die($req->errorInfo());
	
	$req=$db->prepare("UPDATE `mensualite` SET `id_mensualite_bailleur` = '0' WHERE `mensualite`.`id_mensualite_bailleur` = ?;");
	$req->execute(array($_GET['id'])) or die($req->errorInfo());
	
	$req=$db->prepare("UPDATE `depense_bailleur` SET `id_mensualite_bailleur` = '0' WHERE `depense_bailleur`.`id_mensualite_bailleur` = ?;");
	$req->execute(array($_GET['id'])) or die($req->errorInfo());
}

if ($nbr>0) 
{
	?>
	<script type="text/javascript">
		alert("Suppression effectuée");
		window.history.go(-1);
	</script>
	<?php
}
else
{
	?>
	<script type="text/javascript">
		alert("Erreur : DX*001. Suppression impossible. Si l'erreur persite contacter votre administrateur");
		window.history.go(-1);
	</script>
	<?php
}
?>
