<?php
include 'connexion.php';
if (isset($_GET['id_cons_d'])) 
{
	$req=$db->prepare("DELETE FROM vente_produit WHERE id_consultation_domicile=? AND id_produit=?");
$req->execute(array($_GET['id_cons_d'], $_GET['id_prod']));
}
if(isset($_GET['id_cons']))
{
	$req=$db->prepare("DELETE FROM vente_produit WHERE id_consultation=? AND id_produit=?");
	$req->execute(array($_GET['id_cons'], $_GET['id_prod']));
}

$req=$db->prepare('UPDATE produit SET qt=qt+? WHERE id=?');
$req->execute(array($_GET['qt'], $_GET['id_prod'])) or die($req->errorInfo());
$req->closeCursor();
//header("location:m_consultation.php?id=".$id) ;
$nbr=$req->rowCount();
if ($nbr>0) 
{
	?>
	<script type="text/javascript">
		//alert("supprimer");
		window.history.go(-1);
	</script>
	<?php
};
?>