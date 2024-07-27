<?php
include 'connexion.php';
$req=$db->prepare("DELETE FROM ravitaillement_produit WHERE id=? ");
$req->execute(array($_GET['id']));
$nbr=$req->rowCount();

$req=$db->prepare("UPDATE produit SET qt=?, id_ravitaillement=0 WHERE id_ravitaillement=? ");
$req->execute(array($_GET['a_qt'],$_GET['id']));
//header("location:m_consultation.php?id=".$id) ;
echo $_GET['id'];
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