<?php
session_start();
include 'connexion.php';
$frais_reparation=0;
$frais_judiciaire=0;
$autres_frais=0;
$montant=htmlspecialchars($_POST['montant']);
$mois=htmlspecialchars($_POST['mois']);
$annee=htmlspecialchars($_POST['annee']);
$date_versement=$_POST['date_versement'];

if ($_POST['frais_reparation']!="") 
{
	$frais_reparation=$_POST['frais_reparation'];
}
if ($_POST['frais_judiciaire']!="") 
{
	$frais_judiciaire=$_POST['frais_judiciaire'];
}
if ($_POST['autres_frais']!="") 
{
	$autres_frais=$_POST['autres_frais'];
}

//insertion de la mensualité dans la table bailleur
$req=$db->prepare('UPDATE mensualite_bailleur SET montant=?, frais_reparation=?, frais_judiciaire=?, autres_frais=?, mois=?, annee=?, date_versement=? WHERE id=?');
$req->execute(array($montant, $frais_reparation, $frais_judiciaire, $autres_frais, $mois, $annee, $date_versement, $_GET['id'])) or die(print_r($req->errorInfo()));
$nbr=$req->rowCount();
$num=$db->lastInsertId();
$req->closeCursor();

$req=$db->prepare("SELECT CONCAT(bailleur.prenom,' ', bailleur.nom)
FROM  bailleur 
WHERE id=?");
$req->execute(array($_GET['id_bailleur']));
$donnees=$req->fetch();
$motif="Payement du bailleur ".$donnees['0']." pour le mois de ".$mois;
//$motif="Payement du bailleur ".$donnees['0'];
$req->closeCursor();
//insertiondans la caisse
$montant=$montant-$frais_judiciaire-$frais_reparation-$autres_frais;
$req=$db->prepare('UPDATE caisse_immo SET  motif=?, montant=?,date_operation=? WHERE id_mensualite_bailleur=?');
$req->execute(array($motif, $montant, $date_versement,$_GET['id'])) or die(print_r($req->errorInfo()));
$nbr=$req->rowCount();
if ($nbr>0)
{
	?>
	<script type="text/javascript">
		alert('Mensualité du bailleur modifié');
		//window.location="l_bailleur.php";
	</script>
	<?php
	header('location:i_mensualite_bailleur.php?id='.$_GET['id']);
	//header('location:l_bailleur.php');
}
else
	{
?>
<script type="text/javascript">
	alert('Erreur mensualité non modifié');
	window.history.go(-1);
</script>
<?php
}


