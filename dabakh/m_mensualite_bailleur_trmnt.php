<?php
include 'connexion.php';
$req=$db->prepare('UPDATE mensualite_bailleur SET  date_versement=?, mois=?, annee=? WHERE id= ?');
$req->execute(array( $_POST['date_versement'], $_POST['mois'], $_POST['annee'], $_GET['id']));
$nbr=$req->rowCount();
if ($nbr>0)
{	
	?>
	<script type="text/javascript">
		alert('Mensualité modifiée');
		window.history.go(-2);
	</script>
	<?php
}
else
	{
?>
<script type="text/javascript">
	alert('Mensualité non modifiée');
	window.history.go(-1);
</script>
<?php
}

?>