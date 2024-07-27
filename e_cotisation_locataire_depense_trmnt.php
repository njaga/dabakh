<?php
session_start();
include 'connexion.php';
include 'supprim_accents.php';
$motif=htmlspecialchars(ucfirst(strtolower(suppr_accents($_POST['motif']))));
$montant_a_payer=htmlspecialchars($_POST['montant_a_payer']);
$date_depense=htmlspecialchars($_POST['date_depense']);
$montant_payer=htmlspecialchars($_POST['montant']);
//$id_user=substr($_SESSION['prenom'], 0,1).".".substr($_SESSION['nom'], 0,1);
$id_user=$_SESSION['prenom']." ".$_SESSION['nom'];
$reliquat=$montant_a_payer-$montant_payer;

$req=$db->prepare('INSERT INTO cotisation_locataire_depense( date_depense, motif, montant_a_regler, montant_regler, reliquat, id_bailleur, id_user) values (?,?,?,?,?,?,?)');
$req->execute(array($date_depense, $motif, $montant_a_payer, $montant_payer, $reliquat, $_GET['id'], $id_user)) or die(print_r($req->errorInfo()));
$id_cotisation_depense=$db->lastInsertId();
$nbr=$req->rowCount();

$req=$db->prepare('SELECT cotisation_locataire.id 
FROM `cotisation_locataire` 
INNER JOIN locataire ON cotisation_locataire.id_locataire=locataire.id
INNER JOIN location ON location.id_locataire=locataire.id
INNER JOIN logement ON location.id_logement=logement.id
INNER JOIN bailleur ON logement.id_bailleur=bailleur.id
WHERE cotisation_locataire.id_cotisation_depense=0 AND bailleur.id=?');
$req->execute(array($_GET['id'])) or die(print_r($req->errorInfo()));
while($donnees=$req->fetch())
{
    $id=$donnees['0'];
    $req_cotisation=$db->prepare("UPDATE cotisation_locataire SET id_cotisation_depense=? WHERE id=?");
    $req_cotisation->execute(array($id_cotisation_depense, $id));
}

$req=$db->prepare('INSERT INTO caisse_immo(type, section, motif, montant,date_operation, id_cotisation_depense, id_user) values ("sortie","Depense cotisation locataire",?,?,?,?,?) ');
$req->execute(array($motif, $montant_payer, $date_depense, $id_cotisation_depense, $id_user)) or die(print_r($req->errorInfo()));

?>
<script type="text/javascript">
	alert('Cotisation enregistrée');
	window.location="l_cotisation_locataire_depense.php";
</script>
