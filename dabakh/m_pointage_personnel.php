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
$req=$db->prepare('SELECT heure_debut, heure_fin, date_pointage, observation, observation_ad FROM pointage_personnel WHERE id=?');
$req->execute(array( $_GET['id']));
$donnees=$req->fetch();
if ($req->rowCount()>0) {
	$heure_debut=$donnees['0'];
	$heure_fin=$donnees['1'];
	$date_pointage=$donnees['2'];
	$observation=$donnees['3'];
	$observation_ad=$donnees['4'];
}
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Pointage personnel</title>
		<?php include 'entete.php'; ?>
	</head>
	<body style="background-image: url(<?= $image ?>bgaccueil.jpg);">
		<?php
	    include 'verification_menu_sante.php';
	    ?>
		<div class="container white">
			<div class="row z-depth-5" style="padding: 10px;">
				<h3 class="center">Pointage personnel </h3>
				<form class="col s12" method="POST" id="form" action="m_pointage_personnel_trmnt.php?id=<?=$_GET['id'] ?>" >
					<div class="row">
						<div class="col s5 input-field">
							<input type="text" value="<?= $date_pointage  ?>"  name="date_pointage" class=datepicker id="date_pointage" required>
							<label for="date_pointage">Date pointage</label>
						</div>
					</div>
					<div class="row">
						<div class="col s2 input-field">
							<input type="time"
							<?php
							if (isset($heure_debut)) {
								echo "value='".$heure_debut."'";
							}
							?>
							 name="heure_debut" id="heure_debut" >
							<label for="heure_debut">Heure début</label>
						</div>
						<div class="col s2 input-field">
							<input type="time"
							<?php
							if (isset($heure_fin)) {
								echo "value='".$heure_fin."'";
							}
							?>
							 name="heure_fin" id="heure_fin" >
							<label for="heure_fin">Heure de fin</label>
						</div>
					</div>
					<div class="row">
						<div class="input-field col s8">
							<textarea class="materialize-textarea" name="observation" id="observation"><?= $observation  ?></textarea>
							<label for="observation">
								<?php
								if ($_SESSION['fonction']=="administrateur" OR $_SESSION['fonction']=="daf" OR $_SESSION['fonction']=="secretaire" OR $_SESSION['fonction']=="medecin" OR $_SESSION['fonction']=="infirmier" ) 
								{
									echo "Remarque(s)";
								}
								elseif ($_SESSION['fonction']=="agent immobilier" OR $_SESSION['fonction']=="agent de sante" ) {
									echo "Observation(s)";
								}
								else
								{
									echo "Zone(s) ou pièce(s) entretenue";
								}
								?>
								
							</label>
						</div>
					</div>
					<div class="row">
						<div class="input-field col s8">
							<textarea class="materialize-textarea" name="observation_ad" id="observation_ad"><?= $observation_ad  ?></textarea>
							<label for="observation_ad">
								Observation(s) administrateur
							</label>
						</div>
					</div>
					<div class="row">
						<div class="col s2 offset-s8 input-field">
							<input class="btn" type="submit" name="enregistrer" value="Sauvegarder" >
						</div>
					</div>
				</form>
			</div>
		</div>
	</body>
	<script type="text/javascript">
		$(document).ready(function () {
			$('form').submit(function () {
				var pwd1=$('input[name=new_password]').val();
				var pwd2=$('input[name=confirm_password]').val();
				if (pwd1==pwd2) 
				{	
					if (!confirm('Voulez-vous confirmer l\'enregistrement du nouveau mot de passe ?')) 
					{
						return false;
					}
				}
				else 
				{
					alert('Erreur : Les mots de passe ne correspondent pas');
					return false;	
				}
			})
			$('.datepicker').datepicker({
		autoClose: true,
		yearRange:[2014,2020],
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
		});
	</script>
</html>