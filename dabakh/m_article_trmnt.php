<?php
include 'supprim_accents.php';
$article=suppr_accents(ucfirst(strtolower(htmlspecialchars($_POST['article']))));
$pu=$_POST['pu'];
try 
{
	include 'connexion.php';
	$req=$db->prepare('UPDATE article SET article=?, pu=? WHERE id=?');
	$req->execute(array($article, $pu, $_GET['id'])) or die(print_r($req->errorInfo()));
	
	
	} catch (Exception $e) {
	echo "erreu".$e->message();
}
?>
	<script type="text/javascript">
	alert('Modification réussi');
	window.location="article.php";
	</script>