<?php
session_start();
include 'connexion.php';
$mois = $_POST['mois'];
$annee = $_POST['annee'];
$db->query("SET lc_time_names = 'fr_FR';");



$req_patient = $db->prepare("SELECT id_consultation, CONCAT(day(date_consultation),' ', monthname(date_consultation),' ', year(date_consultation)), CONCAT(patient.prenom,' ',patient.nom), CONCAT(CONCAT(day(patient.date_naissance),' ', monthname(patient.date_naissance),' ', year(patient.date_naissance)), ' ',patient.lieu_naissance), consultation_domicile.montant, consultation_domicile.reglement, patient.num_dossier, patient.annee_inscription, consultation_domicile.service, consultation_domicile.infirmer, consultation_domicile.remarque_suggestion
FROM consultation_domicile, patient 
WHERE consultation_domicile.id_patient=patient.id_patient AND YEAR(date_consultation)=? AND month(date_consultation)=?
ORDER BY date_consultation DESC");
$req_patient->execute(array($annee, $mois));
$nbr_patient = $req_patient->rowCount();
if ($nbr_patient > 0) {
	$i = 0;
	while ($donnees_patient = $req_patient->fetch()) {
		$id_consultation = $donnees_patient['0'];
		$date_consultation = $donnees_patient['1'];
		$patient = $donnees_patient['2'];
		$date_naissance = $donnees_patient['3'];
		$montant = $donnees_patient['4'];
		$reglement = $donnees_patient['5'];
		$num_dossier = $donnees_patient['6'];
		$annee_inscription = $donnees_patient['7'];
		$service = $donnees_patient['8'];
		$infirmier = $donnees_patient['9'];
		$remarque_suggestion = $donnees_patient['10'];
		$i++;
		echo "<tr>";
		echo "<td class='grey lighten-3'><b>" . $i . "</b></td>";
		echo "<td>";
		if ($_SESSION['fonction'] == 'medececin' or $_SESSION['fonction'] == 'administrateur' or $_SESSION['fonction'] == 'infirmier') {
			echo " 
			<a class='tooltipped' data-position='top' data-delay='50' data-tooltip='cliquez ici pour modifier' href='m_consultation_d.php?id=$id_consultation'><i class='material-icons left'>edit</i>" . $date_consultation . "</a><br><br>";
		} else {
			echo $date_consultation . "<br><br>";
		}
		if ($_SESSION['fonction'] == 'medececin' or $_SESSION['fonction'] == 'administrateur') {
			echo "<a class='red btn' onclick='return(confirm(\"Voulez-vous supprimer ces soins ?\"))' href='s_consultation_d.php?id=$id_consultation'>Supprimer</a>";
		}

		echo "</td>";
		echo "<td>" . str_pad($num_dossier, 3, "0", STR_PAD_LEFT) . "/" . substr($annee_inscription, -2) . " " . $patient . "</td>";
		echo "<td>" . $date_naissance . "</td>";
		if ($_SESSION['fonction'] == 'daf' or $_SESSION['fonction'] == 'administrateur' or $_SESSION['fonction'] == 'secretaire') {
			echo "<td><b>" . number_format($montant, 0, ",", " ") . " Fcfa</b></td>";
			if ($reglement == 'non') {
				echo "<td>Non régler</td>";
				if ($_SESSION['fonction'] == 'secretaire') {
					echo "<td> <a href='regler_consultation_d.php?id=$id_consultation'> Régler</a>";
					echo "<br><br><a href='d_regularisation_cons_dom.php?id=$id_consultation'> Demande de régularisation</a></td>";
				}
			} else {
				echo "<td>Régler</td>";
			}
		}
		if ($_SESSION['fonction'] == 'medecin' or $_SESSION['fonction'] == 'administrateur' or $_SESSION['fonction'] == 'daf') {

			echo "<td>Service : " . $service . "\nInfirmier : " . $infirmier . "</td>";
			echo "<td>" . $remarque_suggestion . "</td>";
		}
		if ($_SESSION['fonction'] == 'medecin' or $_SESSION['fonction'] == 'administrateur') {
			echo "<td> <a target='_blank' href='infos_consultation_d.php?id=$id_consultation'> Détails</a> <br><br>";
		}
		if ($_SESSION['fonction'] == 'secretaire' or $_SESSION['fonction'] == 'administrateur') {

			echo "<a target='_blank' class='btn' href='i_facture_cons_d.php?id=$id_consultation'>Facture <b>N°" . str_pad($id_consultation, 2, "0", STR_PAD_LEFT) . "</b></a><br><br>";
		}
		if ($reglement == 'non') {
			echo "<a href='d_regularisation_cons_dom.php?id=$id_consultation'> Demande régularisation</a><br><br>";
		}
		if ($_SESSION['fonction'] == "infirmier") {
			echo "<td><a href='e_rapport_assis.php?id=$id_consultation'>Rapport +</a> </td>";
		}
		echo "</td>";
		echo "</tr>";
	}
} else {
	echo "<tr><td></td><td></td><td><h3>Aucune consultation à cette date </td></tr>";
}



?>
<script type="text/javascript">
	$('.tooltipped').tooltip();
</script>