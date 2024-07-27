<?php
include 'connexion.php';
$id=htmlspecialchars($_GET['id']);
$etat=htmlspecialchars($_GET['etat']);
if ($etat=="activer") 
{
	$etat="desactiver";
}
else
{
	$etat="activer";
}

//recupération des différents locations
$req_location = $db->prepare("SELECT location.id
FROM location
INNER JOIN logement ON location.id_logement=logement.id
INNER JOIN bailleur ON logement.id_bailleur=bailleur.id
WHERE bailleur.id=?");
$req_location->execute(array($id));
while($donnees_location = $req_location->fetch())
{
	$id_location = $donnees_location['0'];

	$req=$db->prepare('SELECT id_logement, id_locataire FROM location WHERE id=?');
	$verif=$req->execute(array($id_location));
	$donnee=$req->fetch();
	
	$id_logement=$donnee['0'];
	$id_locataire=$donnee['1'];

	$req=$db->prepare('UPDATE location SET etat="inactive", date_fin=now() WHERE id=?');
	$verif=$req->execute(array($id_location));
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
}

$req=$db->prepare('UPDATE bailleur SET etat=? WHERE id=? ');
$req->execute(array($etat,$id)) or die(print_r($req->errorInfo()));
$nbr=$req->rowCount();
?>
<script type="text/javascript">
	alert('bailleur <?=$etat?>');
	window.location="l_bailleur.php";
</script>