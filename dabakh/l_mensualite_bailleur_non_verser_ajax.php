<?php	
session_start();				
include 'connexion.php';
$mois=$_POST['mois'];
$annee=$_POST['annee'];
$search=$_POST['search'];
$somme=0;
$db->query("SET lc_time_names = 'fr_FR';");
if ($search=="") 
{
	$reponse=$db->prepare("SELECT COUNT(locataire.id), CONCAT(bailleur.prenom,' ', bailleur.nom), SUM(mensualite.montant), bailleur.id 
		FROM `mensualite`,logement, locataire, location, bailleur, type_logement 
		WHERE location.id_locataire=locataire.id AND location.id_logement=logement.id AND type_logement.id = logement.id_type AND location.id=mensualite.id_location AND logement.id_bailleur=bailleur.id AND location.etat='active' AND mensualite.mois=? AND mensualite.annee=? AND mensualite.id_mensualite_bailleur<=1 
        GROUP BY bailleur.id
        ORDER BY bailleur.nom, bailleur.prenom");
	$reponse->execute(array($mois, $annee));
	//les bailleurs n'ayant pas encore reçu de mensualité des locataires
	$req_bailleur_non_paye=$db->prepare("SELECT bailleur.id, CONCAT(bailleur.prenom,' ', bailleur.nom), adresse
	FROM bailleur
	WHERE bailleur.id NOT IN
	(SELECT  bailleur.id
		FROM mensualite_bailleur, bailleur
			WHERE mensualite_bailleur.id_bailleur=bailleur.id AND mensualite_bailleur.mois=? AND mensualite_bailleur.annee=?)");
	$req_bailleur_non_paye->execute(array($mois, $annee));
}
else
{
	$reponse=$db->prepare("SELECT COUNT(locataire.id), CONCAT(bailleur.prenom,' ', bailleur.nom), SUM(mensualite.montant), bailleur.id 
		FROM `mensualite`,logement, locataire, location, bailleur, type_logement 
		WHERE location.id_locataire=locataire.id AND location.id_logement=logement.id AND type_logement.id = logement.id_type AND location.id=mensualite.id_location AND logement.id_bailleur=bailleur.id AND location.etat='active' AND mensualite.mois=? AND mensualite.annee=? AND mensualite.id_mensualite_bailleur<=1 AND CONCAT (bailleur.prenom,' ',bailleur.nom) like CONCAT('%', ?, '%')
        GROUP BY bailleur.id
        ORDER BY bailleur.nom, bailleur.prenom");
	$reponse->execute(array($mois, $annee, $search));
	//les bailleurs n'ayant pas encore reçu de mensualité des locataires
	$req_bailleur_non_paye=$db->prepare("SELECT bailleur.id, CONCAT(bailleur.prenom,' ', bailleur.nom), adresse
	FROM bailleur
	WHERE bailleur.id NOT IN
	(SELECT  bailleur.id
		FROM mensualite_bailleur, bailleur
			WHERE mensualite_bailleur.id_bailleur=bailleur.id AND mensualite_bailleur.mois=? AND mensualite_bailleur.annee=? AND CONCAT (bailleur.prenom,' ',bailleur.nom) like CONCAT('%', ?, '%'))");
	$req_bailleur_non_paye->execute(array($mois, $annee, $search));
}

$nbr=$reponse->rowCount();
?>
<div class="col s12   ">
	<table class="bordered highlight centered striped table white">
		<thead>
			<tr style="color: #fff; background-color: #bd8a3e">
				<th></th>
				<th>Bailleur</th>
				<th>Nbr locations versés</th>
				<th>Montant reçu</th>
				<th>Montant dépenses</th>
			</tr>
		</thead>
		<tbody class="">
<?php
if ($nbr>0) 
{
	$i=1;
	while ($donnees= $reponse->fetch())
	{
		$id=$donnees['3'];
		$montant=$donnees['2'];
		$bailleur=$donnees['1'];
		$nbr=$donnees['0'];
		//somme dépense(s)
		$req_depense=$db->prepare("SELECT SUM(depense_bailleur.montant)
		FROM `depense_bailleur`, bailleur 
		WHERE bailleur.id=depense_bailleur.id_bailleur AND depense_bailleur.annee=2020 AND depense_bailleur.mois=? AND depense_bailleur.annee=? AND bailleur.id=? AND depense_bailleur.id_mensualite_bailleur=0");
		$req_depense->execute(array($mois, $annee, $id));	
		$donnees_depense=$req_depense->fetch();
		$depense=$donnees_depense['0'];
		//soomme locations	
		$req_location=$db->prepare("SELECT COUNT(location.id)
		FROM location, logement, bailleur
		WHERE location.id_logement=logement.id AND logement.id_bailleur=bailleur.id AND location.etat='active' AND bailleur.id=?");
		$req_location->execute(array($id));	
		$donnees_location=$req_location->fetch();
		$location=$donnees_location['0'];

		echo "<tr>";

		echo "<td class='grey lighten-3'><b>".$i. "</b></td>";
		echo "<td>".$bailleur. "</td>";
		echo "<td>".str_pad($nbr, 2,"0",STR_PAD_LEFT). "/<b>".str_pad($location, 2,"0",STR_PAD_LEFT)."</b></td>";
		echo "<td>".number_format($montant,0,'.',' ')." Fcfa</td>";
		echo "<td>".number_format($depense,0,'.',' ')." Fcfa</td>";
		if ($_SESSION['fonction']!='daf')
		{
			echo "<td> <a class='btn tooltipped' data-position='top' data-delay='20' data-tooltip='cliquez ici pour payer' href='e_mensualite_bailleur.php?id=$id'><i class='material-icons left'>attach_money</i></a>";
		}
  
		echo "</td>";
		echo "</tr>";
		$i++;
	}
	
}
else
{
	echo "<tr><td></td><td></td><td></td><td><h3>Aucun versment ce mois ci </td></tr>";
}			
?>

		</tbody>
	</table>
</div>
<!--liste des bailleurs n'ayant pas encore reçu de mensualité locataire -->
<?php
$nbr=$req_bailleur_non_paye->rowCount();
?>
<h4 class="center #0d47a1 col s12" style="color: white">Bailleur dont les locatires n'ont pas encore vérsé</h4>
<div class="col s12   ">
	<table class="bordered highlight centered striped table white">
		<thead>
			<tr style="color: #fff; background-color: #bd8a3e">
				<th></th>
				<th>Bailleur</th>
				<th>Adresse</th>
			</tr>
		</thead>
		<tbody class="">
<?php
if ($nbr>0) 
{
	$i=1;
	while ($donnees= $req_bailleur_non_paye->fetch())
	{
		$id=$donnees['0'];
		$bailleur=$donnees['1'];
		$adresse=$donnees['2'];

		echo "<tr>";

		echo "<td class='grey lighten-3'><b>".$i. "</b></td>";
		echo "<td>".$bailleur. "</td>";
		echo "<td>".$adresse. "</td>";
  
		echo "</tr>";
		$i++;
	}
	
}
else
{
	echo "<tr><td></td><td></td><td></td><td><h3>Aucun bailleur impayé ce mois ci </td></tr>";
}			
?>

		</tbody>
	</table>
</div>
<script type="text/javascript">
	$('.tooltipped').tooltip();
</script>