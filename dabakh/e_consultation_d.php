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
$date_consultation=date('Y').'-'.date('m').'-'.date('d');

$reponse=$db->prepare("SELECT patient.id_patient, patient.prenom, patient.nom, CONCAT(day(patient.date_naissance),'-',month(patient.date_naissance),'-',year(patient.date_naissance)), patient.lieu_naissance, patient.profession, patient.domicile, patient.telephone, patient.sexe, patient.situation_matrimoniale
FROM patient
WHERE id_patient=?");
$reponse->execute(array($_GET['p']));
$donnees=$reponse->fetch();
$id_patient=$donnees['0'];
$prenom=$donnees['1'];
$nom=$donnees['2'];
$date_naissance=$donnees['3'];
$lieu_naissance=$donnees['4'];
$profession=$donnees['5'];
$domicile=$donnees['6'];
$telephone=$donnees['7'];
$sexe=$donnees['8'];
$situation_mat=$donnees['9'];

$req=$db->query("SELECT MAX(id_consultation) FROM consultation_domicile");
$donnee=$req->fetch();
$id_consultation=$donnee['0']+1;
$req->closeCursor();
$req=$db->prepare("INSERT INTO consultation_domicile(id_consultation, date_consultation, id_patient) VALUES (?,?,?)");
$req->execute(array($id_consultation, $date_consultation, $_GET['p']));
$id_consultation=$db->lastInsertId();

$req_soins=$db->query("SELECT * FROM `soins_domicile` WHERE etat=0");
$req_produit=$db->query("SELECT * FROM produit WHERE etat=0");

?>
<!DOCTYPE html>
<html>
	<head>
		<title>Enregistrement du soins à domicile</title>
		<?php include 'entete.php'; ?>
	</head>
	<body style="background-image: url(<?= $image ?>bgaccueil.jpg);">
		<?php include 'verification_menu_sante.php'; ?>
		<div class="row grey white-text">
			<div class="col s5">
				<h6 class="col s12">Prénom et Nom : <b><?=$id_consultation ?> <?=$nom?></b></h6>
				<h6 class="col s12">Profession : <b><?=$profession?></b></h6>
			</div>
			<div class="col s3">
				<h6 class="col s12">Domicile : <b><?=$domicile?></b></h6>
				<h6 class="col s12">Téléphone : <b><?=$telephone?></b></h6>
			</div>
			<div class="col s3">
				<h6 class="col s12">Sexe : <b><?=$sexe?></b></h6>
				<h6 class="col s12"><b><?=$situation_mat ?></b></h6>
			</div>
		</div>
		<div class="container white">
			<div class="row z-depth-5" style="padding: 10px;">
				<h4 class="center">
				Enregistrement d'une consultation
				</h4>
				<form class="col s12" method="POST" id="form" action="e_consultation_d_trmnt.php?p=<?=$_GET['p']?>" >
					<div class="row">
						<!-- Recupération de l'id de la consultation -->
						<div class="input-field">
							<input type="hidden" name="consultation" class="cons" id="cons" value="<?= $id_consultation ?>">
						</div>
					</div>

					<div class="row">
						<div class="col s12 m3 input-field">
							<input type="text" value="<?php echo date('Y').'-'.date('m').'-'.date('d') ?>" class="datepicker" name="date_consultation" id="date_consultation" required>
							<label for="date_consultation">Date de la consultation</label>
						</div>
					</div>
					<div class="row">
						<div class="col s12 m2 input-field">
							<input type="text"   name="poids" id="poids" >
							<label for="poids">Poids en Kg</label>
						</div>
						<div class="col s12 m2 input-field">
							<input type="text" name="tension" id="tension" >
							<label for="tension">Tension</label>
						</div>
						<div class="col s12 m2 input-field">
							<input type="text"  name="pouls" id="pouls" >
							<label for="pouls">Pouls</label>
						</div>
						<div class="col s12 m2 input-field">
							<input type="text" name="temperature" id="temperature" >
							<label for="temperature">Température</label>
						</div>
						
					</div>
					<div class="row">
						<div class="col s12 m2 input-field">
							<input type="text" name="dextro" id="dextro" >
							<label for="dextro">Dextro</label>
						</div>
						<div class="col s3 input-field">
							<select class="browser-default" required="" name="tdr" id="tdr">
								<option disabled value=""  selected="">TDR</option>
								<option value="non defini">Non défini</option>
								<option value="positif" >Positif</option>
								<option value="negatif" >Négatif</option>
							</select>
						</div>
					</div>
					<div class="row">
						<div class="input-field col s12 m6">
							<textarea id="allergie" name="allergie" class="materialize-textarea"></textarea>
							<label for="allergie">Allergie</label>
						</div>
						<div class="input-field col s12 m6">
							<textarea id="plaintes" name="plaintes" class="materialize-textarea"></textarea>
							<label for="plaintes">Plaintes</label>
						</div>
					</div>
					<div class="row">
						<div class="input-field col s12 m8">
							<textarea id="remarques" name="remarques" class="materialize-textarea"></textarea>
							<label for="remarques">Remarques</label>
						</div>
					</div>
					<!--liste des soins-->
					<div class="row">
						<h4 class="col s12 center">Soins</h4>
						<table class="bordered highlight centered">
							<thead>
								<tr>
									<th>Soins</th>
									<th>Pu</th>
									<th>Quantité</th>
									<th>Montant</th>
								</tr>
							</thead>
							<tbody class="l_soins_domicile">
								<?php
									$req=$db->prepare("SELECT  soins_domicile.soins, soins_domicile.pu, soins_domicile_patient.quantite, soins_domicile_patient.montant, soins_domicile.id
										FROM soins_domicile_patient, soins_domicile 
										WHERE soins_domicile_patient.id_soins=soins_domicile.id AND soins_domicile_patient.id_consultation=?");
									$req->execute(array($id_consultation));
									$nbr=$req->rowCount();
									while ($donnees_l_soins=$req->fetch())
									{
										echo "<tr>";
												echo "<td>".$donnees_l_soins['0']."</td>";
												echo "<td>".$donnees_l_soins['1']."</td>";
												echo "<td>".$donnees_l_soins['2']."</td>";
												echo "<td>".$donnees_l_soins['3']."</td>";
												echo "<td><a href='supprim_sd.php?id_cons=".$id_consultation."&amp;id_soins=".$donnees_l_soins['4']."&amp;p=sd'><i class='material-icons red-text'>clear</i></a></td>";
												echo "</tr>";
										}
									$req=$db->prepare("SELECT SUM(soins_domicile_patient.montant) FROM soins_domicile_patient WHERE id_consultation=?");
									$req->execute(array($id_consultation));
									$donnees=$req->fetch();
										echo '<input type="hidden" name="cout_soins" class="cout" id="cout" value="'.$donnees['0'].'">';
									echo "<tr class='grey'>";
														echo "<td>TOTAL</td>";
														echo "<td></td>";
														echo "<td></td>";
														echo "<td><b>".number_format($donnees['0'],0,'.',' ')." Fcfa</b></td>";
									echo "</tr>";
								?>
							</tbody>
						</table>
					</div>

					<div class="row">
						<div class="col s12 m4 input-field">
							<select class="browser-default"  id="soins_domicile">
								<option disabled value="" selected="">Soins à domicile </option>
								<?php
								while ($donnees_soins=$req_soins->fetch()) {
									echo "<option value='".$donnees_soins['0']."'>".$donnees_soins['1'];
									echo "</option>";
								}
								?>
							</select>
						</div>
						<div class="col s12 m2 input-field">
							<input type="number" class="qt_soins"  name="qt_soins" id="qt_soins">
							<label for="qt_soins">Quantité</label>
						</div>
						<div class="col s12 m2  input-field">
							<a  class="btn ajouter_soins">Ajouter+</a>
						</div>
					</div>


					<!--liste des produits-->
					<div class="row hide">
						<h4 class="col s12 center">Produits</h4>
						<table class="bordered highlight centered">
							<thead>
								<tr>
									<th>Produit</th>
									<th>Pu</th>
									<th>Quantité</th>
									<th>Montant</th>
								</tr>
							</thead>
							<tbody class="l_produits">
								<?php
									$req=$db->prepare("SELECT  produit.produit, produit.pu, vente_produit.quantite,vente_produit.montant, produit.id
									FROM consultation_domicile, vente_produit, produit
									WHERE consultation_domicile.id_consultation=vente_produit.id_consultation_domicile AND vente_produit.id_produit=produit.id AND consultation_domicile.id_consultation=?");
									$req->execute(array($id_consultation));
									$nbr=$req->rowCount();
									while ($donnees_l_produits=$req->fetch())
									{
										echo "<tr>";
												echo "<td>".$donnees_l_produits['0']."</td>";
												echo "<td>".$donnees_l_produits['1']."</td>";
												echo "<td>".$donnees_l_produits['2']."</td>";
												echo "<td>".$donnees_l_produits['3']."</td>";
												echo "<td><a href='supprimer_produit.php?id_cons_d=".$_GET['id']."&amp;id_prod=".$donnees_l_produits['4']."&amp;p=sd'><i class='material-icons red-text'>clear</i></a></td>";
												echo "</tr>";
										}
									$req=$db->prepare("SELECT SUM(vente_produit.montant) FROM vente_produit WHERE vente_produit.id_consultation=?");
									$req->execute(array($id_consultation));
									$donnees=$req->fetch();
										echo '<input type="hidden" name="cout_produit" class="cout" id="cout" value="'.$donnees['0'].'">';
									echo "<tr class='grey'>";
														echo "<td>TOTAL</td>";
														echo "<td></td>";
														echo "<td></td>";
														echo "<td><b>".number_format($donnees['0'],0,'.',' ')." Fcfa</b></td>";
									echo "</tr>";
								?>
							</tbody>
						</table>
					</div>
					<div class="row hide">
						<div class="col s12 m4 input-field">
							<select class="browser-default"  id="produit">
								<option disabled value="" selected="">Veillez sélectionner le produit </option>
								<?php
								while ($donnees_produit=$req_produit->fetch()) {
									echo "<option value='".$donnees_produit['0']."'>".$donnees_produit['1'];
									echo "</option>";
								}
								?>
							</select>
						</div>
						<div class="col s12 m2 input-field">
							<input type="number" class="qt"  name="qt_produit" id="qt_produit">
							<label for="qt">Quantité</label>
						</div>
						<div class="col s12 m2  input-field">
							<a  class="btn ajouter_produits">Ajouter+</a>
						</div>
					</div>
					<div class="row">
						<div class="col s12 m2 offset-m8 input-field">
							<input class="btn" type="submit" name="enregistrer" value="Enregistrer" >
						</div>
					</div>
				</form>
			</div>
		</div>
	</body>
	<script type="text/javascript">
		$(document).ready(function () {
			
				$('.ajouter_soins').click(function(){
					var soins = $('#soins_domicile').val();
					var qt = $('#qt_soins').val();
					var consultation = $('#cons').val();
					if (soins==null || qt=="" )
					{
						alert('Soins et ou Quantité null');
					}
					else
					{
						$.ajax({
							type:'POST',
							url:'ajout_soins_domicile_ajax.php',
							data:'soins='+soins+'&qt='+qt+'&consultation='+consultation+'&p=sd',
							success:function (html) {
								$('.l_soins_domicile').html(html);
													}
						});
					}
				});
				//produit
			$('.ajouter_produits').click(function(){
				var produit = $('#produit').val();
				var qt = $('#qt_produit').val();
				var consultation = $('#cons').val();
				if (produit==null || qt=="" )
				{
					alert('Produit et ou Quantité null');
				}
				else
				{
					$.ajax({
						type:'POST',
						url:'ajout_produit_ajax.php',
						data:'produit='+produit+'&qt='+qt+'&consultation='+consultation+'&p=sd',
						success:function (html) {
							$('.l_produits').html(html);
												}
					});
				}
			});
			$('#form').submit(function () {
				if (!confirm('Voulez-vous confirmer l\'enregistrement ?')) {
					return false;
				}
			});
			$('select').formSelect();
			$('.datepicker').datepicker({
				autoClose: true,
				yearRange:[2017,2021],
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