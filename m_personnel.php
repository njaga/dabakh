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
$req=$db->prepare("SELECT * FROM personnel WHERE id=? ");
$req->execute(array($_GET['id']));
$resultat=$req->rowCount();
$donnees= $req->fetch();
$id=$donnees['0'];
$matricule=$donnees['1'];
$prenom=$donnees['2'];
$nom=$donnees['3'];
$sexe=$donnees['4'];
						$fonction=$donnees['5'];
$telephone=$donnees['6'];
	$date_embauche=$donnees['7'];
	$login=$donnees['8'];
$service=$donnees['10'];
$etat=$donnees['11'];
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Modification personnel</title>
		<?php include 'entete.php'; ?>
	</head>
	<body style="background-image: url(<?= $image ?>bgaccueil.jpg);">
		<?php
		include 'verification_menu_sante.php';
		?>
		<div class="container white">
			<div class="row z-depth-5" style="padding: 10px;">
				<h3 class="center">Modification personnel</h3>
				<form class="col s12" method="POST" id="form" action="m_personnel_trmnt.php?id=<?=$_GET['id']?>" enctype="multipart/form-data">
					<div class="row">
						<div class="col s6 m6 l4 input-field">
							<input type="text" value="<?=$matricule ?>" name="matricule" id="matricule" required>
							<label for="matricule">Matricule</label>
						</div>
					</div>
					<div class="row">
						<div class="col s6 m6 l4 input-field">
							<input type="text" value="<?=$prenom ?>" name="prenom" id="prenom" required>
							<label for="prenom">Prénom</label>
						</div>
						<div class="col s5 m6 l4 input-field">
							<input type="text" value="<?=$nom ?>" name="nom" id="nom" required>
							<label for="nom">Nom</label>
						</div>
						<div class="col s6 m6 l2 input-field">
							<select name="sexe" class="browser-default" required>
								<option value="" disabled=""  >-- SEXE --</option>
								<option value="Feminin"
									<?php
									if ($sexe=="Feminin")
									{
										echo "selected";
									}
								?>>Feminin</option>
								<option value="Masculin"
									<?php
									if ($sexe=="Masculin")
									{
										echo "selected";
									}
								?>>Masculin</option>
								DAF
							</option>
							
						</select>
					</div>
				</div>
				<div class="row">
					<div class="col s4 input-field">
						<input type="text" value="<?=$telephone?>" name="telephone" id="telephone" required>
						<label for="telephone">Téléphone</label>
					</div>
					<div class="col s7 input-field">
						<select name="fonction" class="browser-default" required>
							<option value="" disabled="" >
								Veillez choisir la fonction
							</option>
							<option value="administrateur" <?php if ($fonction=="administrateur"){echo "selected";} ?>>
								Administrateur
							</option>
							<option value="daf" <?php if ($fonction=="comptable"){
								echo "selected";} ?>>
								Comptable
							</option>
							<option value="daf" <?php if ($fonction=="daf"){
								echo "selected";} ?>>
								DAF
							</option>
							<option value="secretaire" <?php if ($fonction=="secretaire"){echo "selected";} ?>>
								Secrétaire
							</option>
							<option value="medecin"
								<?php if ($fonction=="medecin"){echo "selected";} ?>>
								Medecin
							</option>
							<option value="infirmier" <?php
								if ($fonction=="infirmier"){echo "selected";} ?>>
								Infirmier(e)
							</option>
							<option value="Agent commercial" <?php if ($fonction=="agent commercial"){
								echo "selected";} ?>>
								Agent commercial
							</option>
							<option value="agent immobilier" <?php if ($fonction=="agent immobilier"){echo "selected";} ?>>
								Agent immobilier(e)
							</option>
							<option value="agent de sante" <?php if ($fonction=="agent de sante"){echo "selected";} ?>>
								Agent de santé
							</option>
							<option value="agent responsable des opérations de caisse" <?php if ($fonction=="agent responsable des opérations de caisse"){echo "selected";} ?>>
							Agent responsable des opérations de caisse
							</option>
							<option value="caissier" <?php if ($fonction=="caissier"){echo "selected";} ?>>
								Caissier(e)
							</option>
							<option value="femme de charge" <?php if ($fonction=="femme de charge"){echo "selected";} ?>>
								Femme de charge
							</option>
							<option value="stagiaire" <?php if ($fonction=="stagiaire"){echo "selected";} ?>>
								Stagiaire
							</option>
							<option value="chauffeur" <?php if ($fonction=="chauffeur"){echo "selected";} ?>>
								Chauffeur
							</option>
						</select>
					</div>
				</div>
				<div class="row">
					<div class="col s5 input-field">
						<input type="date" value="<?=$date_embauche?>" name="date_embauche" class="" id="date_embauche" required>
						<label for="date_embauche">Date d'embauche</label>
					</div>
					<div class="col s6 input-field">
						<select name="service" class="browser-default" required>
							<option value="" disabled="" >
								Veillez choisir le service
							</option>
							<option value="commerce" <?php if ($service=="commerce") {echo "selected";} ?>>
								Commerce
							</option>
							<option value="sante" <?php if ($service=="sante") {echo "selected";} ?>>
								Santé
							</option>
							<option value="immobilier" <?php if ($service=="immobilier") {echo "selected";} ?>>
								Immobilier
							</option>
							<option value="service general" <?php if ($service=="service general") {echo "selected";} ?>>Service Général
							</option>
						</select>
					</div>
				</div>
				<div class="row">
					<div class="col s5 input-field">
						<input type="text" value="<?=$login?>" name="login" id="login" required>
						<label for="login">Login</label>
					</div>
				</div>
				<div class="row" id="doc">
					<h4 class="center col s12">Pièces Jointes</h4>
					<?php
					$req_pj=$db->prepare('SELECT * FROM pj_personnel WHERE id_personnel=?');
					$req_pj->execute(array($_GET['id']));
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
					<div class="file-field input-field col s10">
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
					<div class="col s8 m8 l6">
						<a class='btn blue darken-4 tooltipped' data-position='top' data-delay='50' data-tooltip='Modifier mot de passe' href='password_sante.php?id=<?=$_GET["id"]?>'>Modifier le mot de passe</a>
					</div>
					<div class="col s2  input-field">
						<?php
							if ($etat=="desactiver")
							{
						?>
						<a href="desactiver_personnel.php?id=<?= $id ?>&amp;etat=<?= $etat ?>" class="btn blue">Activer</a>
						<?php
						
						}
						else
						{
						?>
						<a href="desactiver_personnel.php?id=<?= $id ?>&amp;etat=<?= $etat ?>" class="btn red">Déscativer</a>
						<?php
						
						}
						?>
						
					</div>
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
		$('select').formSelect();
		$('#form').submit(function () {
			if (!confirm('Voulez-vous confirmer l\'enregistrement ?')) {
				return false;
			}
		});
	});
	
</script>
</html>