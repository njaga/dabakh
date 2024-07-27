<?php
session_start();	
include 'connexion.php';
$patient=htmlspecialchars($_GET['id']);
$date_certificat=htmlspecialchars($_POST['date_certificat']);
$date_debut=htmlspecialchars($_POST['date_debut']);
$nbr_jours=htmlspecialchars($_POST['nbr_jours']);
$motif=htmlspecialchars($_POST['motif']);
$id_user=$_SESSION['prenom']." ".$_SESSION['nom'];


	$req=$db->prepare('UPDATE certificat_medical SET date_certificat=?,  date_debut=?, nbr_jours=?, motif=?, id_patient=?, id_user=? WHERE id=?');
	$nbr=$req->execute(array($date_certificat, $date_debut, $nbr_jours, $motif, $patient, $id_user, $_GET['id'])) or die(print_r($req->errorInfo()));
	if ($nbr>0) {
	?>
	<script type="text/javascript">
	    alert("Certificat médical modifé.");
        <?php
            header("location:i_certificat_med.php?id=".$_GET['id'])
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
