<?php
session_start();
include 'connexion.php';
include 'supprim_accents.php';
$motif=htmlspecialchars(ucfirst(strtolower(suppr_accents($_POST['motif']))));
$mois=htmlspecialchars($_POST['mois']);
$annee=htmlspecialchars($_POST['annee']);
$type_depense=htmlspecialchars($_POST['type_depense']);
$date_depense=htmlspecialchars($_POST['date_depense']);
$montant=htmlspecialchars($_POST['montant']);
//$id_user=substr($_SESSION['prenom'], 0,1).".".substr($_SESSION['nom'], 0,1);
$id_user=$_SESSION['prenom']." ".$_SESSION['nom'];


$req=$db->query('SELECT MAX(id) FROM cotisation_locataire');
$donnees= $req->fetch();

$id_cotisation_locataire=$donnees['0']+1;
$req=$db->prepare('INSERT INTO cotisation_locataire(id, motif, mois, annee, type_depense, date_depense, montant, id_locataire, id_user) values (?,?, ?, ?, ?, ?, ?, ?, ?) ');
$req->execute(array($id_cotisation_locataire,$motif, $mois, $annee, $type_depense, $date_depense, $montant,  $_GET['id'], $id_user)) or die(print_r($req->errorInfo()));
$id_cotisation_locataire=$db->lastInsertId();
$nbr=$req->rowCount();

$req=$db->prepare('INSERT INTO caisse_immo(type, section, motif, montant,date_operation, id_cotisation_locataire, id_user) values ("entree","Cotisation locataire",?,?,?,?,?) ');
$req->execute(array($motif, $montant, $date_depense, $id_cotisation_locataire, $id_user)) or die(print_r($req->errorInfo()));
if ($nbr>0) {
?>
<script type="text/javascript">
	alert('Cotisation enregistrée');
	window.location="l_cotisation_locataire.php";
</script>
<?php
}
else
{
?>
<script type="text/javascript">
	alert('Erreur Cotisation non enregistrée');
	window.history.go(-1);
</script>
<?php
}
?>