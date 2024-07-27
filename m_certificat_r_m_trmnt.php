<?php
session_start();	
include 'connexion.php';
$patient=htmlspecialchars($_GET['id']);
$date_certificat=htmlspecialchars($_POST['date_certificat']);
$type=htmlspecialchars($_POST['type']);
$date_debut=htmlspecialchars($_POST['date_debut']);
$nbr_jours=htmlspecialchars($_POST['nbr_jours']);
$motif=htmlspecialchars($_POST['motif']);
$id_user=$_SESSION['prenom']." ".$_SESSION['nom'];


	$req=$db->prepare('UPDATE certificat_repos SET date_certificat=?, type=?, date_debut=?, nbr_jours=?, motif=?, id_patient=?, id_user=? WHERE id=?');
	$nbr=$req->execute(array($date_certificat, $type, $date_debut, $nbr_jours, $motif, $patient, $id_user, $_GET['id'])) or die(print_r($req->errorInfo()));
	if ($nbr>0) {
	?>
	<script type="text/javascript">
	    alert("Certificat de repos modifé.");
        <?php
            header("location:i_certificat_r_m.php?id=".$_GET['id'])
        ?>
	</script>
	<?php
	}
	else
	{
	?>
	<script type="text/javascript">
		alert('Erreur certificat non modifié');
		window.history.go(-1);
	</script>
	<?php
	}	
	

?>
