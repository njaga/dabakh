<?php
session_start();
include 'connexion.php';
$search=$_POST['search'];
if ($search=="") 
{
	$reponse=$db->query("SELECT * FROM `bailleur` WHERE etat='activer'
	ORDER BY annee_inscription DESC, num_dossier DESC");
}
else
{
	$reponse=$db->prepare("SELECT * FROM `bailleur` WHERE CONCAT (prenom,' ',nom) like CONCAT('%', ?, '%') AND etat='activer' ORDER BY annee_inscription DESC, num_dossier DESC");
	$reponse->execute(array($search));
}
$resultat=$reponse->rowCount();
if ($resultat<1)
{
	echo "<tr><td colspan='7'><h3 class='center'>Aucun résultat</h3></td></tr>";
}
$i=1;
while ($donnees= $reponse->fetch())
{
	$id=$donnees['0'];
	$num_dossier=$donnees['1'];
	$prenom=strtoupper($donnees['2']);
	$nom=strtoupper($donnees['3']);
	$telephone=$donnees['4'];
	$adresse=$donnees['5'];						
	$annee_inscription=$donnees['6'];

	$req_logement=$db->prepare("SELECT SUM(nbr), SUM(nbr_occupe) FROM `logement` WHERE id_bailleur=? AND logement.etat='actif'");
	$req_logement->execute(array($id));
	$donnees_logement=$req_logement->fetch();
	$nbr=$donnees_logement['0'];
	$nbr_occupe=$donnees_logement['1'];
	$total=$nbr+$nbr_occupe;
	$req_l_logements=$db->prepare('SELECT logement.designation, type_logement.type_logement, logement.adresse, logement.nbr, logement.nbr_occupe, (logement.nbr+logement.nbr_occupe)
		FROM bailleur, logement, type_logement
		WHERE logement.id_type=type_logement.id AND bailleur.id=logement.id_bailleur  AND bailleur.id=?');
	
	echo "<tr>";
		echo "<td class='grey lighten-3'><b>".$i. "</b></td>";
		echo "<td><a class='tooltipped' data-position='top' data-delay='50' data-tooltip='cliquez ici pour modifier' href='m_bailleur.php?id=$id'><i class='material-icons'>edit</i></a><br><br>";
		echo "<a target='_blank' class='btn tooltipped' data-position='top' data-delay='50' data-tooltip='Imprimer la convention gérence' href='i_convention_gerance.php?id=$id'><i class='material-icons left'>print</i></a> <br><br>";
		if ($_SESSION['fonction']=="administrateur" OR $_SESSION['fonction']=="daf")
		{
		echo "<a class='red btn' href='supprimer.php?id_bailleur=".$id."' onclick='return(confirm(\"Voulez-vous supprimer ce bailleur ainsi que tous les logements de ce dernier ?\"))'>X </a>";
		}
		echo "</td>";
		if (isset($num_dossier)) 
		{
			echo "<td><b>".str_pad($num_dossier, 3,"0", STR_PAD_LEFT)."/".substr($annee_inscription, -2)."</b></td>";
		}
		else
		{
			echo "<td></td>";	
		}
		echo "<td>".$prenom." ".$nom."</td>";
		echo "<td>".$telephone."</td>";
		echo "<td>".$adresse."</td>";
		echo "<td>Total : <b>".$total."</b>; <br>Occupé(s) : <b>".$nbr_occupe."</b> ; <br>Libre(s) : <b>";
			if($total>0)
				{
					echo ($total-$nbr_occupe);
				}
			else
				{
					echo "0";
				}
			echo "</b>";
			echo "<br><a class='btn brown' href='list_logement_bailleur.php?id=$id'>Détails</a>";
		echo " </td>";
		echo "<td> <a class='btn cyan' href='compte_bailleur.php?id=$id'>Compte</a><br><br>";
		if ($_SESSION['fonction']!='daf')
		{
		echo "<a class='btn cyan' href='e_mensualite_bailleur.php?id=$id'>Mensualité</a></td>";
		}
		$i++;
	echo "</tr>";

}

?>
<script type="text/javascript">
	$('.tooltipped').tooltip();
</script>