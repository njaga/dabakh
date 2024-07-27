<?php
include 'connexion.php';
$req=$db->prepare("DELETE FROM compte_rendu WHERE id=? ");
$req->execute(array($_GET['id']));

$nbr=$req->rowCount();
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