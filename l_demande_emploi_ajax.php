<?php	
session_start();				
include 'connexion.php';
$search=$_POST['search'];
$db->query("SET lc_time_names = 'fr_FR';");
if ($search=="") {
	$reponse=$db->query("SELECT id,  CONCAT(DATE_FORMAT(date_enregistrement, '%d'), '/', DATE_FORMAT(date_enregistrement, '%m'),'/', DATE_FORMAT(date_enregistrement, '%Y')), prenom, nom, poste, id_user
FROM `demande_emploi`");
}
else
{
	$reponse=$db->prepare("SELECT id,  CONCAT(DATE_FORMAT(date_enregistrement, '%d'), '/', DATE_FORMAT(date_enregistrement, '%m'),'/', DATE_FORMAT(date_enregistrement, '%Y')), prenom, nom, poste, id_user
FROM `demande_emploi` WHERE  CONCAT(prenom,' ', nom) like CONCAT('%', ?, '%') ");
	$reponse->execute(array($search));
}

$resultat=$reponse->rowCount();
while ($donnees= $reponse->fetch())
{
$id=$donnees['0'];
$date_enregistrement=$donnees['1'];
$prenom=ucwords(strtolower($donnees['2']));
$nom=ucwords(($donnees['3']));
$poste=ucfirst($donnees['4']);
$id_user=$donnees['5'];

echo "<tr>";
	echo "<td> <a class='tooltipped' data-position='top' data-delay='50' data-tooltip='cliquez ici pour modifier' href='m_demande_emploi.php?s=$id'> ".$date_enregistrement. "</a> </td>";
	echo "<td>".$prenom." ". $nom."</td>";
	echo "<td>".$poste."</td>";
	echo "<td>";
	$req_pj=$db->prepare('SELECT * FROM pj_demande_emploi WHERE id_demande=? ORDER BY type_demande');
		$req_pj->execute(array($id));
		$i=0;
		while ($donnees_pj=$req_pj->fetch()) 
		{
				echo "<a class='tooltipped' target='_blank' data-position='top' data-tooltip='".$donnees_pj['1']."' href='".$donnees_pj['3']."'>".$donnees_pj['2']." </a>";
				$i++;
				echo "<br>";
		}
	echo "</td>";
	
	
echo "</tr>";}
if ($resultat<1)
{
	echo "<tr><td colspan='4'><h3 class='center'>Aucune demande enregistrée</h3></td></tr>";
}

?>
<script type="text/javascript">
	$('.tooltipped').tooltip();
</script>