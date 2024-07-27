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
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Enregistrement d'une opération</title>
		<?php include 'entete.php'; ?>
	</head>
	<body style="background-image: url(<?= $image ?>bgaccueil.jpg);">
		<?php
        if ($_SESSION['service']=="immobilier") 
        {
            include 'verification_menu_immo.php';
        }
        elseif ($_SESSION['service']=="sante")
        {
            include 'verification_menu_sante.php';
        }
		else
		{
			include 'verification_menu_cm.php';
		}

		 ?>
		<div class="container white">
			<div class="row z-depth-5" style="padding: 10px;">
				<h3 class="center">Enregistrement d'une opération par la banque BAS</h3>
				<form class="col s12 m12" method="POST" id="form" action="banque_2_trmnt.php" >
					<div class="row">
						<div class="col s12 m4 input-field">
							<select class="browser-default" name="type" required>
								<option value="" disabled selected>Choisir type de l'opération</option>
								<option value="entree">Débit</option>
								<option value="sortie">Crédit</option>
							</select>
						</div>
						<div class="col s12 m6 input-field">
							<select class="browser-default" name="section" required>
								<option value="" disabled selected>Section</option>
                            	<option value="Approvisionnement banque par caisse">Approvisionnement banque par caisse</option>
								<option value="Approvisionnement caisse par banque">Approvisionnement caisse par banque</option>
								<option value="Assurance">Assurance </option>
								<option value="Caisse de sécurité sociale">Caisse de sécurité sociale </option>
								<option value="Telephonie">Telephonie</option>
								<option value="Frais bancaire">Frais bancaire</option>
								<option value="Salaire">Salaire</option>
								<option value="Eau">Eau</option>
								<option value="Electricite">Electricite</option>
								<option value="Fournitures Bureau">Fournitures Bureau</option>
								<option value="IPRESS">IPRESS</option>
								<option value="IMPOTS">IMPOTS</option>
								<option value="Remboursement">Remboursement</option>
								<option value="Remuneration">Remuneration</option>
								<option value="Reglement">Réglement</option>
								<option value="Reglement bailleur">Réglement bailleur</option>
								<option value="Versement">Versement</option>
								<option value="Virement">Virement</option>
								<option value="Diver">Diver</option>
								<option value="Loyer">Loyer</option>
								<option value="Transport">Transport</option>
								<option value="Solde">Solde</option>
							</select>
						</div>
					</div>
					<div class="row">
						<div class="input-field col s12 m8">
							<input type="text" name="motif" id="motif" required>
							<label for="motif">Libellé de l'opération</label>
						</div>
						<div class="input-field col s8 m2 ">
							<input type="text" name="num_cheque" id="num_cheque">
							<label for="num_cheque">N° chéque</label>
						</div>
					</div>
					<div class="row">
						<div class="col s8 m3 input-field">
							<input type="text" class="datepicker" name="date_operation" id="date_operation" required>
							<label for="date_operation">Date de l'opération</label>
						</div>
						<div class="input-field col s8 m6">
							<input type="number" name="montant" id="montant" required>
							<label for="montant">Montant de l'opération</label>
						</div>
						<div class="input-field col s8 m2 ">
							<input type="number" name="pj" id="pj" value="0" required="">
							<label for="pj">N° pièce jointe</label>
						</div>
					</div>
					<div class="row">
						<div class="col s12 m2 offset-m8 input-field">
							<input class="btn" type="submit" name="enregistrer" value="Sauvegarder" >
						</div>
					</div>
				</div>
			</form>
		</div>
	</div>
</body>
<script type="text/javascript">
	$(document).ready(function () {
		$('#form').submit(function () {
			if (!confirm('Voulez-vous confirmer l\'enregistrement de cette opération ?')) {
				return false;
			}
		});
		$('select').formSelect();
		$('.datepicker').datepicker({
		autoClose: true,
		yearRange:[2017,2022],
		showClearBtn: true,
		i18n:{
			nextMonth: 'Mois suivant',
			previousMonth: 'Mois précédent',
			labelMonthSelect: 'Selectionner le mois',
			labelYearSelect: 'Selectionner une année',
			months: [ 'Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre' ],
			monthsShort: [ 'Jan', 'Fev', 'Mar', 'Avr', 'Mai', 'Jun', 'Jul', 'Aou', 'Sep', 'Oct', 'Nov', 'Dec' ],
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
	});
</script>
</html>