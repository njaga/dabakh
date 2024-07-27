<?php
include 'connexion.php';
$req=$db->prepare("DELETE FROM contact WHERE id=? ");
$req->execute(array($_GET['id']));

//header("location:m_consultation.php?id=".$id) ;
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