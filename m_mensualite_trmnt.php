<?php

include 'connexion.php';
$type=$_POST['type'];
$mois=$_POST['mois'];

$req=$db->prepare('UPDATE mensualite SET montant=?, date_versement=?, mois=?, annee=?, type=? WHERE id= ?');
$req->execute(array($_POST['montant'], $_POST['date_versement'], $_POST['mois'], $_POST['annee'], $_POST['type'], $_GET['id']));
$nbr=$req->rowCount();

//recupération des infos du locataire
$req=$db->prepare("SELECT CONCAT(locataire.prenom,' ', locataire.nom), CONCAT(logement.designation,' situé à ',logement.adresse)
FROM logement, location, locataire, mensualite 
WHERE logement.id=location.id_logement AND location.id_locataire=locataire.id AND location.id=mensualite.id_location AND mensualite.id=?");
$req->execute(array($_GET['id']));
$donnees=$req->fetch();
$motif="";
if ($type=="avance") 
{
	$motif="Avance loyer ".$mois." de ".$donnees['0'];
}
elseif($type=="reliquat")
{

$motif="Reliquat loyer ".$mois." de ".$donnees['0'];
}
else
{
$motif="Loyer ".$mois." de ".$donnees['0'];

}
$req=$db->prepare('UPDATE caisse_immo SET montant=?, date_operation=?, motif=? WHERE id_mensualite=?');
$req->execute(array($_POST['montant'], $_POST['date_versement'], $motif, $_GET['id']));
?>
<script type="text/javascript">
	alert('Mensualité modifiée');
	window.location="l_mensualite_paye.php";
</script>
	