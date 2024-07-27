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
$type_paiement=htmlspecialchars($_POST['type_paiement']);
$num_cheque=htmlspecialchars($_POST['num_cheque']);
$id_user=$_SESSION['prenom']." ".$_SESSION['nom'];

$req=$db->prepare('INSERT INTO depense_bailleur(motif, mois, annee, type_depense, date_depense, montant, type_paiement, id_bailleur, id_user) values (?, ?, ?, ?, ?, ?, ?, ?, ?) ');
$req->execute(array($motif, $mois, $annee, $type_depense, $date_depense, $montant, $type_paiement, $_GET['id'], $id_user)) or die(print_r($req->errorInfo()));
$id_depense_bailleur=$db->lastInsertId();
$nbr=$req->rowCount();

if ($type_paiement=="caisse") 
{
	$req=$db->prepare('INSERT INTO caisse_immo(type, section, motif, montant,date_operation, id_depense_bailleur, id_user) values ("sortie","Depense bailleur",?,?,?,?,?) ');
	$req->execute(array($motif, $montant, $date_depense, $id_depense_bailleur, $id_user)) or die(print_r($req->errorInfo()));
}
else
{
	$req=$db->prepare('INSERT INTO banque(type, section, motif, montant,date_operation, id_depense_bailleur, num_cheque, structure, id_user) values ("sortie","Depense bailleur",?,?,?,?,?,"immobilier",?) ');
	$req->execute(array($motif, $montant, $date_depense, $id_depense_bailleur, $num_cheque, $id_user)) or die(print_r($req->errorInfo()));	
}
if ($nbr>0) {
?>
<script type="text/javascript">
	alert('Dépense enregistrée');
	window.location="l_depenses_bailleur.php";
</script>
<?php
}
else
{
?>
<script type="text/javascript">
	alert('Erreur dépense non enregistrée');
	window.history.go(-1);
</script>
<?php
}
?>