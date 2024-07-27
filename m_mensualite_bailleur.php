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
$datefr = $mois[date("n")];
include 'connexion.php';
$req=$db->prepare("SELECT CONCAT(bailleur.prenom,' ', bailleur.nom), mensualite_bailleur.mois, mensualite_bailleur.annee, mensualite_bailleur.autres_frais, mensualite_bailleur.frais_reparation, mensualite_bailleur.frais_judiciaire, bailleur.id, mensualite_bailleur.date_versement, mensualite_bailleur.type_versement, mensualite_bailleur.non_recouvrer, bailleur.pourcentage
FROM `mensualite_bailleur`, bailleur 
WHERE mensualite_bailleur.id_bailleur=bailleur.id AND mensualite_bailleur.id=?");
$req->execute(array($_GET['id']));
$nbr=$req->rowCount();

$donnees=$req->fetch();
$bailleur=$donnees['0'];
$mois_mens=$donnees['1'];
$annee_mens=$donnees['2'];
$autres_frais=$donnees['3'];
$frais_reparation=$donnees['4'];						
$frais_judiciaire=$donnees['5'];	
$id_bailleur=$donnees['6'];	
$date_versement=$donnees['7'];	
$type_versement=$donnees['8'];	
$non_recouvrer=$donnees['9'];	
$pourcentage=$donnees['10'];	

$req_sum=$db->prepare('SELECT SUM(nbr_occupe*pu) 
FROM `logement` WHERE id_bailleur=?');
$req_sum->execute(array($id_bailleur));
$donnee_sum=$req_sum->fetch();
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Modification mensualité bailleur</title>
		<?php include 'entete.php'; ?>
	</head>
	<body style="background-image: url(<?= $image ?>bgaccueil.jpg);">
		<?php
		include 'verification_menu_immo.php';
		?>
		<div class="container white">
			
			<div class="row z-depth-5" style="padding: 10px;">
				<h3 class="center">Modification mensualité</h3>
				<h5 class="col s12">Bailleur : <b><?=$bailleur ?></b></h5>
				<table class="col s8 offset-s2 highlight centered">
					<thead>
						<th>Logement</th>
						<th>PU</th>
						<th>Montant</th>
					</thead>
					<tbody>
						
					<?php
						$db->query("SET lc_time_names = 'fr_FR';");
						$req=$db->prepare("SELECT CONCAT(logement.designation,' : ', type_logement.type_logement), logement.pu,  mensualite.type, mensualite.montant, mensualite.id_mensualite_bailleur
							FROM mensualite, logement, bailleur, location, type_logement
							WHERE logement.id_type=type_logement.id AND mensualite.id_location=location.id AND location.id_logement=logement.id AND logement.id_bailleur=bailleur.id AND mensualite.id_mensualite_bailleur=?");
						$req->execute(array($_GET['id']));
						$total=0;
						while ($donnees=$req->fetch()) 
						{
							
							$logement=$donnees['0'];
							$pu=$donnees['1'];
							$type=$donnees['2'];
							$montant=$donnees['3'];
							$id_mens_bailleur=$donnees['4'];
							
							echo "<tr>";
							echo "<td>".$logement."</td>";	
							echo "<td>".$type."</td>";	
							echo "<td>".number_format($pu,0,'.',' ')." Fcfa</td>";	
							echo "<td>".number_format(($montant),0,'.',' ')." Fcfa</td>";
							$total=$total+$montant;
						echo "</tr>";
							
							
						}
						$commission=($total*$pourcentage)/100;
						$total=$total-$commission;
						echo "<tr class=''>";
							echo "<td colspan='3'>Commission gérence ".$pourcentage."%</td>";
							echo "<td>".number_format($commission,0,'.',' ')." Fcfa</td>";
						echo "</tr>";
						echo "<tr class='grey white-text'>";
							echo "<td colspan='3'>TOTAL</td>";
							echo "<td>".number_format($total,0,'.',' ')." Fcfa</td>";
						echo "</tr>";
						
					?>
					</tbody>
				</table>
				

				<form class="col s12" method="POST" id="form" action="m_mensualite_bailleur_trmnt.php?id=<?=$_GET['id'] ?>" >
					<br>
					<div class="row">
						<div class="col s4">
							<label for="mois">Mois de :</label>
							<select class="browser-default" name="mois" id="mois" required="">
								<option disabled="" >Sélectionner le mois</option>
								<?php
								for ($i=1; $i <= 12; $i++) 
								{
									echo "<option value='$mois[$i]'";
									if ($mois[$i]==$mois_mens) {
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
								<option value="" disabled selected="">--Choisir Annee--</option>
								<?php
								echo '<option value="'. $annee .'"';
								if ($annee==$annee_mens) {
										echo "selected";
									}
								echo'>'. $annee .'</option>';
								echo '<option value="'. ($annee + 1) .'"';
								if (($annee + 1)==$annee_mens) {
										echo "selected";
									}
								echo'>'. ($annee + 1) .'</option>';
								?>
							</select>
						</div>
						<div class="col s4 input-field">
							<input type="text" value="<?=$date_versement ?>"  name="date_versement" class=datepicker id="date_versement" required>
							<label for="date_versement">Date versement</label>
						</div>
				      <div class="row">
				      	<h5 class="col s12">Non recouvrer</h5>
				        <div class="input-field col s10">
				          <?= nl2br($non_recouvrer) ?>
				        </div>
				      </div>
					<div class="row">
						<div class="col s2 offset-s8 input-field">
							<input class="btn" <?php if ($total==0){echo "disabled";} ?> type="submit" name="enregistrer" value="Enregistrer" >
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
				if (!confirm('Voulez-vous confirmer l\'enregistrement de cette nouvelle mensualité ?')) {
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