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
$req=$db->prepare("SELECT CONCAT(prenom,' ', nom) FROM `bailleur` WHERE id=?");
$req->execute(array($_GET['id']));
$nbr=$req->rowCount();
if ($nbr>0) {
	$donnees=$req->fetch();
	$bailleur=$donnees['0'];
}

?>
<!DOCTYPE html>
<html>
	<head>
		<title>Nouvelle dépense</title>
		<?php include 'entete.php'; ?>
	</head>
	
	<body style="background-image: url(<?= $image ?>bgaccueil.jpg);">
		<?php
		//include 'verification_menu_immo.php';
		?><br>
		<a onclick="window.history.go(-1)" class="btn " >Retour</a>
		<div class="container white">
			<div class="row z-depth-5" style="padding: 10px;">
				<h3 class="center">Nouvelle dépense </h3>
				<h5 class="col s12">
                    Bailleur : <b><?=$bailleur ?></b>
                    <br>Montant cotisation : <b><?=number_format($_GET['mnt'],0,'.'," ") ?> Fcfa</b>
                </h5>

				<form class="col s12 m12" method="POST" id="form" action="e_cotisation_locataire_depense_trmnt.php?id=<?=$_GET['id']?>" >
					<br>
					<input type="number" name="montant_a_payer" value="<?=$_GET['mnt'] ?>" hidden>
					<div class="row">
						<div class="col s12 m4 hide">
							<label for="type_depense">Type cotisation</label>
							<select class="browser-default " name="type_depense" id="type_depense" >
								<option value="" disabled selected="">--Type cotisation--</option>
								<option value="Frais de nettoyage">Frais de nettoyage</option>
								<option value="Frais de gardiennage">Frais de gardiennage</option>
								<option value="Frais de vidange">Frais de vidange</option>
								<option value="Autres frais">Autres frais</option>
							</select>
						</div>
						<div class="col s12 m5 input-field">
							<textarea class="materialize-textarea" name="motif" id="motif"></textarea>
							<label for="motif">Motif de la dépense</label>
						</div>
					</div>
					<div class="row">
						<div class="col s12 m5 input-field">
							<input type="text" value="<?php echo date('Y').'-'.date('m').'-'.date('d') ?>"  name="date_depense" class="datepicker" id="date_depense" required>
							<label for="date_depense">Date dépense</label>
						</div>
						<div class="col s9 m3 input-field">
							<input type="number" value="" name="montant" id="montant" required>
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
		yearRange:[2014,2022],
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