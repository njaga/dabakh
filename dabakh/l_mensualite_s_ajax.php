<?php	
session_start();				
include 'connexion.php';
$mois=$_POST['mois'];
$annee=$_POST['annee'];
$search=$_POST['search'];
$bailleur=$_POST['bailleur'];
$nbr=0;
$total=0;
$db->query("SET lc_time_names = 'fr_FR';");
if ($search=="") 
{
	if ($bailleur==0) 
	{
		$reponse=$db->prepare("SELECT mensualite.id, CONCAT(locataire.prenom,' ', locataire.nom), CONCAT(type_logement.type_logement,' à ',logement.adresse), mensualite.montant, mensualite.mois, CONCAT(DATE_FORMAT(mensualite.date_versement, '%d'), '/', DATE_FORMAT(mensualite.date_versement, '%m'),'/', DATE_FORMAT(mensualite.date_versement, '%Y')), CONCAT(bailleur.prenom,' ', bailleur.nom), mensualite.type, location.prix_location, mensualite.id_user, mensualite.id_mensualite_bailleur 
		FROM `mensualite`,logement, locataire, location, bailleur, type_logement 
		WHERE location.id_locataire=locataire.id AND location.id_logement=logement.id AND type_logement.id = logement.id_type AND location.id=mensualite.id_location AND logement.id_bailleur=bailleur.id AND location.etat='active' AND mensualite.mois=? AND mensualite.annee=? AND mensualite.id_mensualite_bailleur=1");
		$reponse->execute(array($mois, $annee));
	}
	else
	{
		$reponse=$db->prepare("SELECT mensualite.id, CONCAT(locataire.prenom,' ', locataire.nom), CONCAT(type_logement.type_logement,' à ',logement.adresse), mensualite.montant, mensualite.mois, CONCAT(DATE_FORMAT(mensualite.date_versement, '%d'), '/', DATE_FORMAT(mensualite.date_versement, '%m'),'/', DATE_FORMAT(mensualite.date_versement, '%Y')), CONCAT(bailleur.prenom,' ', bailleur.nom), mensualite.type, location.prix_location, mensualite.id_user, mensualite.id_mensualite_bailleur   
		FROM `mensualite`,logement, locataire, location, bailleur, type_logement 
		WHERE location.id_locataire=locataire.id AND location.id_logement=logement.id AND type_logement.id = logement.id_type AND location.id=mensualite.id_location AND logement.id_bailleur=bailleur.id AND location.etat='active' AND mensualite.mois=? AND mensualite.annee=? AND bailleur.id=? AND mensualite.id_mensualite_bailleur=1");
		$reponse->execute(array($mois, $annee, $bailleur));
	}
}
else
{
	if ($bailleur==0) 
	{
		$reponse=$db->prepare("SELECT mensualite.id, CONCAT(locataire.prenom,' ', locataire.nom), CONCAT(type_logement.type_logement,' à ',logement.adresse), mensualite.montant, mensualite.mois, CONCAT(DATE_FORMAT(mensualite.date_versement, '%d'), '/', DATE_FORMAT(mensualite.date_versement, '%m'),'/', DATE_FORMAT(mensualite.date_versement, '%Y')), CONCAT(bailleur.prenom,' ', bailleur.nom), mensualite.type, location.prix_location, mensualite.id_user, mensualite.id_mensualite_bailleur   
		FROM `mensualite`,logement, locataire, location, bailleur, type_logement 
		WHERE location.id_locataire=locataire.id AND location.id_logement=logement.id AND type_logement.id = logement.id_type AND location.id=mensualite.id_location AND logement.id_bailleur=bailleur.id AND location.etat='active' AND mensualite.mois=? AND mensualite.annee=? AND CONCAT (locataire.prenom,' ',locataire.nom) like CONCAT('%', ?, '%') AND mensualite.id_mensualite_bailleur=1");
		$reponse->execute(array($mois, $annee, $search));
	}
	else
	{
		$reponse=$db->prepare("SELECT mensualite.id, CONCAT(locataire.prenom,' ', locataire.nom), CONCAT(type_logement.type_logement,' à ',logement.adresse), mensualite.montant, mensualite.mois, CONCAT(DATE_FORMAT(mensualite.date_versement, '%d'), '/', DATE_FORMAT(mensualite.date_versement, '%m'),'/', DATE_FORMAT(mensualite.date_versement, '%Y')), CONCAT(bailleur.prenom,' ', bailleur.nom), mensualite.type, location.prix_location, mensualite.id_user , mensualite.id_mensualite_bailleur  
	FROM `mensualite`,logement, locataire, location, bailleur, type_logement 
	WHERE location.id_locataire=locataire.id AND location.id_logement=logement.id AND location.id=mensualite.id_location AND logement.id_bailleur=bailleur.id AND type_logement.id = logement.id_type AND location.etat='active' AND mensualite.mois=? AND mensualite.annee=? AND CONCAT (locataire.prenom,' ',' ',locataire.nom) like CONCAT('%', ?, '%') AND bailleur.id=? AND mensualite.id_mensualite_bailleur=1");
	$reponse->execute(array($mois, $annee, $search, $bailleur));
	}
}

$nbr=$reponse->rowCount();
if ($nbr>0) 
{
	$i=0;
	while ($donnees= $reponse->fetch())
	{
		$i++;
		$id=$donnees['0'];
		$locataire=$donnees['1'];
		$logement=$donnees['2'];
		$montant=$donnees['3'];
		$mois=$donnees['4'];
		$date_versement=$donnees['5'];
		$bailleur=$donnees['6'];
		$type=$donnees['7'];
		$prix_location=$donnees['8'];
		$id_user=$donnees['9'];
		$id_mensualite_bailleur=$donnees['10'];
		$total=$total+$montant;
		
		echo "<tr>";
		echo "<td class='grey lighten-3'><b>".$i. "</b></td>";
		if ($_SESSION['fonction']=="administrateur") 
		{
			echo "<td><a class='tooltipped ' data-position='top' data-delay='50' data-tooltip='cliquez ici pour modifier' href='m_mensualite.php?id=$id'>".$date_versement."</a></td>";
		}
		else
		{
			echo "<td class=' '>".$date_versement."</td>";
		}
		
		echo "<td>".$locataire. "</td>";
		echo "<td>".$logement."</td>";
		echo "<td>".$bailleur."</td>";
		echo "<td>".strtoupper($type)."</td>";
		echo "<td>".number_format($prix_location,0,'.',' ')." </td>";
		echo "<td>".number_format($montant,0,'.',' ')." </td>";
		echo "<td> <a class='btn tooltipped'  data-position='top' data-delay='50' data-tooltip='Quittance N° ".$id."' href='i_mensualite.php?id=$id'><i class='material-icons left'>print</i></a>";
		echo "<td> <a class='btn-floating  tooltipped'  data-position='top' data-delay='50' data-tooltip='Restaurer' onclick='return(confirm(\"Voulez-vous restaurer cette mensualité ?\"))' href='r_mensualite.php?id=$id'><i class='material-icons right'>history</i></a></td>";
		if ($_SESSION['fonction']=="administrateur") 
		{

			echo "<td>";
			echo"
			<a class='btn red' href='s_mensualite_ancien.php?id=$id&amp;p=p' onclick='return(confirm(\"Voulez-vous supprimer cette mensualité ?\"))'><i class='material-icons'>close</i></a><br>";
			echo $id_user."</td>";
		}
		echo "</tr>";
		$nbr=$nbr+1;
	}
	
	echo "<tr>";
		echo "<td colspan='4'><h5>TOTAL</h5></td>";
		echo "<td colspan='2'><h5>".number_format($total,0,'.',' ')."</h5></td>";
	echo "</tr>";
	
}
else
{
	echo "<tr><td></td><td></td><td></td><td><h3>Aucun payement ce mois ci </td></tr>";
}			
?>
<script type="text/javascript">
	$('.tooltipped').tooltip();
</script>