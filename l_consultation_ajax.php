<?php	
session_start();				
include 'connexion.php';
$etat='medecin';
$mois=$_POST['mois'];
$annee=$_POST['annee'];
$search=$_POST['search'];
$db->query("SET lc_time_names = 'fr_FR';");

if($search=="")
{
	$reponse=$db->prepare("SELECT id_consultation, CONCAT(day(date_consultation),' ', monthname(date_consultation),' ', year(date_consultation)), CONCAT(patient.prenom,' ',patient.nom), CONCAT(CONCAT(day(patient.date_naissance),' ', monthname(patient.date_naissance),' ', year(patient.date_naissance)), ' ',patient.lieu_naissance), consultation.montant, consultation.reglement, patient.num_dossier, patient.annee_inscription, consultation.id_user_s, consultation.id_user_i, consultation.id_user_m
	FROM `consultation`, patient 
	WHERE consultation.id_patient=patient.id_patient AND YEAR(date_consultation)=? AND month(date_consultation)=? AND etat=?
	ORDER BY date_consultation DESC");
	$reponse->execute(array($annee, $mois, $etat));
}
else
{
	$reponse=$db->prepare("SELECT id_consultation, CONCAT(day(date_consultation),' ', monthname(date_consultation),' ', year(date_consultation)), CONCAT(patient.prenom,' ',patient.nom), CONCAT(CONCAT(day(patient.date_naissance),' ', monthname(patient.date_naissance),' ', year(patient.date_naissance)), ' ',patient.lieu_naissance), consultation.montant, consultation.reglement, patient.num_dossier, patient.annee_inscription, consultation.id_user_s, consultation.id_user_i, consultation.id_user_m
	FROM `consultation`, patient 
	WHERE consultation.id_patient=patient.id_patient AND YEAR(date_consultation)=? AND month(date_consultation)=? AND etat=? AND CONCAT (prenom,' ',' ',nom) like CONCAT('%', ?, '%')
	ORDER BY date_consultation DESC");
	$reponse->execute(array($annee, $mois, $etat, $search));
}
$nbr=$reponse->rowCount();
if ($nbr>0) 
{
	while ($donnees= $reponse->fetch())
	{
		$id_consultation=$donnees['0'];
		$date_consultation=$donnees['1'];
		$patient=$donnees['2'];
		$date_naissance=$donnees['3'];
		$montant=$donnees['4'];
		$reglement=$donnees['5'];
		$num_dossier=$donnees['6'];
		$annee_inscription=$donnees['7'];
		$id_user_s=$donnees['8'];
		$id_user_i=$donnees['9'];
		$id_user_m=$donnees['10'];
		echo "<tr>";
		echo "<td>";
		/*
		if ($_SESSION['fonction']=='medececin' OR $_SESSION['fonction']=='administrateur' OR $_SESSION['fonction']=='infirmier') 
		{
		*/
			echo "<a class='tooltipped' data-position='top' data-delay='50' data-tooltip='cliquez ici pour modifier' href='m_consultation.php?id=$id_consultation'><i class='material-icons left'>edit</i>".$date_consultation."</a><br><br>";
			/*
		}
		else
		{
			echo $date_consultation."<br><br>";
		}
		*/
		if ($_SESSION['fonction']=='administrateur') 
		{
			echo "<a class='red btn' onclick='return(confirm(\"Voulez-vous supprimer cette consultation ?\"))' href='s_consultation.php?id=$id_consultation'>Supprimer</a>";
		}

		echo "</td>";
		echo "<td>".str_pad($num_dossier, 3,"0",STR_PAD_LEFT)."/".substr($annee_inscription, -2)." ".$patient."</td>";
		echo "<td>".$date_naissance."</td>";
		if ($_SESSION['fonction']!="infirmier" AND $_SESSION['fonction']!='medecin'	)
		{
			echo "<td><b>".number_format($montant, 0, ",", " ")." Fcfa</b></td>";
			if ($reglement=='non') 
			{
				echo "<td>Non régler</td>";
				if ($_SESSION['fonction']=='secretaire' OR $_SESSION['fonction']=='caissier')
				{	
					echo "<td> <a href='regler_consultation.php?id=$id_consultation'> Régler</a> </td>";
				}
			}
			else
			{
				echo "<td>Régler</td>";
			}
		}
		
		echo "<td> <a target='_blank' href='infos_consultation.php?id=$id_consultation'> Détails</a> </td>";
		if ($_SESSION['fonction']=='secretaire' OR $_SESSION['fonction']=='administrateur')
		{
			
		echo "<td> <a target='_blank' class='btn' href='i_facture_cons.php?id=$id_consultation'><i class='material-icons left'>print</i> Facture <b>N°".str_pad($id_consultation, 3, "0", STR_PAD_LEFT)."</b></a> </td>";
		}
		if ($_SESSION['fonction']=='administrateur')
		{
			
		echo "<td>".$id_user_s."<br>".$id_user_i."<br>".$id_user_m."</td>";
		}
		echo "</tr>";
	}
	
}
else
{
	echo "<tr><td></td><td></td><td><h3>Aucune consultation à cette date </td></tr>";
}			
?>
<script type="text/javascript">
	$('.tooltipped').tooltip();
</script>