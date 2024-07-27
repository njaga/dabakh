<?php
session_start();
include 'connexion.php';
$jour=$_POST['jour'];
$patient=$_POST['patient'];
$db->query("SET lc_time_names = 'fr_FR';");
if ($patient==0) 
{
	$reponse=$db->prepare("SELECT prescription.id, CONCAT(patient.prenom,' ',patient.nom), CONCAT(CONCAT(day(patient.date_naissance),' ', monthname(patient.date_naissance),' ', year(patient.date_naissance)), ' ',patient.lieu_naissance), prescription.prescription, prescription.etat, CONCAT(hour(prescription.heure_prescription),'h : ',minute( prescription.heure_prescription))
	FROM prescription, patient
	WHERE prescription.id_patient=patient.id_patient AND date_prescription=?
	ORDER BY heure_prescription DESC");
	$reponse->execute(array($jour));
}
else
{
	$reponse=$db->prepare("SELECT prescription.id, CONCAT(patient.prenom,' ',patient.nom), CONCAT(CONCAT(day(patient.date_naissance),' ', monthname(patient.date_naissance),' ', year(patient.date_naissance)), ' ',patient.lieu_naissance), prescription.prescription, prescription.etat, CONCAT(hour(prescription.heure_prescription),'h : ',minute( prescription.heure_prescription))
	FROM prescription, patient
	WHERE prescription.id_patient=patient.id_patient AND date_prescription=? AND prescription.id_patient=?
	ORDER BY heure_prescription DESC");
	$reponse->execute(array($jour, $patient));
}
$nbr=$reponse->rowCount();
if ($nbr>0)
{
while ($donnees= $reponse->fetch())
{
	$id_prescription=$donnees['0'];
	$patient=$donnees['1'];
	$date_naissance=$donnees['2'];
	$prescription=$donnees['3'];
	$etat=$donnees['4'];
	$heure_prescription=$donnees['5'];
	if ($etat=='non')
	{
		echo "<tr class=' red lighten-4'>";
		}
		else
		{
			echo "<tr>";
			}
			
			echo "<td>".$patient."</td>";
			echo "<td>".$date_naissance."</td>";
			echo "<td><b>".nl2br($prescription)."</b></td>";
			echo "<td>".$heure_prescription."</td>";
			
			if ($etat=='non')
			{
				echo "<td>Pas encore appliquer</td>";
				if ($_SESSION['fonction']=="infirmier")
				{
				echo "<td> <a href='appliquer_prescription.php?id=$id_prescription'> Appliquer</a> </td>";
				}
			}
			else
			{
				echo "<td>Déjà appliquer</td>";
			}
			if ($_SESSION['fonction']=='medecin' OR $_SESSION['fonction']=='administrateur')
			{
				
			echo "<td> <a class='btn red' onclick='return(confirm(\"Voulez-vous effectuer cette suppression ?\"))' href='supp_prescription.php?id=$id_prescription'><i class='material-icons left'>close</i></a> </td>";
			}
			
		echo "</tr>";
	}
	
	}
	else
	{
	echo "<tr><td></td><td></td><td><h3>Aucune prescription à cette date </td></tr>";
			}
	?>