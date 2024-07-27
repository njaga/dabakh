<?php
include 'connexion.php';
$id=(int) $_GET['s'];
$req=$db->prepare('UPDATE produit SET etat=1 WHERE id=?');
$req->execute(array($id)) or die($req->errorInfo());
$req->closeCursor();
//header("location:m_consultation.php?id=".$id) ;
$nbr=$req->rowCount();
if ($nbr>0) 
{
	?>
	<script type="text/javascript">
		alert("Produit supprimé");
		window.history.go(-1);
	</script>
	<?php
};
?>