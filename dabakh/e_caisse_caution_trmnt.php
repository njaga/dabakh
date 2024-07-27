<?php
session_start();
include 'connexion.php';
include 'supprim_accents.php';
$section="";
$motif=htmlspecialchars(ucfirst(strtolower(suppr_accents($_POST['motif']))));
$montant=htmlspecialchars($_POST['montant']);
$type=htmlspecialchars($_POST['type']);
$pj=htmlspecialchars($_POST['pj']);
$id_location=htmlspecialchars($_POST['id_location']);
$date_operation=htmlspecialchars($_POST['date_operation']);
$id_user=$_SESSION['prenom']." ".$_SESSION['nom'];
if ($type=="entree") 
{
	$section="Caution";
}
else
{
	$section="depense locataire";
}
$req=$db->prepare('INSERT INTO caisse_caution(type, section, motif, montant,date_operation, id_location, id_user) values (?,?,?,?,?,?,?) ');
$nbr=$req->execute(array($type, $section, $motif, $montant, $date_operation, $id_location, $id_user)) or die(print_r($req->errorInfo()));
$id_caisse_caution=$db->lastInsertId();

//Insertion dans la caisse immo
$req=$db->prepare('INSERT INTO caisse_immo(type, section, motif, montant,date_operation, id_caisse_caution, pj, id_user) values (?,?,?,?,?,?,?,?) ');
$nbr=$req->execute(array($type, $section, $motif, $montant, $date_operation, $id_caisse_caution, $pj, $id_user)) or die(print_r($req->errorInfo()));
if ($nbr>0) {
?>
<script type="text/javascript">
	alert('Opération enregistrée');
	window.location="etat_caisse_caution.php";
</script>
<?php
}
else
{
?>
<script type="text/javascript">
	alert('Erreur operation non enregistrée');
	window.history.go(-1);
</script>
<?php
}
?>