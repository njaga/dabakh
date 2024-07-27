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
$req=$db->prepare('SELECT id, CONCAT(prenom," ", nom), num_dossier,annee_inscription
FROM `bailleur`
WHERE id=?');
$req->execute(array($_GET['id']));
$donnees_bailleur=$req->fetch();
$id=$donnees_bailleur['0'];
$bailleur=$donnees_bailleur['1'];
$num_dossier=$donnees_bailleur['2']."/".$donnees_bailleur['3'];
$req->closeCursor();
$req_type_logement=$db->query("SELECT * FROM type_logement");
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Ajout d'un nouveau logement</title>
		<?php include 'entete.php'; ?>
		<style type="text/css">
			body {
			background-image: url(<?=$image ?>banniere_immo.png);
			background-position: center center;
			background-repeat:  no-repeat;
			background-attachment: fixed;
			background-size:  cover;
			background-color: #999;
		
		}
		</style>
	</head>
	<body style="background-image: url(../css/images/e_logement.jpg);">
		<?php
		//include 'verification_menu_immo.php';
		?>
		<div class="container white">
			<div class="row z-depth-5" style="padding: 10px;">
				<h3 class="center">Ajout de nouveau logement</h3>
				<div class="row">
					<h5 class="col s12">
					N° dossier : <b><?= $num_dossier ?></b><br>
					Bailleur : <b><?= $bailleur ?></b>
					</h5>
				</div>
				<div class="input-field">
					<input type="hidden" name="id_bailleur" class="id_bailleur" id="id_bailleur" value="<?= $_GET['id'] ?>">
				</div>
				<div class="row">
					<h4 class="col s12 center"><u>Liste des logements</u></h4>
					<table class="bordered highlight centered col s10 offset-s1">
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
							$req=$db->prepare("SELECT logement.id, logement.designation, type_logement.type_logement, logement.pu, logement.nbr, logement.adresse 
								FROM logement, type_logement 
								WHERE logement.id_type=type_logement.id AND logement.id_bailleur=?") or die(print_r($req->errorInfo()));
								$req->execute(array($_GET['id']));
								$nbr=$req->rowCount();

								while ($donnees=$req->fetch()) 
								{
								echo "<tr>";
									echo "<td>".$donnees['1']."</td>";
									echo "<td>".$donnees['2']."</td>";
									echo "<td>".$donnees['3']."</td>";
									echo "<td>".$donnees['4']."</td>";
									echo "<td>".$donnees['5']."</td>";
									//echo "<td><a href='supprimer_logement_ajax.php?id=".$donnees['0']."&amp;id_bailleur=".$_GET['id']."'><i class='material-icons red-text'>clear</i></a></td>";
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
					<select class="browser-default"  id="type_logement">
						<option disabled value="" selected="">Type du logement </option>
						<?php
						while ($donnees_type_logement=$req_type_logement->fetch()) {
							echo "<option value='".$donnees_type_logement['0']."'>".$donnees_type_logement['1'];
							echo "</option>";
						}
						?>
					</select>
				</div>
				<div class="col s12 m6 input-field">
					<input type="text" class="designation "  name="designation" id="designation">
					<label for="designation">Désignation</label>
				</div>
				<div class="col s12 m2 input-field">
					<input type="number" class="nbr"  name="nbr" id="nbr">
					<label for="nbr">Nombre</label>
				</div>
				<div class="row">
					<div class="col s12 m2 input-field">
						<input type="number" class="pu"  name="pu" id="pu">
						<label for="pu">Prix location HT</label>
					</div>
					<div class="col s12 m4 input-field">
						<input type="text" class="adresse"  name="adresse" id="adresse">
						<label for="adresse">Adresse</label>
					</div>
				</div>
				<div class="col s12 m12 center input-field">
					<a  class="btn ajouter">Ajouter+</a>
				</div>
			</div>
			<div class="row">
				<div class="col s12 m2 right  input-field">
					<a  class="btn brown" href="i_convention_gerance.php?id=<?=$_GET['id']?>">Terminer</a>
				</div>
			</div>
		</div>
	</div>
</body>
<script type="text/javascript">
	$(document).ready(function () {
		$('select').formSelect();
		$('.ajouter').click(function(){
			
			var type_logement = $('select:last').val();
			var nbr = $('#nbr').val();
			var designation = $('#designation').val();
			var adresse = $('#adresse').val();
			var id_bailleur = $('#id_bailleur').val();
			var pu = $('#pu').val();
			if (type_logement==null || nbr=="" || designation=="" || adresse=="" )
			{
				alert('Vérifiez si vous avez bien remplis les champs');
			}
			else
			{
				$.ajax({
					type:'POST',
					url:'ajout_logement_ajax.php',
					data:'type_logement='+type_logement+'&nbr='+nbr+'&designation='+designation+'&adresse='+adresse+'&id_bailleur='+id_bailleur+'&pu='+pu+'&p=e',
					success:function (html) {
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
		$('#form').submit(function () {
			if (!confirm('Voulez-vous confirmer l\'enregistrement de ce nouveau logement ?')) {
				return false;
			}
		});
	});
	
</script>
</html>