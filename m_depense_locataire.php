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
$req=$db->prepare("SELECT CONCAT(prenom,' ', nom), cotisation_locataire.id, cotisation_locataire.motif, cotisation_locataire.type_depense, cotisation_locataire.date_depense, cotisation_locataire.mois, cotisation_locataire.annee, cotisation_locataire.montant 
FROM `locataire`, cotisation_locataire 
WHERE cotisation_locataire.id_locataire=locataire.id AND cotisation_locataire.id=?");
$req->execute(array($_GET['id']));
$nbr=$req->rowCount();
if ($nbr>0) {
	$donnees=$req->fetch();
	$locataire=$donnees['0'];
	$id=$donnees['1'];
	$motif=$donnees['2'];
	$type_depense=$donnees['3'];
	$date_depense=$donnees['4'];
	$mois_encours=$donnees['5'];
	$annee_encours=$donnees['6'];
	$montant=$donnees['7'];
}

?>
<!DOCTYPE html>
<html>
	<head>
		<title>Modification cotisation</title>
		<?php include 'entete.php'; ?>
	</head>
	
	<body style="background-image: url(<?= $image ?>bgaccueil.jpg);">
		<?php
		include 'verification_menu_immo.php';
		?><br>
		<a onclick="window.history.go(-1)" class="btn " >Retour</a>
		<div class="container white">
			<div class="row z-depth-5" style="padding: 10px;">
				<h3 class="center">Modificaition cotisation</h3>
				<h5 class="col s12">Locataire : <b><?=$locataire ?></b></h5>

				<form class="col s12" method="POST" id="form" action="m_depense_locataire_trmnt.php?id=<?=$_GET['id']?>" >
					<br>
					<div class="row">

						<div class="col s4">
							<label for="mois">Mois de :</label>
							<select class="browser-default" name="mois" id="mois" required="">
								<option disabled="" selected="" value="">Sélectionner le mois</option>
								<?php
								for ($i=1; $i <= 12; $i++) 
								{
									echo "<option value='".$mois[$i]."'";
									if ($mois_encours==$mois[$i]) {
										echo "selected";
									}
									echo ">".$mois[$i]."</option>";
								}
								?>
							</select>
						</div>
						<div class="col s3">
							<label for="annee">Année</label>
							<select class="browser-default " name="annee" id="annee" required="">
								<option value="" disabled>--Choisir Annee--</option>
								<?php
								echo '<option value="'. $annee .'" ';
								if ($annee_encours==$annee) {
									echo "selected";
								}
								echo '>'. $annee .'</option>';
								echo '<option value="'. $annee + 1 .'" ';
								if ($annee_encours==$annee + 1) {
									echo "selected";
								}
								echo '>'. $annee + 1 .'</option>';
								?>
							</select>
						</div>
					</div>
					<div class="row">
						<div class="col s4">
							<label for="type_depense">Type cotisation</label>
							<select class="browser-default " name="type_depense" id="type_depense" required="">
								<option value="" disabled >--Type cotisation--</option>
								<option value="Frais de nettoyage" <?php if ($type_depense=="Frais de nettoyage") {echo "selected";}  ?>>Frais de nettoyage</option>
								<option value="Frais de gardiennage" <?php if ($type_depense=="Frais de gardiennage") {echo "selected";}  ?>>Frais de gardiennage</option>
								<option value="Autres frais" <?php if ($type_depense=="Autres frais") {echo "selected";}  ?>>Autres frais</option>
							</select>
						</div>
						<div class="col s5 input-field">
							<textarea class="materialize-textarea" name="motif" id="motif"><?=$motif ?></textarea>
							<label for="motif">Motif de la cotisation</label>
						</div>
					</div>
					<div class="row">
						<div class="col s5 input-field">
							<input type="text" value="<?=$date_depense ?>"  name="date_depense" class="datepicker" id="date_depense" required>
							<label for="date_depense">Date cotisation</label>
						</div>
						<div class="col s3 input-field">
							<input type="number" value="<?=$montant ?>" name="montant" id="montant" required>
							<label for="montant">Montant cotisation</label>
						</div>
					</div>
					<div class="row">
						<div class="col s2 offset-s8 input-field">
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
				if (!confirm('Voulez-vous confirmer la modification de cette dépense ?')) {
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