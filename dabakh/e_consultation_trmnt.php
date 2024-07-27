<?php
session_start();
include 'connexion.php';
$id_user=$_SESSION['prenom']." ".$_SESSION['nom'];
$etat="medecin";
if ($_SESSION['fonction']=="infirmier") 
{
	$etat='infirmier';
$date_consultation=$_POST['date_consultation'];
}
elseif($_SESSION['fonction']=='medecin' OR $_SESSION['fonction']=='administrateur')
{
	$etat='medecin';
$date_consultation=$_POST['date_consultation'];
}

if ($_SESSION['fonction']=="infirmier") 
{
	$poids=$_POST['poids'];
	$tension=$_POST['tension'];
	$pouls=$_POST['pouls'];
	$temperature=$_POST['temperature'];
	$glycemie=$_POST['glycemie'];
	$tdr=$_POST['tdr'];
	$spo2=$_POST['spo2'];
	//$ant_medicaux=htmlspecialchars($_POST['ant_medicaux']);
	//$ant_chirurgicaux=htmlspecialchars($_POST['ant_chirurgicaux']);
	//$traitement_cours=htmlspecialchars($_POST['traitement_cours']);
	$allergie=htmlspecialchars($_POST['allergie']);
	$plaintes=$_POST['plaintes'];
	$req=$db->prepare('UPDATE consultation SET date_consultation=?, poids=?, pouls=?, tension=?, temperature=?, etat=?, glycemie=?, tdr=?, spo2=?,  allergie=?,  plaintes=?, id_user_i=? WHERE id_consultation=?');
	$nbr=$req->execute(array($date_consultation, $poids, $pouls, $tension, $temperature, $etat, $glycemie, $tdr, $spo2,  $allergie,  $plaintes, $id_user, $_GET['id']))  or die(print_r($req->errorInfo()));
	$req->closeCursor();
	if ($nbr>0) {
	?>
	<script type="text/javascript">
		alert('Consultation enregistrée');
		window.location="l_attente.php";
	</script>
	<?php
	}
	else
	{
	?>
	<script type="text/javascript">
		alert('Erreur consultation non enregistrée');
		window.location="l_attente.php";
	</script>
	<?php
	}
}
elseif($_SESSION['fonction']=="medecin" OR $_SESSION['fonction']=="administrateur")
{
	//recupération du prix du service dispensé
	$id_service=$_POST['service'];
	$req=$db->prepare("SELECT pu FROM service WHERE id=?");
	$req->execute(array($id_service));
	$donnees=$req->fetch();
	$pu_sevice=$donnees['0'];
	$req->closeCursor();

	$poids=$_POST['poids'];
	$tension=$_POST['tension'];
	$pouls=$_POST['pouls'];
	$temperature=$_POST['temperature'];
	$spo2=$_POST['spo2'];
	$glycemie=$_POST['glycemie'];
	$tdr=$_POST['tdr'];
	$plaintes=$_POST['plaintes'];
	$histoire_maladie=htmlspecialchars($_POST['histoire_maladie']);
	$ant_medicaux=htmlspecialchars($_POST['ant_medicaux']);
	$ant_chirurgicaux=htmlspecialchars($_POST['ant_chirurgicaux']);
	$traitement_cours=htmlspecialchars($_POST['traitement_cours']);
	$allergie=htmlspecialchars($_POST['allergie']);
	$neurologie=htmlspecialchars($_POST['neurologie']);
	$hemodynamique=htmlspecialchars($_POST['hemodynamique']);
	$respiratoire=htmlspecialchars($_POST['respiratoire']);
	$autres_appareils=htmlspecialchars($_POST['autres_appareils']);
	$ecg=htmlspecialchars($_POST['ecg']);
	$biologie=htmlspecialchars($_POST['biologie']);
	$radiographie=htmlspecialchars($_POST['radiographie']);
	$tdm=htmlspecialchars($_POST['tdm']);
	$echographie=htmlspecialchars($_POST['echographie']);
	$autres_examen=htmlspecialchars($_POST['autres_examen']);
	$traitement=htmlspecialchars($_POST['traitement']);
	$evolution=htmlspecialchars($_POST['evolution']);
	$traitement_sortie=htmlspecialchars($_POST['traitement_sortie']);
	$resume=htmlspecialchars($_POST['resume']);

	if (isset($_POST['cout_produit'])) 
	{
		$cout=$_POST['cout_produit'];
	}
	else
	{
		$cout=0;
	}
	$montant=$cout+$pu_sevice;

	$req=$db->prepare('UPDATE consultation SET poids=?, pouls=?, tension=?, temperature=?, spo2=?, etat=?, glycemie=?, tdr=?, histoire_maladie=?, ant_medicaux=?, ant_chirurgicaux=?, traitement_cours=?, allergie=?, neurologie=?, hemodynamique=?, respiratoire=?, autres_appareils=?, ecg=?, biologie=?, radiographie=?, tdm=?, echographie=?, autres_examen=?, id_service=?, montant=?, reglement=?, traitement=?, evolution=?, traitement_sortie=?, resume=?, id_user_m=? WHERE id_consultation=?');
	$nbr=$req->execute(array($poids, $pouls, $tension, $temperature, $spo2, $etat, $glycemie, $tdr, $histoire_maladie, $ant_medicaux, $ant_chirurgicaux, $traitement_cours, $allergie, $neurologie, $hemodynamique, $respiratoire, $autres_appareils, $ecg, $biologie, $radiographie, $tdm, $echographie, $autres_examen, $id_service, $montant, 'non', $traitement, $evolution, $traitement_sortie, $resume, $id_user, $_GET['id']))  or die(print_r($req->errorInfo()));
	$req->closeCursor();
	if ($nbr>0) {
	?>
	<script type="text/javascript">
		alert('Consultation enregistrée');
		window.location="l_attente.php";
	</script>
	<?php
	}
	else
	{
	?>
	<script type="text/javascript">
		alert('Erreur consultation non enregistrée');
		window.location="l_attente.php";
	</script>
	<?php
	}

}
//secrétariat
else
{
  $id_service=$_POST['service'];
  $req=$db->prepare('UPDATE consultation SET  id_service=? WHERE id_consultation=?');
	$nbr=$req->execute(array($id_service, $_GET['id']))  or die(print_r($req->errorInfo()));
	$req->closeCursor();
	if ($nbr>0) {
	?>
	<script type="text/javascript">
		alert('Consultation enregistrée');
		window.location="l_attente.php";
	</script>
	<?php
	}
	else
	{
	?>
	<script type="text/javascript">
		alert('Erreur consultation non enregistrée');
		window.location="l_attente.php";
	</script>
	<?php
	}

}

?>