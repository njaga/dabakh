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
$req=$db->prepare("SELECT demandes_p_a.date_demande, demandes_p_a.type_demande, demandes_p_a.date_debut, demandes_p_a.date_fin, demandes_p_a.heure_debut, demandes_p_a.heure_fin, CONCAT(personnel.prenom,' ', personnel.nom) 
FROM `demandes_p_a`, personnel
WHERE demandes_p_a.id_personnel=personnel.id AND demandes_p_a.id=?");
$req->execute(array($_GET['s']));
$donnees=$req->fetch();
$date_demande=$donnees['0'];
$type_demande=$donnees['1'];
$date_debut=$donnees['2'];
$date_fin=$donnees['3'];
$heure_debut=$donnees['4'];
$heure_fin=$donnees['5'];
$personnel=$donnees['6'];

?>
<!DOCTYPE html>
<html>
	<head>
		<title>Modification d'une demande</title>
		<?php include 'entete.php'; ?>
	</head>
	<body style="background-image: url(<?= $image ?>bgaccueil.jpg);">
		<?php
            include 'verification_menu_sante.php';         
        ?>
		<div class="container white">
			<div class="row z-depth-5" style="padding: 10px;">
				<h3 class="center">Modification demande pour : <br><b><?=$personnel ?></b></h3>
				<form class="col s12" method="POST" id="form" action="m_personnel_demandes_trmnt.php" enctype="multipart/form-data">
					<input type="text" name="id_demande" value="<?=$_GET['s'] ?>" hidden>
					<div class="row">
						<div class="col s6 m4 l4 input-field">
							<input type="text" class="datepicker" value="<?=$date_demande ?>" name="date_demande" id="date_demande" >
							<label for="date_demande">Date demande</label>
						</div>
					</div>
					<div class="row">
						<div class="col s7 m4 l4 input-field">
							<select name="type_demande" class="browser-default type_demande" required>
								<option value="" disabled="">Objet de la demande </option>
								<option value="Autorisation d'absence"
								<?php
								if ($type_demande=="Autorisation d'absence") 
								{
									echo "selected";
								}
								?>
								>
									Autorisation d'absence
								</option>
								<option value="Demande de conge"
								<?php
								if ($type_demande=="Demande de conge") 
								{
									echo "selected";
								}
								?>>
									Demande de conge
								</option>
								<option value="Demande de permission"
								<?php
								if ($type_demande=="Demande de permission") 
								{
									echo "selected";
								}
								?>>
									Demande de permission
								</option>
								<?php
								if ($_SESSION['fonction']=="administrateur" OR $_SESSION['fonction']=="daf") 
								{
									?>
									<option value="Demande d'explication"
									<?php
									if ($type_demande=="Demande d'explication") 
									{
										echo "selected";
									}
									?>>
										Demande d'explication
									</option>
									<?php
								}
								?>
							</select>
						</div>
					</div>
					<div class="row date_demande">
						<div class="col s5 m4 l4 input-field">
							<input type="text" class="datepicker" value="<?=$date_debut ?>" name="date_debut" class="" id="date_debut" >
							<label for="date_debut">Date début</label>
						</div>
						<div class="col s5 m4 l4 input-field">
							<input type="text" class="datepicker" value="<?=$date_fin ?>" name="date_fin" class="" id="date_fin" >
							<label for="date_fin">Date fin</label>
						</div>
					</div>
					<div class="row heure_demande">
						<div class="col s5 m2 l2 input-field">
							<input type="text" class="timepicker" value="<?=$heure_debut ?>" name="heure_debut" class="" id="heure_debut" >
							<label for="heure_debut">Heure début</label>
						</div>
						<div class="col s5 m2 l2 input-field">
							<input type="text" class="timepicker" value="<?=$heure_fin ?>" name="heure_fin" class="" id="heure_fin" >
							<label for="heure_fin">Heure fin</label>
						</div>
					</div>
					<div class="row" id="doc">
						<h4 class="col s12 center">Pièces jointes</h4>
						<div class="col s12">
							<?php
							$req_pj=$db->prepare('SELECT * FROM pj_demandes WHERE id_demande=?');
							$req_pj->execute(array($_GET['s']));
							while ($donnees_pj=$req_pj->fetch()) 
							{
								echo "<div class='row'>";
									echo "<a class='col s6' href='".$donnees_pj['2']."'>".$donnees_pj['1']."</a>";
									echo "&nbsp&nbsp&nbsp";
									echo "<a class='col s2 red-text' href='s_pj_demande.php?s=".$donnees_pj['0']."' onclick='return(confirm(\"Voulez-vous supprimer cette pièce jointe ?\"))'>Supprimer</a>";
									echo "<br>";
								echo "</div>";
							}
							?>
						</div>
							<div class="file-field input-field col s12">
								<div class="btn blue darken-4">
									<span >Sélectionner</span>
									<input type="file" accept="application/pdf" name="fichier[]" class=" fichier" multiple>
								</div>
								<div class="file-path-wrapper">
									<input class="file-path validate fichier" placeholder="Sélectionner le(s) document(s)"  type="text" >
								</div>
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
	<script type="text/javascript">
		$(document).ready(function () {
			
			$('.date_demande').addClass('hide');
			$('.heure_demande').addClass('hide');
			function demande()
			{
				var s=$('.type_demande').val();
				if (s=="Demande de permission")
				{
					$('.date_demande').removeClass('hide');
					$('.heure_demande').addClass('hide');
					$('.date_emission_reception').addClass('hide');
				}
				else if (s=="Demande de conge") 
				{
					$('.date_demande').removeClass('hide');
					$('.heure_demande').addClass('hide');
					$('.date_emission_reception').addClass('hide');
				}
				else if (s=="Demande d'explication") 
				{
					$('.date_emission_reception').removeClass('hide');
					$('.date_demande').addClass('hide');
					$('.heure_demande').addClass('hide');
				}
				else if (s=="Autorisation d'absence")
				{
					$('.heure_demande').removeClass('hide');
					$('.date_demande').addClass('hide');
					$('.date_emission_reception').addClass('hide');
				}
			}
			demande();
			$('.type_demande').change(function(){
				demande();
			});		
			$('.type_demande').formSelect();
			$('#form').submit(function () {
				if (!confirm('Voulez-vous confirmer l\'enregistrement ?')) {
					return false;
				}
			});
			$('.timepicker').timepicker({
				showClearBtn:true,
				twelveHour:false,
				i18n:{
					cancel:'Annuler',
					done:'OK',
					clear:'Réinitialiser'
				}
			});
			$('.datepicker').datepicker({
				autoClose: true,
				yearRange:[2018,<?=(date('Y')+1) ?>],
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