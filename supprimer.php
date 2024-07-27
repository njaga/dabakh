<?php
include 'connexion.php';
//patient
if (isset($_GET['id_patient'])) 
{
	$req=$db->prepare('DELETE FROM patient  WHERE id_patient=?');
	$req->execute(array($_GET['id_patient'])) or die(print_r($req->errorInfo()));;
	$req->fetch();
	$req->closeCursor();
	
	?>
	<script type="text/javascript">
		alert("Patient supprimé ! ");
		window.location='l_patient.php';
	</script>
	<?php
}

//bailleur
if (isset($_GET['id_bailleur'])) 
{
	$req=$db->prepare('SELECT COUNT(*)
	FROM bailleur, location, logement
	WHERE bailleur.id=logement.id_bailleur AND logement.id=location.id_logement AND bailleur.id=?');
	$req->execute(array($_GET['id_bailleur'])) or die(print_r($req->errorInfo()));;
	$donnee=$req->fetch();
	$nbr=$donnee['0'];
	$req->closeCursor();
	if ($nbr<1) 
	{
		$req=$db->prepare('UPDATE  bailleur set etat="desactiver" WHERE id=?');
		$req->execute(array($_GET['id_bailleur'])) or die(print_r($req->errorInfo()));;
		$req->fetch();
		$req->closeCursor();
		
		?>
		<script type="text/javascript">
			alert("Bailleur supprimé ! ");
			window.location='l_bailleur.php';
		</script>
		<?php
	}
	else
	{
		?>
		<script type="text/javascript">
			alert("Veillez d'abord désactiver toutes les locations active pour ce bailleur avant de le supprimer ! ");
			window.history.go(-1);
		</script>
		<?php
	}
	
}
?>