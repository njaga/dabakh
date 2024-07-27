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
$req=$db->prepare('SELECT * FROM banque WHERE id=?');
$req->execute(array($_GET['id']));
$donnees=$req->fetch();
$type=$donnees['1'];
$motif=$donnees['2'];
$num_cheque=$donnees['3'];
$section=$donnees['4'];
$date_operation=$donnees['5'];
$montant=$donnees['6'];
$pj=$donnees['11'];
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Modification d'une opération</title>
		<?php include 'entete.php'; ?>
	</head>
	<body style="background-image: url(<?= $image ?>bgaccueil.jpg);">
	<?php 
        if ($_SESSION['service']=="immobilier") 
        {
            include 'verification_menu_immo.php';
        }
        elseif($_SESSION['service']=="sante")
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
				<h3 class="center">Modification d'une opération banque</h3>
				<form class="col s12" method="POST" id="form" action="m_banque_trmnt.php?id=<?=$_GET['id']?>" >
					<div class="row">
						<div class="col s4 m4 input-field">
							<select class="browser-default" name="type" required>
								<option value="" disabled >Choisir type de l'opération</option>
								<option value="entree" <?php if ($type=='entree') {
									echo "selected";
								} ?> > Débit</option>
								<option value="sortie" <?php if ($type=='sortie') {
									echo "selected";
								} ?>>Crédit</option>
								
							</select>
						</div>
						<div class="col s6 m6 input-field">
							<select class="browser-default" name="section" required>
								<option value="" disabled >Section</option>
								<option value="Approvisionnement banque par caisse" <?php if ($section=='Approvisionnement banque par caisse' ) { echo "selected" ; } ?>>Approvisionnement banque par caisse</option>
								<option value="Approvisionnement caisse par banque" <?php if ($section=='Approvisionnement caisse par banque' ) { echo "selected" ; } ?>>Approvisionnement caisse par banque</option>
								<option value="Materiaux Sanitaire" <?php if ($section=='Materiaux Sanitaire' ) { echo "selected" ; } ?>>Materiaux Sanitaire</option>
								<option value="Materiaux Electricite" <?php if ($section=='Materiaux Electricite' ) { echo "selected" ; } ?>>Materiaux Electricite</option>
								<option value="Versement" <?php if ($section=='Versement' ) { echo "selected" ; } ?>>Versement</option>
								<option value="Assurance" <?php if ($section=='Assurance' ) { echo "selected" ; } ?>>Assurance </option>
								<option value="Telephonie" <?php if ($section=='Telephonie' ) { echo "selected" ; } ?>>Telephonie</option>
								<option value="Electricite" <?php if ($section=='Electricite' ) { echo "selected" ; } ?>>Electricite</option>
								<option value="Salaire" <?php if ($section=='Salaire' ) { echo "selected" ; } ?>>Salaire</option>
								<option value="Frais bancaire" <?php if ($section=='Frais bancaire' ) { echo "selected" ; } ?>>Frais bancaire</option>
								<option value="Fournitures Bureau" <?php if ($section=='Fournitures Bureau' ) { echo "selected" ; } ?>>Fournitures Bureau</option>
								<option value="Remboursement" <?php if ($section=='Remboursement' ) { echo "selected" ; } ?>>Remboursement</option>
								<option value="Remuneration" <?php if ($section=='Remuneration' ) { echo "selected" ; } ?>>Remuneration</option>
								<option value="Reglement" <?php if ($section=='Reglement' ) { echo "selected" ; } ?>>Réglement</option>
								<option value="Diver" <?php if ($section=='Diver' ) { echo "selected" ; } ?>>Diver</option>
								<option value="Loyer" <?php if ($section=='Loyer' ) { echo "selected" ; } ?>>Loyer</option>
								<option value="Transport" <?php if ($section=='Transport' ) { echo "selected" ; } ?>>Transport</option>
								<option value="Transport agent" <?php if ($section=='Transport agent' ) { echo "selected" ; } ?>>Transport agent</option>
								<option value="Transport materiel" <?php if ($section=='Transport materiel' ) { echo "selected" ; } ?>>Transport materiel</option>
								<option value="Main d'oeuvre maçon" <?php if ($section=="Main d'oeuvre maçon" ) { echo "selected" ; } ?>>Main d'oeuvre maçon</option>
								<option value="Main d'oeuvre electricien" <?php if ($section=="Main d'oeuvre electricien" ) { echo "selected" ; } ?>>Main d'oeuvre electricien</option>
								<option value="Main d'oeuvre plombier" <?php if ($section=="Main d'oeuvre plombier" ) { echo "selected" ; } ?>>Main d'oeuvre plombier</option>
								<option value="Autres mains d'oeuvre" <?php if ($section=="Autres mains d'oeuvre" ) { echo "selected" ; } ?>>Autres mains d'oeuvre</option>
								<option value="solde" <?php if ($section=='solde' ) { echo "selected" ; } ?>>Solde</option>
							</select>
						</div>
					</div>
					<div class="row">
						<div class="input-field col s12 m8">
							<input type="text" value="<?=$motif ?>" name="motif" id="motif">
							<label for="motif">Motif de l'opération</label>
						</div>
						<div class="input-field col s8 m2">
							<input type="text" value="<?=$num_cheque ?>" name="num_cheque" id="num_cheque">
							<label for="num_cheque">N° chéque</label>
						</div>
					</div>
					<div class="row">
						<div class="col s8 m3 input-field">
							<input type="text" value="<?=$date_operation ?>" class="datepicker" name="date_operation" id="date_operation" required>
							<label for="date_operation">Date de l'opération</label>
						</div>
						<div class="input-field col s8 m6">
							<input type="number" value="<?=$montant ?>" name="montant" id="montant">
							<label for="montant">Montant de l'opération</label>
						</div>
						<div class="input-field col s8 m2 ">
							<input type="number" name="pj" value="<?=$pj ?>" id="pj">
							<label for="pj">N° pièce jointe</label>
						</div>
					</div>
					<div class="row">
						<div class="col s2 offset-s8 input-field">
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
			if (!confirm('Voulez-vous confirmer la Modification de cette opération ?')) {
				return false;
			}
		});
		$('select').formSelect();
		$('.datepicker').datepicker({
		autoClose: true,
		yearRange:[2017,<?=(date('Y')+1) ?>],
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