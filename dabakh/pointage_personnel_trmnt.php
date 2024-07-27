<?php
session_start();
include 'connexion.php';
$heure_fin=$_POST['heure_fin'];
$heure_debut=$_POST['heure_debut'];
$observation=$_POST['observation'];

$req=$db->prepare('SELECT id FROM pointage_personnel WHERE date_pointage=? AND id_personnel=?');
$req->execute(array($_POST['date_pointage'], $_SESSION['id']));
$donnee=$req->fetch();

if ($donnee['0']>0) 
{
	$req->closeCursor();
	$req=$db->prepare('UPDATE pointage_personnel SET heure_debut=?, heure_fin=?, observation=? WHERE id=?');
	$req->execute(array($heure_debut, $heure_fin, $observation, $donnee['0']));
	$nbr=$req->rowCount();
	
}

else
{
	$req=$db->prepare('INSERT INTO pointage_personnel(date_pointage, heure_debut, heure_fin, observation, id_personnel, structure) VALUES (?,?,?,?,?,?) ');
	$req->execute(array($_POST['date_pointage'], $_POST['heure_debut'], $_POST['heure_fin'], $observation, $_SESSION['id'], $_GET['p']));
	$nbr=$req->rowCount();
}

if ($nbr>0) {
	?>
	<script type="text/javascript">
		alert('Pointage enregistré');
		window.history.go(-2);
	</script>
	<?php
	}
	else
	{
	?>
	<script type="text/javascript">
		alert('Erreur pointage non enregistré');
		window.history.go(-2);
	</script>
	<?php
	}
?>