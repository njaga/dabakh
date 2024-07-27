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
$req=$db->prepare("SELECT consommable, qt FROM consommable WHERE id=?");
$req->execute(array($_GET['id']));
$nbr=$req->rowCount();
if ($nbr>0) {
	$donnees=$req->fetch();
	$consommable=$donnees['0'];
	$quantite=$donnees['1'];
}

?>
<!DOCTYPE html>
<html>
	<head>
		<title>Nouvelle opération</title>
		<?php include 'entete.php'; ?>
	</head>
	
	<body style="background-image: url(<?= $image ?>bgaccueil.jpg);">
		<?php
		include 'verification_menu_immo.php';
		?><br>
		<div class="container white">
			<div class="row z-depth-5" style="padding: 10px;">
				<h3 class="center">Nouvelle opération</h3>
				<h5 class="col s12">Consommable : <b><?=$consommable ?></b></h5>
				<h5 class="col s12">Quantité restante : <b><?=$quantite ?></b></h5>
				<input hidden type="number" value="<?=$quantite ?>" id="qt_restante" name="qt_restante" >

				<form class="col s12" method="POST" id="form" action="e_consommable_user1_trmnt.php?id=<?=$_GET['id']?>" >
					<div class="row">
						<div class="col s5 input-field">
							<input type="text" value="<?php echo date('Y').'-'.date('m').'-'.date('d') ?>"  name="date_operation" class="datepicker" id="date_operation" required>
							<label for="date_operation">Date opération</label>
						</div>
						<div class="col s3 input-field">
							<input type="number" value="" name="qt" id="qt" required>
							<label for="qt">Quantité</label>
						</div>
					</div>
					<div class="row">
						<div class="col s4 input-field">
							<input type="text" value=""  name="demandeur"  id="demandeur" required>
							<label for="demandeur">Demandeur</label>
						</div>
					</div>
					<div class="row">
						<div class="col s7 input-field">
							<textarea class="materialize-textarea" id="commentaire" name="commentaire"></textarea>
							<label id="commentaire">Commentaire(s)</label>
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
				var qt_restante=$('#qt_restante').val();
				var qt=$('#qt').val();				
				if (!confirm('Voulez-vous confirmer l\'enregistrement de cette opération ?')) {
					return false;
				}
				if (parseInt(qt_restante)<parseInt(qt)) 
				{
					alert("Quantité saisie supérieur à la quantité restante");
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