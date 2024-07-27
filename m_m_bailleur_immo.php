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
$req=$db->prepare("SELECT CONCAT(bailleur.prenom,' ', bailleur.nom), bailleur.num_dossier, bailleur.annee_inscription, bailleur.pourcentage, logement.designation, logement.pu, logement.nbr_occupe, bailleur.id, mensualite_bailleur.montant, mensualite_bailleur.frais_reparation, mensualite_bailleur.frais_judiciaire, mensualite_bailleur.autres_frais, mensualite_bailleur.date_versement, mensualite_bailleur.mois, mensualite_bailleur.annee
FROM `bailleur`, logement, mensualite_bailleur 
WHERE bailleur.id=logement.id_bailleur AND bailleur.id=mensualite_bailleur.id_bailleur AND mensualite_bailleur.id=?");
$req->execute(array($_GET['id']));
$nbr=$req->rowCount();

$donnees=$req->fetch();
$bailleur=$donnees['0'];
$num_dossier=$donnees['1'];
$annee_inscription=$donnees['2'];
$pourcentage=$donnees['3'];
$designation=$donnees['4'];						
$pu=$donnees['5'];	
$nbr_occupe=$donnees['6'];	
$id_bailleur=$donnees['7'];	
$montant=$donnees['8'];	
$frais_reparation=$donnees['9'];	
$frais_judiciaire=$donnees['10'];	
$autres_frais=$donnees['11'];	
$date_versement=$donnees['12'];	
$mois_location=$donnees['13'];	
$annee_location=$donnees['14'];	

$req_sum=$db->prepare('SELECT SUM(nbr_occupe*pu) 
FROM `logement` WHERE id_bailleur=?');
$req_sum->execute(array($id_bailleur));
$donnee_sum=$req_sum->fetch();
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Modification mensualité bailleur</title>
		<?php include 'entete.php';?>
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
						<th>Nombre</th>
						<th>PU</th>
						<th>Montant</th>
					</thead>
					<tbody>
						
					<?php
						$db->query("SET lc_time_names = 'fr_FR';");
						$req=$db->prepare("SELECT CONCAT(bailleur.prenom,' ', bailleur.nom), bailleur.num_dossier, bailleur.annee_inscription, bailleur.pourcentage, logement.designation, logement.pu, logement.nbr_occupe
						FROM `bailleur`, logement 
						WHERE bailleur.id=logement.id_bailleur AND bailleur.id=?");
						$req->execute(array($id_bailleur));
						while ($donnees=$req->fetch()) 
						{
							
							$bailleur=$donnees['0'];
							$num_dossier=$donnees['1'];
							$annee_inscription=$donnees['2'];
							$pourcentage=$donnees['3'];
							$designation=$donnees['4'];						
							$pu=$donnees['5'];	
							$nbr_occupe=$donnees['6'];
							if ($nbr_occupe>0) 
							{
								echo "<tr>";
								echo "<td>".$designation."</td>";	
								echo "<td>".$nbr_occupe."</td>";	
								echo "<td>".number_format($pu,0,'.',' ')." Fcfa</td>";	
								echo "<td>".number_format(($pu*$nbr_occupe),0,'.',' ')." Fcfa</td>";	
							echo "</tr>";
							}
							
						}
						$commission=($donnee_sum['0']*$pourcentage)/100;
						$total=$donnee_sum['0']-$commission;
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
				

				<form class="col s12" method="POST" id="form" action="m_m_bailleur_trmnt.php?id=<?=$_GET['id'] ?>&amp;id_bailleur=<?=$id_bailleur ?>" >
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
									if ($mois[$i]==$mois_location) {
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
								echo '<option value="'. ($annee_location - 1) .'" >'. ($annee_location - 1) .'</option>';
								echo '<option value="'. $annee_location .'" selected>'. $annee_location .'</option>';
								echo '<option value="'. ($annee_location + 1) .'" >'. ($annee_location + 1) .'</option>';
								?>
							</select>
						</div>
						<div class="col s4 input-field">
							<input type="text" value="<?=$date_versement ?>"  name="date_versement" class=datepicker id="date_versement" required>
							<label for="date_versement">Date versement</label>
						</div>
						
					</div>
					<div class="row">
						<div class="input-field col s3">
							<input type="number" value="<?=$frais_reparation ?>" name="frais_reparation" id="frais_reparation">
							<label for="frais_reparation">Frais de réparation</label>
						</div>
						<div class="input-field col s3">
							<input type="number" value="<?=$frais_judiciaire ?>" name="frais_judiciaire" id="frais_judiciaire">
							<label for="frais_judiciaire">Frais judiciaire</label>
						</div>
						<div class="input-field col s3">
							<input type="number" value="<?=$autres_frais ?>" name="autres_frais" id="autres_frais">
							<label for="autres_frais">Autres frais</label>
						</div>
					</div>
					<div class="row">
						<div class="col s4 input-field hide">
							<input type="number" value="<?= $total ?>" name="montant" id="montant" required>
							<label for="montant">Montant</label>
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
				if (!confirm('Voulez-vous confirmer la modification de cette mensualité ?')) {
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