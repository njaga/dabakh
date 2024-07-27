<?php	
session_start();				
include 'connexion.php';
$search=$_POST['search'];
$db->query("SET lc_time_names = 'fr_FR';");
if ($search=="") {
	$reponse=$db->query("SELECT * FROM patient");
}
else
{
	$reponse=$db->prepare("SELECT * FROM patient WHERE prenom like CONCAT('%', ?, '%') OR nom like CONCAT('%', ?, '%')");
	$reponse->execute(array($search,$search));
}

$resultat=$reponse->rowCount();
while ($donnees= $reponse->fetch())
{
$id_patient=$donnees['0'];
$prenom=$donnees['1'];
$nom=$donnees['2'];
$date_naissance=$donnees['3'];
$lieu_naissance=$donnees['4'];
$profession=$donnees['5'];
$domicile=$donnees['6'];
$telephone=$donnees['7'];
$num_dossier=$donnees['12'];
$annee_inscription=$donnees['13'];
$p_n=$prenom." ".$nom;
echo "<tr>";
	echo "<td></td>";
	echo "<td>".str_pad($num_dossier, 3,"0",STR_PAD_LEFT)."/".substr($annee_inscription, -2)."</td>";
	echo "<td>".$prenom." ". $nom."</td>";
	echo "<td>".$date_naissance." à ".$lieu_naissance."</td>";
	echo "<td>".$profession."</td>";
	echo "<td>".$domicile."</td>";
	echo "<td>".$telephone."</td>";
	echo "<td><a class='btn ' href='e_prescription.php?id=".$id_patient."&amp;p_n=".$p_n."'>Sélectionner</a></td>";
echo "</tr>";}
if ($resultat<1)
{
	echo "<tr><td colspan='4'><h3 class='center'>Aucun patient</h3></td></tr>";
}

?>