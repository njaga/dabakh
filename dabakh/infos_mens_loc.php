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
$db->query("SET lc_time_names = 'fr_FR';");
$annee = date('Y');
$req = $db->prepare('SELECT CONCAT(locataire.prenom," ", locataire.nom) FROM locataire WHERE id=?');
$req->execute(array($_GET['id']));
$donnee = $req->fetch();
$locataire = $donnee['0'];
$req->closeCursor();

$req = $db->prepare('SELECT location.id, type_contrat FROM locataire, location 
WHERE location.id_locataire=locataire.id AND locataire.id=?');
$req->execute(array($_GET['id']));
$donnee = $req->fetch();
$id_location = $donnee['0'];
$type_contrat = $donnee['1'];
$req->closeCursor();

$req = $db->prepare('SELECT bailleur.id 
FROM locataire
INNER JOIN location ON location.id_locataire=locataire.id 
INNER JOIN logement ON location.id_logement=logement.id
INNER JOIN bailleur on bailleur.id=logement.id_bailleur
WHERE location.id_locataire=locataire.id AND locataire.id=?');
$req->execute(array($_GET['id']));
$donnee = $req->fetch();
$id_bailleur = $donnee['0'];
$req->closeCursor();


$req_annee = $db->query('SELECT DISTINCT YEAR(date_versement) FROM `mensualite` ORDER BY date_versement DESC');
?>
<!DOCTYPE html>
<html>

<head>
	<title>Infos des mensualités</title>
	<?php include 'entete.php'; ?>
</head>

<body style="background-image: url(<?= $image ?>bgaccueil.jpg);">
	<?php
	include 'verification_menu_immo.php';
	?>
	<br>
	<a class="btn brown" onclick="window.history.go(-1)">Retour</a>
	<div class="container white" style="padding-bottom: 40px">
		<br>
		<div class="row">
			<h4 class="col s12 m12 l12 center"><b>Locataire : <?= $locataire ?></b></h4>
			<input type="text" name="" hidden="" value="<?= $_GET['id'] ?>">
		</div>
		<div class="row">
			<a class="btn col s8 m3 brown" href="i_fac_ins_loc.php?id=<?= $id_location ?>">Facture locative</a>
			&nbsp&nbsp&nbsp&nbsp
			<?php
			if ($type_contrat = "habitation") {
				if ($id_bailleur == 2) {
					echo "<a target='_blank' class='btn tooltipped' data-position='top' data-delay='50' data-tooltip='Imprimer le contrat de la location' href='i_contrat_location1.php?id=$id_location'>Contrat location</a>";
				} elseif ($id_bailleur == 3) {
					echo "<a target='_blank' class='btn tooltipped' data-position='top' data-delay='50' data-tooltip='Imprimer le contrat de la location' href='i_contrat_location3.php?id=$id_location'>Contrat location</a>";
				} else {
					echo "<a target='_blank' class='btn tooltipped' data-position='top' data-delay='50' data-tooltip='Imprimer le contrat de la location' href='i_contrat_location.php?id=$id_location'>Contrat location</a>";
				}
			} else {
				echo "<a target='_blank' class='btn tooltipped' data-position='top' data-delay='50' data-tooltip='Imprimer le contrat de la location' href='i_contrat_location_prof.php?id=$id_location'>Contrat location</a>";
			}
			?>
			<a class="btn col s8 m3 offset-m1 brown" href="i_contrat_location.php?id=<?= $id_location ?>">Contrat location</a>
			&nbsp&nbsp&nbsp&nbsp
			<a class="btn col s8 m3 offset-m1 brown" href="compte_locataire.php?s=<?= $_GET['id'] ?>">Compte locataire</a>
		</div>
		<div class="row">
			<h4 class="center col s12">Dossiers</h4>
			<h5 class="center">
				<?php
				$req_pj = $db->prepare('SELECT * FROM pj_locataire WHERE id_locataire=?');
				$req_pj->execute(array($_GET['id']));
				$nbr = $req_pj->rowCount();
				if ($nbr > 0) {
					while ($donnees_pj = $req_pj->fetch()) {
						echo "<div class='row'>";
						echo "<a class='col s5' href='" . $donnees_pj['2'] . "'>" . $donnees_pj['1'] . "</a>";
						echo "&nbsp&nbsp&nbsp";
						if ($_SESSION['fonction'] == 'administrateur') {
							echo "<a class='col s2 red-text' href='s_pj_locataire.php?s=" . $donnees_pj['0'] . "' onclick='return(confirm(\"Voulez-vous supprimer cette pièce jointe ?\"))'>Supprimer</a>";
						}
						echo "<br>";
						echo "</div>";
					}
				} else {
					echo "Aucun fichier";
				}
				?>
				<br>
				<a href="m_locataire.php?id=<?= $_GET['id'] ?>" class='btn col s2 offset-s8 blue'>Ajouter+</a>
			</h5>
		</div>
		<div class="row">
			<h5 class="col s12 center">Mensualité(s) payée(s)</h5>
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
		</div>
		<div class="row">
			<table class="col s10 offset-s1">
				<thead>
					<th>Date paiement</th>
					<th>Mois</th>
					<th>Montant</th>
					<th>Logement</th>
					<th>Bailleur</th>
				</thead>
				<tbody>
				</tbody>
			</table>
		</div>
	</div>
</body>
<script type="text/javascript">
	$(document).ready(function() {
		function e_mensualite_paye() {
			var annee = $('select:eq(0)').val();
			var id = $('input:eq(0)').val();
			$.ajax({
				type: 'POST',
				url: 'e_mensualite_paye_ajax.php',
				data: 'annee=' + annee + '&id=' + id,
				success: function(html) {
					$('tbody').html(html);
				}
			});
		}
		e_mensualite_paye();
		$('select:eq(0)').change(function() {
			e_mensualite_paye();
		});
	})
</script>
<style type="text/css">
	/*import du css de materialize*/
	@import "../css/materialize.min.css"print;

	/*CSS pour la page à imprimer */
	/*Dimension de la page*/
	@page {
		size: portrait;
		margin: 0px;
		margin-bottom: 10px;
		margin-top: 1px;
	}

	@media print {

		.btn nav {
			display: none;
		}

		p {
			margin-top: -5px;
		}

		.row h5 {
			margin-top: -5px;
		}

	}

	td {
		text-align: center;
		border: 1px solid black;
	}

	th {
		text-align: center;
		border: 1px solid black;
	}
</style>

</html>