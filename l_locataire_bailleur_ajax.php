<?php
			session_start();
include 'connexion.php';
$mois=$_POST['mois'];
$annee=$_POST['annee'];
$search=$_POST['search'];
$somme=0;
$db->query("SET lc_time_names = 'fr_FR';");
//recupération des locations du bailleur
$reponse=$db->prepare("SELECT CONCAT(locataire.prenom,' ', locataire.nom), CONCAT(type_logement.type_logement,' ',logement.designation,' à ',logement.adresse), location.id, location.prix_location
FROM logement, locataire, location, bailleur, type_logement
WHERE type_logement.id=logement.id_type AND location.id_logement=logement.id AND location.id_locataire=locataire.id AND logement.id_bailleur= bailleur.id AND bailleur.id=? ORDER BY location.id");
$reponse->execute(array($search));
$nbr=$reponse->rowCount();
if ($nbr>0)
{
while ($donnees= $reponse->fetch())
{
	$locataire=$donnees['0'];
	$logement=$donnees['1'];
	$id_location=$donnees['2'];
	$prix_location=$donnees['3'];
	//recupération des mensualités
	$req_mensualite=$db->prepare("SELECT mensualite.montant, mensualite.date_versement, mensualite.type
	FROM `mensualite`,logement, locataire, location, bailleur
	WHERE location.id=mensualite.id_location AND location.id_logement=logement.id AND location.id_locataire=locataire.id AND logement.id_bailleur= bailleur.id AND location.id=? AND mensualite.mois=? AND mensualite.annee=?");
	$req_mensualite->execute(array($id_location, $mois, $annee));
	$donnees_mensualite= $req_mensualite->fetch();
	$montant=$donnees_mensualite['0'];
	$date_versement=$donnees_mensualite['1'];
	$type=$donnees_mensualite['2'];

	echo "<tr>";
		//echo "<td><a class='tooltipped' data-position='top' data-delay='50' data-tooltip='cliquez ici pour modifier' href='m_mensualite.php?id=$id'>".$date_versement."</a></td>";
		echo "<td>".$locataire. "</td>";
		echo "<td>".$logement."</td>";
		echo "<td>".number_format($prix_location,0,'.',' ')." Fcfa</td>";
		if (!isset($montant)) 
		{
			echo "<td></td>";
			echo "<td></td>";
			echo "<td></td>";
		}
		else
		{
			
			echo "<td>".$date_versement. "</td>";
			echo "<td>".$type. "</td>";
			echo "<td>".$montant." Fcfa</td>";
		}
		/*
		echo "<td> <a class='btn' href='i_mensualite.php?id=$id'><i class='material-icons left'>print</i> Facture</a>
		<br><br> <a class='btn red' href='s_mensualite.php?id=$id' onclick='return(confirm(\"Voulez-vous supprimer cette mensualité ?\"))'><i class='material-icons left'>close</i>Supprimer</a>
	</td>";
	*/
	echo "</tr>";
	$somme=$somme+1;
}
echo "<tr>";
	echo "<td colspan='3'><h5>TOTAL</h5></td>";
	echo "<td><h5>".$somme."</h5></td>";
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