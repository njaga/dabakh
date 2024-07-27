<?php	
session_start();				
include 'connexion.php';
$mois=$_POST['mois'];
$annee=$_POST['annee'];
$search=$_POST['search'];
$bailleur=$_POST['bailleur'];
$somme=0;
$db->query("SET lc_time_names = 'fr_FR';");
if ($search=="") 
{
	if ($bailleur==0) 
	{
		$reponse=$db->prepare("SELECT DISTINCT location.id, CONCAT(locataire.prenom,' ', locataire.nom), CONCAT(type_logement.type_logement,' à ',logement.adresse), CONCAT(bailleur.prenom,' ', bailleur.nom), location.prix_location
		FROM `mensualite`,logement, locataire, location, bailleur, type_logement 
		WHERE location.id_locataire=locataire.id AND location.id_logement=logement.id AND location.id NOT IN (SELECT mensualite.id_location FROM mensualite WHERE mensualite.mois=? AND mensualite.annee=?) AND logement.id_bailleur=bailleur.id AND type_logement.id = logement.id_type AND location.etat='active' AND mensualite.mois=? AND mensualite.annee=?");
		$reponse->execute(array($mois, $annee, $mois, $annee));
	}
	else
	{
		$reponse=$db->prepare("SELECT DISTINCT location.id, CONCAT(locataire.prenom,' ', locataire.nom), CONCAT(type_logement.type_logement,' à ',logement.adresse), CONCAT(bailleur.prenom,' ', bailleur.nom), location.prix_location
		FROM `mensualite`,logement, locataire, location, bailleur, type_logement 
		WHERE location.id_locataire=locataire.id AND location.id_logement=logement.id AND location.id NOT IN (SELECT mensualite.id_location FROM mensualite WHERE mensualite.mois=? AND mensualite.annee=?) AND logement.id_bailleur=bailleur.id AND type_logement.id = logement.id_type AND location.etat='active' AND mensualite.mois=? AND mensualite.annee=? AND bailleur.id=?");
		$reponse->execute(array($mois, $annee, $mois, $annee, $bailleur));
	}
}
else
{
	if ($bailleur==0) 
	{
		$reponse=$db->prepare("SELECT DISTINCT location.id, CONCAT(locataire.prenom,' ', locataire.nom), CONCAT(type_logement.type_logement,' à ',logement.adresse), CONCAT(bailleur.prenom,' ', bailleur.nom), location.prix_location
		FROM `mensualite`,logement, locataire, location, bailleur, type_logement 
		WHERE location.id_locataire=locataire.id AND location.id_logement=logement.id AND location.id NOT IN (SELECT mensualite.id_location FROM mensualite WHERE mensualite.mois=? AND mensualite.annee=?) AND logement.id_bailleur=bailleur.id AND type_logement.id = logement.id_type AND location.etat='active' AND mensualite.mois=? AND mensualite.annee=? AND CONCAT (locataire.prenom,' ',' ',locataire.nom) like CONCAT('%', ?, '%')");
		$reponse->execute(array($mois, $annee, $mois, $annee, $search));
	}
	else
	{
		$reponse=$db->prepare("SELECT DISTINCT location.id, CONCAT(locataire.prenom,' ', locataire.nom), CONCAT(type_logement.type_logement,' à ',logement.adresse), CONCAT(bailleur.prenom,' ', bailleur.nom), location.prix_location
		FROM `mensualite`,logement, locataire, location, bailleur, type_logement 
		WHERE location.id_locataire=locataire.id AND location.id_logement=logement.id AND location.id NOT IN (SELECT mensualite.id_location FROM mensualite WHERE mensualite.mois=? AND mensualite.annee=?) AND logement.id_bailleur=bailleur.id AND type_logement.id = logement.id_type AND location.etat='active' AND mensualite.mois=? AND mensualite.annee=? AND CONCAT (locataire.prenom,' ',' ',locataire.nom) like CONCAT('%', ?, '%') AND bailleur.id=?");
		$reponse->execute(array($mois, $annee, $mois, $annee, $search, $bailleur));
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
		$bailleur=$donnees['3'];
		$montant=$donnees['4'];
		echo "<tr>";
		echo "<td class='grey lighten-3'><b>".$i. "</b></td>";
		echo "<td>".$locataire. "</td>";
		echo "<td>".$logement."</td>";
		echo "<td>".$bailleur."</td>";
		echo "<td>".number_format($montant,0,'.',' ')." Fcfa</td>";
		if ($_SESSION['fonction']!='daf')
			{
				echo "<td> <a class='btn tooltipped' data-position='top' data-delay='20' data-tooltip='cliquez ici pour payer' href='e_mensualite1.php?id=$id'><i class='material-icons left'>attach_money</i></a> </td>";
			}
  
		echo "<td> <a class='btn tooltipped' target='_blank'  data-position='top' data-delay='50' data-tooltip='Imprimer' href='i_mensualite_impaye.php?id=$id&amp;m=$mois'><i class='material-icons left'>print</i></a> ";

		echo "</tr>";
		$somme=$somme+1;
	}
	/*
	echo "<tr>";
		echo "<td colspan='3'><h5>TOTAL</h5></td>";
		echo "<td><h5>".$somme."</h5></td>";
	echo "</tr>";
	*/
}
else
{
	echo "<tr><td></td><td></td><td></td><td><h3>Aucune mensualité impayé ce mois ci </td></tr>";
}			
?>
<script type="text/javascript">
	$('.tooltipped').tooltip();
</script>
