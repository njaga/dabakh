<?php
session_start();	
include 'connexion.php';
$patient=htmlspecialchars($_GET['id']);
$prescription=htmlspecialchars($_POST['prescription']);
$date_prescription=htmlspecialchars($_POST['date_prescription']);
$heure_prescription=htmlspecialchars($_POST['heure_prescription']);
$date_medecin=date("Y/m/d")." - ".date("h:i:sa");
$etat="non";
/*
$req=$db->prepare('SELECT COUNT(*) FROM prescription WHERE id_patient=? AND date_prescription=? AND heure_prescription=?');
$req->execute(array($patient, $date_prescription, $heure_prescription));
$donnees=$req->fetch();
if ($donnees['0']!=0) 
{
?>
<script type="text/javascript">
	alert('Prescription déjà enregistrée');
	window.history.go(-1);
</script>
<?php
}
*/
	$req=$db->prepare('INSERT INTO prescription(prescription, id_patient, id_docteur,etat,date_prescription, heure_prescription, date_medecin) values (?,?,?,?,?,?,?) ');
	$nbr=$req->execute(array($prescription, $patient, $_SESSION['id'], $etat, $date_prescription, $heure_prescription,$date_medecin)) or die(print_r($req->errorInfo()));
	if ($nbr>0) {
	?>
	<script type="text/javascript">
		if (!confirm('Prescription enregistrée. Voulez-vous refaire une autre prescription avec ce patient ?')) {
			window.location="l_prescription.php";
            }
            else
            {
            	
			window.history.go(-1);
            }
	</script>
	<?php
	}
	else
	{
	?>
	<script type="text/javascript">
		alert('Erreur operation non enregistrée');
		window.location="l_prescription.php";
	</script>
	<?php
	}	
	

?>
