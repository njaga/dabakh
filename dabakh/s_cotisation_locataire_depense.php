<?php
include 'connexion.php';
$nbr=0;
if (isset($_GET['id'])) 
{
	$req=$db->prepare('DELETE FROM cotisation_locataire_depense WHERE id=?');
	$req->execute(array($_GET['id'])) or die(print_r($req->errorInfo()));;
	$req->fetch();
	$req->closeCursor();
	$nbr=$req->rowCount();
    $id=0;
    $req_cotisation=$db->prepare("UPDATE cotisation_locataire SET id_cotisation_depense=? WHERE id_cotisation_depense=?");
    $req_cotisation->execute(array($id, $_GET['id']));
    
    
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
