<?php
session_start();
include 'connexion.php';
if (!isset($_SESSION['fonction'])) {
?>
<script type="text/javascript">
alert("Veillez d'abord vous connectez !");
window.location = 'index.php';
</script>
<?php
}
$annee= date('Y');
$mois = array("","Janvier","Février","Mars","Avril","Mai","Juin","Juillet","Août","Septembre","Octobre","Novembre","Décembre");
$datefr = $mois[date("n")-1];
//recupération des informations sur le locataire
$db->query("SET lc_time_names = 'fr_FR';");
$req=$db->prepare("SELECT CONCAT(bailleur.prenom,' ',bailleur.nom),cotisation_locataire_depense.date_depense, cotisation_locataire_depense.motif, cotisation_locataire_depense.montant_a_regler, cotisation_locataire_depense.montant_regler, cotisation_locataire_depense.reliquat
FROM `cotisation_locataire_depense` 
INNER JOIN bailleur ON cotisation_locataire_depense.id_bailleur=bailleur.id
WHERE cotisation_locataire_depense.id=?");
$req->execute(array($_GET['id']));
$nbr=$req->rowCount();
if ($nbr>0) {
	$donnees=$req->fetch();
	$bailleur=$donnees['0'];
	$date_depense=$donnees['1'];
	$motif=$donnees['2'];
	$montant_a_regler=$donnees['3'];
	$montant_regler=$donnees['4'];
	$reliquat=$donnees['5'];
}

?>
<!DOCTYPE html>
<html>
	<head>
		<title>Modification dépense</title>
		<?php include 'entete.php'; ?>
	</head>
	
	<body style="background-image: url(<?= $image ?>bgaccueil.jpg);">
		<?php
		//include 'verification_menu_immo.php';
		?><br>
		<a onclick="window.history.go(-1)" class="btn " >Retour</a>
		<div class="container white">
			<div class="row z-depth-5" style="padding: 10px;">
				<h3 class="center">Modification dépense sur cotisation locataire </h3>
				<h5 class="col s12">
                    Bailleur : <b><?=$bailleur ?></b>
                    <br>Montant cotisation : <b><?=number_format($montant_a_regler,0,'.'," ") ?> Fcfa</b>
                </h5>

				<form class="col s12 m12" method="POST" id="form" action="m_cotisation_locataire_depense_trmnt.php?id=<?=$_GET['id']?>" >
					<br>
					<input type="number" name="montant_a_payer" value="<?=$montant_a_regler ?>" hidden>
					<div class="row">
						<div class="col s12 m5 input-field">
							<textarea class="materialize-textarea" name="motif" id="motif"><?=nl2br($motif) ?></textarea>
							<label for="motif">Motif de la dépense</label>
						</div>
					</div>
					<div class="row">
						<div class="col s12 m5 input-field">
							<input type="text" value="<?= $date_depense ?>"  name="date_depense" class="datepicker" id="date_depense" required>
							<label for="date_depense">Date dépense</label>
						</div>
						<div class="col s9 m3 input-field">
							<input type="number" value="<?=$montant_regler ?>" name="montant" id="montant" required>
							<label for="montant">Montant dépense</label>
						</div>
					</div>
					<div class="row">
						<div class="col s8 m2 offset-m8 input-field">
							<input class="btn" type="submit" name="enregistrer" value="Enregistrer" >
						</div>
					</div>
				</form>
			</div>
		</div>
	</body>
	<style type="text/css">
		tr td {
			border: 1px solid;
		}
		th{
			border: 1px solid;
		}
	</style>
	<script type="text/javascript">
		$(document).ready(function () {
			$('select').formSelect();
			$('#form').submit(function () {
				if (!confirm('Voulez-vous confirmer l\'enregistrement de cette nouvelle dépense ?')) {
					return false;
				}
			});
		});
		$('.datepicker').datepicker({
		autoClose: true,
		yearRange:[2014,<?=(date('Y')+1) ?>],
		showClearBtn: true,
		i18n:{
			nextMonth: 'Mois suivant',
			previousMonth: 'Mois précédent',
			labelMonthSelect: 'Selectionner le mois',
			labelYearSelect: 'Selectionner une année',
			months: [ 'Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre' ],
			monthsShort: [ 'Jan', 'Fev', 'Mar', 'Avr', 'Mai', 'Jun', 'Jul', 'Août', 'Sep', 'Oct', 'Nov', 'Dec' ],
			weekdays: [ 'Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi' ],
			weekdaysShort: [ 'Dim', 'Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam' ],
			weekdaysAbbrev: [ 'D', 'L', 'M', 'M', 'J', 'V', 'S' ],
			today: 'Aujourd\'hui',
			clear: 'Réinitialiser',
			cancel: 'Annuler',
			done: 'OK'
			
		},
		format: 'yyyy-mm-dd'
	});
	</script>
</html>