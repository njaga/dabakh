<?php
include 'connexion.php';
$erreur=0;
$db->beginTransaction();
$req=$db->prepare('SELECT id_logement, id_locataire FROM location WHERE id=?');
$verif=$req->execute(array($_GET['id']));
$donnee=$req->fetch();
if (!$verif) {
	$erreur=1;
}
$id_logement=$donnee['0'];
$id_locataire=$donnee['1'];

$req=$db->prepare('UPDATE location SET etat="inactive", date_fin=now() WHERE id=?');
$verif=$req->execute(array($_GET['id']));
if (!$verif) {
	$erreur=2;
}

$req=$db->prepare('SELECT nbr, nbr_occupe FROM `logement`WHERE id=?');
$verif=$req->execute(array($id_logement));
$donnees=$req->fetch();
if (!$verif) {
	$erreur=3;
}
$nbr=$donnees['0']+1;
$nbr_occupe=$donnees['1']-1;

$req=$db->prepare('UPDATE logement SET nbr=?, nbr_occupe=? WHERE id=?');
$verif=$req->execute(array($nbr, $nbr_occupe, $id_logement));
if (!$verif) {
	$erreur=4;
}

$req=$db->prepare('UPDATE locataire SET statut="inactif" WHERE id=?');
$verif=$req->execute(array($id_locataire));
if (!$verif) {
	$erreur=5;
}

if ($erreur==0) 
{
	$db->commit();
	?>
	<script type="text/javascript">
		alert('Contrat de location résilier');
		window.history.go(-1);
	</script>
	<?php
}
else
{
	$db->rollback();
	echo "$erreur";
	?>
	<script type="text/javascript">
		alert('Erreur : dossier non enregistré');
		window.history.go(-1);
	</script>
	<?php
}
?>