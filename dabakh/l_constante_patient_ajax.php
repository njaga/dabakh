<option value="0" selected="">--Tous les patients--</option>
<?php
include 'connexion.php';
$jour=$_POST['jour'];
$reponse=$db->prepare("SELECT DISTINCT patient.id_patient, patient.prenom, patient.nom 
FROM `constante`, patient 
WHERE patient.id_patient=constante.id_patient AND constante.date_prise=?
ORDER BY patient.nom ");
$reponse->execute(array($jour));
while ($donnees=$reponse->fetch())
{
	echo '<option value="'. $donnees['0'] .'">'. ucwords(strtolower($donnees['1'])) .' '.strtoupper($donnees['2']).'</option>';
}
?>
