<?php
session_start();
include 'connexion.php';
$jour=$_POST['jour'];
$patient=$_POST['patient'];
$db->query("SET lc_time_names = 'fr_FR';");
if ($patient==0) 
{
	$reponse=$db->prepare("SELECT constante.id, patient.num_dossier, CONCAT(patient.prenom, ' ', patient.nom), CONCAT(day(constante.date_prise),' ', monthname(constante.date_prise), ' ', year(constante.date_prise)), CONCAT(hour(constante.heure_prise), 'h : ', minute(constante.heure_prise)), constante.pouls, constante.tension, constante.temperature, constante.dextro, constante.conscience, constante.vomissement, constante.diarrhee, spo2
	FROM `constante`, patient 
	WHERE patient.id_patient=constante.id_patient AND constante.date_prise=?
	ORDER BY constante.date_prise DESC, constante.heure_prise DESC");
	$reponse->execute(array($jour));
}
else
{
	$reponse=$db->prepare("SELECT constante.id, patient.num_dossier, CONCAT(patient.prenom, ' ', patient.nom), CONCAT(day(constante.date_prise),' ', monthname(constante.date_prise), ' ', year(constante.date_prise)), CONCAT(hour(constante.heure_prise), 'h : ', minute(constante.heure_prise)), constante.pouls, constante.tension, constante.temperature, constante.dextro, constante.conscience, constante.vomissement, constante.diarrhee, spo2
	FROM `constante`, patient 
	WHERE patient.id_patient=constante.id_patient AND constante.date_prise=? AND patient.id_patient=?
	ORDER BY constante.date_prise DESC, constante.heure_prise DESC");
	$reponse->execute(array($jour, $patient));	
}
$nbr=$reponse->rowCount();
if ($nbr>0)
{
	while ($donnees= $reponse->fetch())
	{
		$id_constante=$donnees['0'];
		$num_dossier=$donnees['1'];
		$patient=$donnees['2'];
		$date_prise=$donnees['3'];
		$heure_prise=$donnees['4'];
		$pouls=$donnees['5'];
		$tension=$donnees['6'];
		$temperature=$donnees['7'];
		$dextro=$donnees['8'];
		$conscience=$donnees['9'];
		$vomissement=$donnees['10'];
		$diarrhee=$donnees['11'];
		$spo2=$donnees['12'];

		echo "<tr>";
			echo "<td> <a href='m_constante.php?id=".$id_constante."'>".$date_prise."</a> </td>";
			echo "<td>".$heure_prise."</td>";
			echo "<td>".$num_dossier."</td>";
			echo "<td>".$patient."</td>";
			echo "<td>".$pouls."</td>";
			echo "<td>".$tension."</td>";
			echo "<td>".$temperature."</td>";
			echo "<td>".$dextro."</td>";
			echo "<td>".$spo2."</td>";
			echo "<td><b>".nl2br($conscience)."</b></td>";
			echo "<td><b>".nl2br($vomissement)."</b></td>";
			echo "<td><b>".nl2br($diarrhee)."</b></td>";
		
		echo "</tr>";
	}
	
	}
	else
	{
		echo "<tr><td></td><td></td><td><h3>Aucune constante enregistrée à cette date </td></tr>";
			}
	?>