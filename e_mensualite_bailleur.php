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

$total = 0;
$annee = date('Y');
$mois = array("", "Janvier", "Février", "Mars", "Avril", "Mai", "Juin", "Juillet", "Août", "Septembre", "Octobre", "Novembre", "Décembre");
$datefr = $mois[date("n")];
$req_annee = $db->query('SELECT DISTINCT YEAR(date_versement) FROM `mensualite_bailleur` ORDER BY date_versement DESC');
$reqSumLogPu = $db->prepare("SELECT  COUNT(location.id), SUM(location.prix_location) FROM logement, locataire, location, bailleur, type_logement WHERE location.id_locataire=locataire.id AND location.id_logement=logement.id AND logement.id_bailleur=bailleur.id AND type_logement.id = logement.id_type AND location.etat='active' AND bailleur.id=?");
$reqSumLogPu->execute(array($_GET['id']));
$donneesSumLogPu = $reqSumLogPu->fetch();
$sumLog = $donneesSumLogPu['0'];
$sumPu = $donneesSumLogPu['1'];
$req = $db->prepare("SELECT CONCAT(bailleur.prenom,' ', bailleur.nom), bailleur.num_dossier, bailleur.annee_inscription, bailleur.pourcentage, logement.designation, logement.pu, logement.nbr_occupe
FROM `bailleur`, logement
WHERE bailleur.id=logement.id_bailleur AND bailleur.id=?");
$req->execute(array($_GET['id']));
$nbr = $req->rowCount();
$donnees = $req->fetch();
$bailleur = strtoupper($donnees['0']);
$num_dossier = $donnees['1'];
$annee_inscription = $donnees['2'];
$pourcentage = $donnees['3'];
$designation = $donnees['4'];
$pu = $donnees['5'];
$nbr_occupe = $donnees['6'];
$req_sum = $db->prepare('SELECT SUM(nbr_occupe*pu)
FROM `logement` WHERE id_bailleur=?');
$req_sum->execute(array($_GET['id']));
$donnee_sum = $req_sum->fetch();
?>
<!DOCTYPE html>
<html>

<head>
	<title>Nouvelle mensualité bailleur</title>
	<?php include 'entete.php'; ?>
</head>

<body style="background-image: url(<?= $image ?>e_mensualite_bailleur.jpg) ;">
	<?php
	include 'verification_menu_immo.php';
	?>
	<div class="container white">
		<br>
		<a href="compte_bailleur.php?id=<?= $_GET['id'] ?>" class="btn col s3 m3 l2">Compte bailleur</a>
		<div class="row">
			<h3 class="center col s12">Mensualité(s) versée(s)</h3>
			<select class="browser-default col s3 offset-s1  m3 offset-m1 l2 offset-l1" name="annee">
				<option value="" disabled>--Choisir Annee--</option>
				<?php
				while ($donnee = $req_annee->fetch()) {
					echo '<option value="' . $donnee['0'] . '"';
					if ($annee == $donnee['0']) {
						echo "selected";
					}
					echo ">";
					echo $donnee['0'] . '</option>';
				}
				?>
			</select>
			<table class="col s10 offset-s1 highlight centered">
				<thead class="grey darken-4 white-text">
					<th>Date versement</th>
					<th>Mois</th>
					<th>Montant</th>
					<th></th>
				</thead>
				<tbody class="tbody_paye">


				</tbody>
			</table>
		</div>
		<div class="row z-depth-5" style="padding: 10px;">

			<form class="col s12" method="POST" id="form" action="e_mensualite_bailleur_trmnt.php?id=<?= $_GET['id'] ?>">
				<input type="number" hidden name="id" value="<?= $_GET['id'] ?>">
				<h3 class="center">Nouvelle mensualité</h3>
				<h5 class="col s12">
					Bailleur : <b><?= $bailleur ?></b>
					<br>
					Nbr logements : <b><?= str_pad($sumLog, 2, "0", STR_PAD_LEFT) ?></b> &nbsp&nbsp Total : <b><?= number_format($sumPu, 0, '.', ' ') ?></b>
				</h5>
				<div class="row">
					<div class="col s4">
						<label for="mois">Mois de :</label>
						<select class="browser-default" name="mois" id="mois" required="">
							<option selected="" value="0">Sélectionner le mois</option>
							<?php
							for ($i = 1; $i <= 12; $i++) {
								echo "<option value='$mois[$i]'";
								/*
											if ($mois[$i+1]==$datefr) {
												echo "selected";
											}
											*/
								echo ">$mois[$i]</option>";
							}
							?>
						</select>
					</div>
					<div class="col s3">
						<label for="annee">Année</label>
						<select class="browser-default " name="annee" id="annee" required="">
							<option value="" disabled>--Choisir Annee--</option>
							<?php
							echo '<option value="' . ($annee - 2) . '" >' . ($annee - 2) . '</option>';
							echo '<option value="' . ($annee - 1) . '" >' . ($annee - 1) . '</option>';
							echo '<option value="' . $annee . '" selected>' . $annee . '</option>';
							echo '<option value="' . ($annee + 1) . '" >' . ($annee + 1) . '</option>';
							?>
						</select>
					</div>
					<div class="col s4 input-field">
						<input type="text" value="<?php echo date('Y') . '-' . date('m') . '-' . date('d') ?>" name="date_versement" class=datepicker id="date_versement" required>
						<label for="date_versement">Date versement</label>
					</div>

				</div>
				<div class="row">
					<p class="col s12 m8">
						<label>
							<input class="mois_a_payer" name="mois_a_payer" value="tous" type="radio" checked />
							<span>Tous les mois</span>
						</label>
						<label>
							<input class="mois_a_payer" name="mois_a_payer" value="seul" type="radio" />
							<span>Seul le mois sélectionner</span>
						</label>
					</p>
				</div>
				<div class="row tbody">

				</div>
				<br>
				<div class="row">
					<p class="col s12 m8">
						<label>
							<input name="type_paiement" class="type_paiement" value="caisse" type="radio" checked />
							<span>Caisse</span>
						</label>
						<label>
							<input name="type_paiement" class="type_paiement" value="banque" type="radio" />
							<span>Banque</span>
						</label>
						<label>
							<input name="type_paiement" class="type_paiement" value="caisse_banque" type="radio" />
							<span>Caisse & Banque</span>
						</label>
					</p>
				</div>
				<div class="row">
					<div class="col s12 m3 input-field" id="num_cheque">
						<input type="number" value="" name="num_cheque" id="num_cheque_val">
						<label for="num_cheque_val">N° chèque</label>
					</div>
					<div class="col s12 m3 input-field banque_caisse">
						<input type="number" class="" value="" name="montant_caisse" id="montant_caisse">
						<label for="montant_caisse">Montant caisse</label>
					</div>
					<div class="col s12 m3 input-field banque_caisse">
						<input type="number" class="" value="" name="montant_banque" id="montant_banque">
						<label for="montant_banque">Montant banque</label>
					</div>
				</div>
				<div class="row">
					<div class="col s2 offset-s8 input-field">
						<input class="btn" type="submit" name="enregistrer" value="Enregistrer">
					</div>
				</div>
			</form>
		</div>
		</dsiv>
</body>
<style type="text/css">
	tr td {
		border: 1px solid;
	}

	th {
		border: 1px solid;
	}
</style>
<script type="text/javascript">
	$(document).ready(function() {
		function e_mensualite_paye() {
			var annee = $('select:eq(0)').val();
			var id = $('input:eq(0)').val();
			$.ajax({
				type: 'POST',
				url: 'e_mensualite_bailleur_paye_ajax.php',
				data: 'annee=' + annee + '&id=' + id,
				success: function(html) {
					$('.tbody_paye').html(html);
				}
			});
		}

		function e_mensualite() {
			var mois = $('select:eq(1)').val();
			var annee = $('select:eq(2)').val();
			var id = $('input:eq(0)').val();
			var mois_a_payer = $('.mois_a_payer:checked').val();
			$.ajax({
				type: 'POST',
				url: 'e_mensualite_bailleur_ajax.php',
				data: 'mois=' + mois + '&annee=' + annee + '&id=' + id + '&mois_a_payer=' + mois_a_payer,
				success: function(html) {
					if (mois != "0") {
						$('.tbody').html(html);
					}

				}
			});
		}

		function verif_type_paiement() {
			var type_paiement = $('.type_paiement:checked').val();
			if (type_paiement == "caisse") {
				$('#num_cheque').addClass('hide');
				$('.banque_caisse').addClass('hide');
			} else if (type_paiement == "banque") {
				$('#num_cheque').removeClass('hide');
				$('.banque_caisse').addClass('hide');
			} else {
				$('#num_cheque').removeClass('hide');
				$('.banque_caisse').removeClass('hide');
			}
		}
		e_mensualite();
		e_mensualite_paye();
		verif_type_paiement();
		$('select:eq(0)').change(function() {
			e_mensualite_paye();
		});
		$('select:eq(1)').change(function() {
			e_mensualite();
		});
		$('select:eq(2)').change(function() {
			e_mensualite();
		});
		$('.mois_a_payer').change(function() {
			e_mensualite();
		});
		$('.type_paiement').change(function() {
			verif_type_paiement();
		});

		$('select').formSelect();
		$('#form').submit(function() {
			var type_paiement = $('.type_paiement:checked').val();
			var montant_caisse = $('#montant_caisse').val();
			var montant_banque = $('#montant_banque').val();
			var num_cheque = $('#num_cheque_val').val();
			if (type_paiement == "caisse_banque") {
				if (montant_caisse <= 0 || montant_banque <= 0) {
					alert("Erreur \nVeillez saisir les montants ");
					return false;
				}
			}
			if (type_paiement == "banque") {
				if (num_cheque == "") {
					alert("Erreur \nVeillez saisir le numéro de chèque ");
					return false;
				}
			}

			if (!confirm('Voulez-vous confirmer l\'enregistrement de cette nouvelle mensualité ?')) {
				return false;
			}
		});
	});
	$('.datepicker').datepicker({
		autoClose: true,
		yearRange: [2014, 2022],
		showClearBtn: true,
		i18n: {
			nextMonth: 'Mois suivant',
			previousMonth: 'Mois précédent',
			labelMonthSelect: 'Selectionner le mois',
			labelYearSelect: 'Selectionner une année',
			months: ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'],
			monthsShort: ['Jan', 'Fev', 'Mar', 'Avr', 'Mai', 'Jun', 'Jul', 'Août', 'Sep', 'Oct', 'Nov', 'Dec'],
			weekdays: ['Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'],
			weekdaysShort: ['Dim', 'Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam'],
			weekdaysAbbrev: ['D', 'L', 'M', 'M', 'J', 'V', 'S'],
			today: 'Aujourd\'hui',
			clear: 'Réinitialiser',
			cancel: 'Annuler',
			done: 'OK'

		},
		format: 'yyyy-mm-dd'
	});
</script>

</html>