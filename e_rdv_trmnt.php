<?php

$date_rdv=$_POST['date_rdv'];
$heure_rdv=$_POST['heure_rdv'];
$date_prescription=date('Y').'-'.date('m').'-'.date('d');
$id_patient=$_GET['id_patient'];
include 'connexion.php';
$req=$db->prepare('INSERT INTO rdv (date_rdv, heure_rdv, date_prescription, id_patient) VALUES(?,?,?,?)');
$nbr=$req->execute(array($date_rdv, $heure_rdv, $date_prescription, $id_patient))  or die(print_r($req->errorInfo()));
if ($nbr>0) {
?>
?>
<script type="text/javascript">
	alert('Rendez-vous enregistrée');
	window.location="l_rdv.php";
</script>
<?php
}
else
{
?>
<script type="text/javascript">
	alert('Consultation non enregistrée');
	window.location="l_rdv.php";
</script>
<?php
}
?>