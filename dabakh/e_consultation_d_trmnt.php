<?php
session_start();
include 'connexion.php';
//recupération du prix du service dispensé

$id_consultation=$_POST['consultation'];
$date_consultation=$_POST['date_consultation'];
$poids=$_POST['poids'];
$tension=$_POST['tension'];
$pouls=$_POST['pouls'];
$temperature=$_POST['temperature'];
$dextro=$_POST['dextro'];
$tdr=$_POST['tdr'];
$cout_soins=$_POST['cout_soins'];
$cout_produit=$_POST['cout_produit'];
$allergie=htmlspecialchars(ucfirst(strtolower($_POST['allergie'])));
$plaintes=htmlspecialchars(ucfirst(strtolower($_POST['plaintes'])));
$remarques=htmlspecialchars(ucfirst(strtolower($_POST['remarques'])));
if ($cout_soins=="") 
{
	$cout_soins=0;
}
if ($cout_produit=="") 
{
	$cout_produit=0;
}
$montant=$cout_produit+$cout_soins;

$req=$db->prepare('UPDATE `consultation_domicile` SET `date_consultation`=?, `poids`=?, `tension`=?, `pouls`=?, `temperature`=?, dextro=?, `tdr`=?, `montant`=?, `reglement`="non", `id_patient`=?, allergie=?, plaintes=?, remarques=? WHERE id_consultation=?');
$nbr=$req->execute(array($date_consultation, $poids, $tension, $pouls, $temperature, $dextro, $tdr, $montant, $_GET['p'], $allergie, $plaintes, $remarques, $id_consultation))  or die(print_r($req->errorInfo()));
	$req->closeCursor();
	if ($nbr>0) {
	?>
	<script type="text/javascript">
		alert('Soins enregistrés');
		window.location="";
	</script>
	<?php
	header("location:p_qualite_sante.php?id=".$id_consultation);
	}
	else
	{
	?>
	<script type="text/javascript">
		alert('Erreur consultation non enregistrée');
		window.history.go(-1);
	</script>
	<?php
	}


?>