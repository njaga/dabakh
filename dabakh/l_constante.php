<?php
session_start();
if (!isset($_SESSION['fonction'])) {
?>
<script type="text/javascript">
alert("Veillez d'abord vous connectez !");
window.location = 'index.php';
</script>
<?php
}
$dateFormat = date("Y-m-d");
$jour = array("Dimanche","Lundi","Mardi","Mercredi","Jeudi","Vendredi","Samedi");
$mois = array("","Janvier","Février","Mars","Avril","Mai","Juin","Juillet","Août","Septembre","Octobre","Novembre","Décembre");
$datefr = $mois[date("n")];
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Constantes</title>
		<?php include 'entete.php'; ?>
	</head>
	<body style="background-image: url(<?=$image ?>banniere_immo.png) ;" class="white">
		<?php
		include 'verification_menu_sante.php';
		?>
		<div class="row">
			<h3 class="center #0d47a1 col s12 m12 l12" style="color: white">Constante(s) du </h3>
			<select class="browser-default col s5 m4 offset-m1 l3 offset-l1" name="annee">
				<option value="0" selected="">--Tous les patients--</option>
				
			</select>
			<div class="col s4 offset-s1 m3 offset-m1 l2 offset-l1 white input-field" style="border-radius: 10px">
				<input type="date" name="date_cons" id="date_cons" value="<?php echo date("Y-m-d");?>" required>
				<label  for="date_cons" class="white-text">Date</label>
			</div>
		</div>
		<div class="row">
			<div class="col s12   ">
				<table class="bordered striped highlight centered " style="border-radius: 10px">
					<thead>
						<tr style="color: #0d47a1;">
							<th>Date</th>
							<th>Heure</th>
							<th>N° dossier</th>
							<th>Patient</th>
							<th>Pouls</th>
							<th>Tension</th>
							<th>Température</th>
							<th>Dextro</th>
							<th>%SpO<sub>2</sub></th>
							<th>Conscience</th>
							<th>Vomissement</th>
							<th>Diarrhée</th>
						</tr>
					</thead>
					<tbody>
					</tbody>
				</table>
			</div>
		</div>
		
	</body>
	<style type="text/css">
		
		table
		{
			background: white;
		}
		th
		{
			font: 18pt "times new roman";
			font-weight: bold;
		}
		td
		{
			font: 14pt "times new roman";
			
		}
	</style>
	<script type="text/javascript">
		$(document).ready(function(){
			function constante()
			{
			var jour = $('input').val();
			var patient = $('select').val();
				$.ajax({
					type:'POST',
					url:'l_constante_ajax.php',
					data:'jour='+jour +'&patient='+patient,
					success:function (html) {
						$('tbody').html(html);
					}
					});
			}
			function list_patient()
			{
				var jour = $('input').val();
				$.ajax({
					type:'POST',
					url:'l_constante_patient_ajax.php',
					data:'jour='+jour,
					success:function (html) {
						$('select').html(html);
					}
				});
			}
			list_patient();
			constante();
			$('input').change(function () {
				list_patient();
				constante();
			})
			$('select').change(function () {
				constante();
			})
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
			
			$('.tooltipped').tooltip();
		});
	</script>
	<style type="text/css">
		/*import du css de materialize*/
		@import "../css/materialize.min.css" print;
		/*CSS pour la page à imprimer */
		/*Dimension de la page*/
		@page
		{
			size: portrait;
			margin: 0;
			margin-top: 25px;
		}
		@media print
		{
			.btn{
				display: none;
			}
			nav{
				display: none;
			}
			div
			{
			font: 12pt "times new roman";
			}
			select{
				border-color: transparent
			}
		}
	</style>
</html>