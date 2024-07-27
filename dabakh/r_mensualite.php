<?php
include 'connexion.php';
$req=$db->prepare("UPDATE mensualite SET id_mensualite_bailleur=0 WHERE id=?");
$req->execute(array($_GET['id']));

$nbr=$req->rowCount();
if ($nbr>0) 
{
	?>
	<script type="text/javascript">
		alert("Mensualité restaurer");
		window.history.go(-1);
	</script>
	<?php
};
?>