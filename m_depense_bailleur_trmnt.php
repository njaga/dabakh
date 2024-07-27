<?php
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
$id_depense_bailleur=$_GET['id'];
$req=$db->prepare('UPDATE depense_bailleur SET motif=?, mois=?, annee=?, type_depense=?, date_depense=?, montant=?, type_paiement=? WHERE id=?');
$req->execute(array($motif, $mois, $annee, $type_depense, $date_depense, $montant, $type_paiement, $id_depense_bailleur)) or die(print_r($req->errorInfo()));
$nbr=$req->rowCount();

if ($type_paiement=="caisse") 
{
	$req=$db->prepare('UPDATE caisse_immo SET type="sortie", section="Depense bailleur", motif=?, montant=?, date_operation=? WHERE id_depense_bailleur=?');
	$req->execute(array($motif, $montant, $date_depense, $id_depense_bailleur)) or die(print_r($req->errorInfo()));
}
else
{
	$req=$db->prepare('UPDATE banque SET type="sortie", section="Depense bailleur", motif=?, montant=?,date_operation=?, num_cheque=?, structure="immobilier" WHERE id_depense_bailleur=?');
	$req->execute(array($motif, $montant, $date_depense, $num_cheque, $id_depense_bailleur)) or die(print_r($req->errorInfo()));	
}
?>
<script type="text/javascript">
	alert('Dépense modifiée');
	window.location="l_depenses_bailleur.php";
</script>
