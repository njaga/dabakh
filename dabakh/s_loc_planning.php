<?php
include 'connexion.php';
$req=$db->prepare("DELETE FROM planning_recouvrement WHERE id=?");
$req->execute(array($_GET['s'])) or die($req->errorInfo());
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
