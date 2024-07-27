<?php
session_start();
include 'connexion.php';
$etat='oui';
$date_infirmier=date("Y/m/d")." - ".date("h:i:sa");
$req=$db->prepare('UPDATE prescription SET etat=?, id_infirmier=?, date_infirmier=? WHERE id=?');
$nbr=$req->execute(array($etat,$_SESSION['id'],$date_infirmier,$_GET['id']));
if ($nbr>0) {
	?>
	<script type="text/javascript">
		alert('Prescription appliquée');
		window.location="l_prescription.php";
	</script>
	<?php
	}
	else
	{
	?>
	<script type="text/javascript">
		alert('Erreur prescription non enregistrée');
		window.location="l_prescription.php";
	</script>
	<?php
	}
?>