<?php
include 'connexion.php';
$id_bailleur=(int) $_GET['id'];
$id_logement=(int) $_GET['log'];
$req=$db->prepare('UPDATE logement SET id_bailleur=? WHERE id=?');
$req->execute(array($id_bailleur, $id_logement)) or die($req->errorInfo());
$req->closeCursor();
//header("location:m_consultation.php?id=".$id) ;
$nbr=$req->rowCount();
if ($nbr>0) 
{
	?>
	<script type="text/javascript">
		alert("Logement transférer");
		window.history.go(-2);
	</script>
	<?php
};
?>