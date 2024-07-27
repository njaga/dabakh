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
$req = $db->prepare('SELECT * FROM bailleur WHERE id= ?');
$req->execute(array($_GET['id']));
$nbr = $req->rowCount();
if ($nbr > 0) {
	$donnees = $req->fetch();
	$id = $donnees['0'];
	$num_dossier = $donnees['1'];
	$prenom = $donnees['2'];
	$nom = $donnees['3'];
	$telephone = $donnees['4'];
	$adresse = $donnees['5'];
	$annee_inscription = $donnees['6'];
	$pourcentage = $donnees['7'];
	$etat = $donnees['8'];
	$cni = $donnees['9'];
	$date_debut = $donnees['10'];
	$duree_contrat = $donnees['11'];
}
$req->closeCursor();
$req_type_logement = $db->query("SELECT * FROM type_logement");
?>
<!DOCTYPE html>
<html>

<head>
	<title>Modification d'un bailleur</title>
	<?php include 'entete.php'; ?>
</head>

<body style="background-image: url(<?= $image ?>m_bailleur.jpg);">
	<?php
	include 'verification_menu_immo.php';
	?>
	<div class="container white">
		<div class="row z-depth-5" style="padding: 10px;">
			<h3 class="center">Modification d'un bailleur</h3>
			<form class="col s12" method="POST" id="form" action="m_bailleur_trmnt.php?id=<?= $id ?>" enctype="multipart/form-data">

				<div class="row">
					<div class="input-field">
						<input type="hidden" name="id_bailleur" class="id_bailleur" id="id_bailleur" value="<?= $_GET['id'] ?>">
					</div>
					<div class="col s5 m2 input-field">
						<input type="number" value="<?= $num_dossier ?>" name="num_dossier" id="num_dossier" required>
						<label for="num_dossier">Numéro dossier</label>
					</div>
					<div class="col s7 m3 input-field">
						<select class="browser-default" id="annee_inscription" name="annee_inscription" required>
							<option disabled="">Année d'inscription</option>
							<?php

							for ($i = 12; $i > 0; $i--) {
								echo "<option value='" . (date('Y') - $i) . "'";
								if ($annee_inscription == (date('Y') - $i)) {
									echo "selected";
								}
								echo ">" . (date('Y') - $i) . "</option>";
							}
							echo "<option value='" . (date('Y')) . "'";
							if ($annee_inscription == (date('Y'))) {
								echo "selected";
							}
							echo ">" . (date('Y')) . "</option>";
							echo "<option value='" . (date('Y') + 1) . "'";
							if ($annee_inscription == (date('Y') + 1)) {
								echo "selected";
							}
							echo ">" . (date('Y') + 1) . "</option>";
							?>
						</select>
					</div>
					<div class="col s12 m3 input-field">
						<input type="text" class="datepicker" value="<?= $date_debut ?>" name="date_debut" id="date_debut" required>
						<label for="date_debut">Date début</label>
					</div>
					<div class="col s12 m2 input-field">
						<input type="number" value="<?= $duree_contrat ?>" name="duree_contrat" id="duree_contrat" required>
						<label for="duree_contrat">Durée (année)</label>
					</div>
				</div>
				<div class="row">
					<div class="col s12 m6 input-field">
						<input type="text" name="prenom" value="<?= $prenom ?>" id="prenom" required>
						<label for="prenom">Prénom</label>
					</div>
					<div class="col s12 m5 input-field">
						<input type="text" name="nom" value="<?= $nom ?>" id="nom" required>
						<label for="nom">Nom</label>
					</div>
				</div>
				<div class="row">
					<div class="col s12 m4 input-field">
						<input type="text" value="<?= $telephone ?>" name="telephone" id="telephone" required>
						<label for="telephone">Téléphone</label>
					</div>
					<div class="col s12 m7 input-field">
						<input type="text" name="adresse_bailleur" value="<?= $adresse ?>" id="adresse_bailleur" required>
						<label for="adresse_bailleur">Adresse</label>
					</div>
				</div>
				<div class="row">
					<div class="col s12 m8 input-field">
						<input type="text" name="cni" value="<?= $cni ?>" id="cni" required>
						<label for="cni">Numéro et date CNI</label>
					</div>
				</div>
				<div class="row">
					<div class="col m2 s6 input-field">
						<input type="number" name="pourcentage" id="pourcentage" value="<?= $pourcentage ?>">
						<label id="pourcentage">Pourcentage</label>
					</div>
				</div>
				<!--Pièces Jointes -->
				<div class="row" id="doc">
					<h4 class="center col s12">Pièces Jointes</h4>
					<h5 class="center">
						<?php
						$req_pj = $db->prepare('SELECT * FROM pj_bailleur WHERE id_bailleur=?');
						$req_pj->execute(array($_GET['id']));
						$nbr = $req_pj->rowCount();
						if ($nbr > 0) {
							while ($donnees_pj = $req_pj->fetch()) {
								echo "<div class='row'>";
								echo "<a class='col s5' href='" . $donnees_pj['2'] . "'>" . $donnees_pj['1'] . "</a>";
								echo "&nbsp&nbsp&nbsp";
								if ($_SESSION['fonction'] == 'administrateur') {
									echo "<a class='col s2 red-text' href='s_pj_bailleur.php?s=" . $donnees_pj['0'] . "' onclick='return(confirm(\"Voulez-vous supprimer cette pièce jointe ?\"))'>Supprimer</a>";
								}
								echo "<br>";
								echo "</div>";
							}
						} else {
							echo "Aucun fichier";
						}
						?>
						<br>
					</h5>
					<div class="file-field input-field col s10">
						<div class="btn blue darken-4">
							<span>Sélectionner</span>
							<input type="file" accept="application/pdf" name="fichier[]" class=" fichier" multiple>
						</div>
						<div class="file-path-wrapper">
							<input class="file-path validate fichier" placeholder="Sélectionner le(s) document(s)" type="text">
						</div>
					</div>
				</div>

				<div class="row">
					<h4 class="col s12 center"><u>Liste des logements</u></h4>
					<table class="bordered highlight centered col s12 m10 offset-m1 responsive-table">
						<thead>
							<tr>
								<th>Logement</th>
								<th>Type de logment</th>
								<th>Pu</th>
								<th>Nombre</th>
								<th>Adresse</th>
							</tr>
						</thead>
						<tbody>
							<?php
							$req = $db->prepare("SELECT logement.id, logement.designation, type_logement.type_logement, logement.pu, logement.nbr, logement.adresse , logement.nbr_occupe
									FROM logement, type_logement 
									WHERE logement.id_type=type_logement.id AND logement.etat='actif' AND logement.id_bailleur=?") or die(print_r($req->errorInfo()));
							$req->execute(array($_GET['id']));
							$nbr = $req->rowCount();

							while ($donnees = $req->fetch()) {
								echo "<tr>";
								echo "<td>" . $donnees['1'] . "</td>";
								echo "<td>" . $donnees['2'] . "</td>";
								echo "<td>" . $donnees['3'] . "</td>";
								echo "<td>" . ($donnees['4'] + $donnees['6']) . "</td>";
								echo "<td>" . $donnees['5'] . "</td>";
								echo "<td><a href='supprimer_logement_ajax.php?id=" . $donnees['0'] . "'><i class='material-icons red-text'>clear</i></a></td>";
								echo "<td><a href='a_s_logement.php?id=" . $donnees['0'] . "&amp;nbr=" . $donnees['4'] . "&amp;a=a'>+1</a></td>";
								echo "<td><a href='a_s_logement.php?id=" . $donnees['0'] . "&amp;nbr=" . $donnees['4'] . "&amp;a=s'>-1</a></td>";
								echo "<td><a href='m_logement.php?id=" . $donnees['0'] . "&amp;idb=" . $_GET['id'] . "'><i class='material-icons green-text'>edit</i></a></td>";
								echo "</tr>";
							}
							?>
						</tbody>
					</table>
				</div>
				<br>

				<div class="row" style="border-radius: 20px; border: 1px solid black;">
					<h5 class="center ">Ajout d'un logement</h5>
					<div class="col s12 m4 input-field">
						<select class="browser-default" id="type_logement">
							<option disabled value="" selected="">Type du logement </option>
							<?php
							while ($donnees_type_logement = $req_type_logement->fetch()) {
								echo "<option value='" . $donnees_type_logement['0'] . "'>" . $donnees_type_logement['1'];
								echo "</option>";
							}
							?>
						</select>
					</div>
					<div class="col s12 m6 input-field">
						<input type="text" class="designation " name="designation" id="designation">
						<label for="designation">Désignation</label>
					</div>
					<div class="col s12 m2 input-field">
						<input type="number" class="nbr" name="nbr" id="nbr">
						<label for="nbr">Nombre</label>
					</div>
					<div class="col s12 m2 input-field">
						<input type="number" class="pu" name="pu" id="pu">
						<label for="pu">Prix location HT</label>
					</div>
					<div class="col s12 m4 input-field">
						<input type="text" class="adresse" name="adresse" id="adresse">
						<label for="adresse">Adresse</label>
					</div>
					<div class="col s12 m12 center input-field">
						<a class="btn ajouter">Ajouter+</a>
					</div>
				</div>
				<div class="row">
					<div class="col s2 m2  input-field">
						<?php
						if ($etat == "activer") {
						?>
							<a href="desactiver_bailleur.php?id=<?= $id ?>&amp;etat=<?= $etat ?>" class="btn red">Déscativer</a>
						<?php

						} else {
							$etat = "desactiver";
						?>
							<a href="desactiver_bailleur.php?id=<?= $id ?>&amp;etat=<?= $etat ?>" class="btn blue">Activer</a>
						<?php

						}
						?>

					</div>
					<div class="col s2 m2 offset-m3 offset-s2 input-field">
						<input class="btn" type="submit" name="enregistrer" value="Enregistrer modification(s)">
					</div>
				</div>
			</form>
		</div>
	</div>
</body>
<script type="text/javascript">
	$(document).ready(function() {
		$('.datepicker').datepicker({
			autoClose: true,
			yearRange: [2017, 2022],
			showClearBtn: true,
			i18n: {
				nextMonth: 'Mois suivant',
				previousMonth: 'Mois précédent',
				labelMonthSelect: 'Selectionner le mois',
				labelYearSelect: 'Selectionner une année',
				months: ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'],
				monthsShort: ['Jan', 'Fev', 'Mar', 'Avr', 'Mai', 'Jun', 'Jul', 'Aou', 'Sep', 'Oct', 'Nov', 'Dec'],
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
		$('.ajouter').click(function() {

			var type_logement = $('select:last').val();
			var nbr = $('#nbr').val();
			var designation = $('#designation').val();
			var adresse = $('#adresse').val();
			var id_bailleur = $('#id_bailleur').val();
			var pu = $('#pu').val();
			if (type_logement == null || nbr == "" || designation == "" || adresse == "") {
				alert('Vérifiez si vous avez bien remplis les champs');
			} else {
				$.ajax({
					type: 'POST',
					url: 'ajout_logement_ajax.php',
					data: 'type_logement=' + type_logement + '&nbr=' + nbr + '&designation=' + designation + '&adresse=' + adresse + '&id_bailleur=' + id_bailleur + '&pu=' + pu,
					success: function(html) {
						$('#designation').val("");
						$('#nbr').val("");
						$('#adresse').val("");
						$('#pu').val("");
						$('select:last').val("");
						$('tbody').html(html);
					}
				});
			}
		});
		$('select').formSelect();
		$('#form').submit(function() {
			if (!confirm('Voulez-vous confirmer la modification de ce bailleur ?')) {
				return false;
			}
		});
	});
</script>

</html>