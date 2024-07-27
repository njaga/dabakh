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
$req_annee=$db->query('SELECT DISTINCT YEAR(date_versement) FROM `mensualite` WHERE date_versement IS NOT NULL ORDER BY date_versement');
$mois = array("","Janvier","Février","Mars","Avril","Mai","Juin","Juillet","Août","Septembre","Octobre","Novembre","Décembre");
$datefr = $mois[date("n")-1];
//recupération des informations sur la location
$db->query("SET lc_time_names = 'fr_FR';");
$req=$db->prepare("SELECT mensualite.montant, CONCAT(locataire.prenom,' ', locataire.nom), CONCAT(logement.designation,' à ',logement.adresse), mensualite.date_versement, mensualite.mois, mensualite.annee, mensualite.type, logement.pu
	FROM `location`, logement, locataire, mensualite
	WHERE location.id_locataire=locataire.id AND location.id_logement=logement.id AND location.id=mensualite.id_location AND mensualite.id=?
	ORDER BY location.date_debut DESC");
$req->execute(array($_GET['id']));
$nbr=$req->rowCount();
if ($nbr>0) {
	$donnees=$req->fetch();
	$montant_mensuel=$donnees['0'];
	$locataire=$donnees['1'];
	$designation=$donnees['2'];
	$date_versement=$donnees['3'];
	$mois1=$donnees['4'];
	$annee=$donnees['5'];
	$type=$donnees['6'];
	$pu=$donnees['7'];
}

?>
<!DOCTYPE html>
<html>
	<head>
		<title>Modification mensualité</title>
		<?php include 'entete.php'; ?>
	</head>
	<body style="background-image: url(<?= $image ?>bgaccueil.jpg);">
		<?php
		//include 'verification_menu_immo.php';
		?><br>
		<a onclick="window.history.go(-1)" class="btn " >Retour</a>
		<div class="container white">
			<div class="row z-depth-5" style="padding: 10px;">
				<h3 class="center">Modification mensualité</h3>
				<h5 class="col s12">Locataire : <b><?=$locataire ?></b></h5>
				<h5 class="col s12">Logement : <b><?=$designation ?></b></h5>
				<h5 class="col s6">Mensualité : <b><?=number_format($pu,0,'.',' ') ?> Fcfa</b></h5>
				<!--<h5 class="col s12">Mois : <b><?=$_GET['m'] ?></b></h5>-->
				<form class="col s12" method="POST" id="form" action="m_mensualite_trmnt.php?id=<?=$_GET['id'] ?>" >
					<br>
					
					<div class="row">
						<div class="col s10">
							<label>
								<input type="radio" name="type" value="complet" 
								<?php
								if ($type=="complet") {echo "checked";}
								?>>
								<span>Montant complet</span>
							</label>
							<label>
								<input type="radio" name="type" value="avance" <?php
								if ($type=="avance") {echo "checked";}
								?>>
								<span>Avance</span>
							</label>
							<label>
								<input type="radio" value="reliquat" name="type" <?php
								if ($type=="reliquat") {echo "checked";}
								?>>
								<span>Reliquat</span>	
							</label>
							
						</div>
					</div>
					<div class="row">
						<div class="col s4">
							<label for="mois">Mois de :</label>
							<select class="browser-default" name="mois" id="mois" required="">
								<option disabled="" >Sélectionner le mois</option>
								<?php
								for ($i=1; $i <= 12; $i++) 
								{
									echo "<option value='$mois[$i]'";
									if ($mois[$i]==$mois1) {
										echo "selected";
									}
									echo">$mois[$i]</option>";
								}
								?>
							</select>
						</div>
						<div class="col s3">
							<label for="annee">Année</label>
							<select class="browser-default " name="annee" id="annee" required="">
								<option value="" disabled>--Choisir Annee--</option>
								<?php
								while ($donnee=$req_annee->fetch())
					                {
					                    echo '<option value="'. $donnee['0'] .'"';
					                    if ($annee==$donnee['0']) {
					                        echo "selected";
					                    }
					                    echo ">"; 
					                    echo $donnee['0'] .'</option>';
					                }
								?>
							</select>
						</div>
					</div>
					<div class="row">
						<div class="col s5 input-field">
							<input type="text" value="<?=$date_versement ?>"  name="date_versement" class=datepicker id="date_versement" required>
							<label for="date_versement">Date versement</label>
						</div>
						<div class="col s4 input-field">
							<input type="number" value="<?= $montant_mensuel ?>" name="montant" id="montant" required>
							<label for="montant">Montant versé</label>
						</div>
					</div>
					<div class="row">
						<div class="col s2 offset-s8 input-field">
							<input class="btn" type="submit" name="enregistrer" value="Modifier" >
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
				if (!confirm('Voulez-vous confirmer la modification de cette nouvelle mensualité ?')) {
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