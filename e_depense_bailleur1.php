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
//recupération des informations sur le bailleur
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
		<a href="e_mensualite.php" class="btn " >Retour</a>
		<div class="container white">
			<div class="row z-depth-5" style="padding: 10px;">
				<h3 class="center">Nouvelle dépense</h3>
				<h5 class="col s12">Bailleur : <b><?=$bailleur ?></b></h5>

				<form class="col s12" method="POST" id="form" action="e_depense_bailleur_trmnt.php?id=<?=$_GET['id']?>" >
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
								echo '<option value="'. ($annee - 1) .'" >'. ($annee - 1) .'</option>';
								echo '<option value="'. $annee .'" selected>'. $annee .'</option>';
								echo '<option value="'. ($annee + 1) .'" >'. ($annee + 1) .'</option>';
								?>
							</select>
						</div>
					</div>
					<div class="row">
						<div class="col s4">
							<label for="type_depense">Type dépense</label>
							<select class="browser-default " name="type_depense" id="type_depense" required="">
								<option value="" disabled selected="">--Type dépense--</option>
								<option value="Réparation / Maintenance">Réparation / Maintenance</option>
								<option value="Frais judiciaire">Frais judiciaire</option>
								<option value="Autres frais">Autres frais</option>
								<option value="Credit bailleur">Credit bailleur</option>
								<option value="Entretient logement">Entretient logement</option>
							</select>
						</div>
						<div class="col s5 input-field">
							<textarea class="materialize-textarea" name="motif" id="motif"></textarea>
							<label for="motif">Motif de la dépense</label>
						</div>
					</div>
					<div class="row">
						<div class="col s5 input-field">
							<input type="text" value="<?php echo date('Y').'-'.date('m').'-'.date('d') ?>"  name="date_depense" class="datepicker" id="date_depense" required>
							<label for="date_depense">Date dépense</label>
						</div>
						<div class="col s3 input-field">
							<input type="number" value="" name="montant" id="montant" required>
							<label for="montant">Montant dépense</label>
						</div>
					</div>
					<div class="row">
						<p class="col s12 m3">
					      <label>
					        <input name="type_paiement" value="caisse" type="radio" checked />
					        <span>Caisse</span>
					      </label>
					      <label>
					      	<input name="type_paiement" value="banque" type="radio" />
					        <span>Banque</span>
					      </label>
					    <div class="col s12 m3 input-field">
							<input type="number" value="" name="num_cheque" id="num_cheque">
							<label for="num_cheque">N° chèque</label>
						</div>
					    </p>
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