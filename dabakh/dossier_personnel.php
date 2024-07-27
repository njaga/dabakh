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
$dateFormat = date("Y-m-d");
$jour = array("Dimanche","Lundi","Mardi","Mercredi","Jeudi","Vendredi","Samedi");
$mois = array("","Janvier","Février","Mars","Avril","Mai","Juin","Juillet","Août","Septembre","Octobre","Novembre","Décembre");
$datefr = $mois[date("n")];
$annee = date('Y');
$db->query("SET lc_time_names = 'fr_FR';");
$reponse=$db->prepare("SELECT id,prenom, nom, sexe, fonction, telephone, CONCAT(DATE_FORMAT(date_embauche, '%d'), '/', DATE_FORMAT(date_embauche, '%m'),'/', DATE_FORMAT(date_embauche, '%Y')), login, service, matricule FROM personnel WHERE id=?");
$reponse->execute(array($_GET['id']));
$donnees=$reponse->fetch();
$id=$donnees['0'];
$prenom=$donnees['1'];
$nom=$donnees['2'];
$sexe=$donnees['3'];
$fonction=$donnees['4'];
$telephone=$donnees['5'];
$date_embauche=$donnees['6'];
$login=$donnees['7'];
$service=$donnees['8'];
$matricule=$donnees['9'];
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Dossier personnel</title>
		<?php include 'entete.php'; ?>
	</head>
	<body style="background-image: url(<?= $image ?>bgaccueil.jpg);">
		<a href="" class="btn" onclick="window.close();">Fermer</a>
		<div class="container white" style="border-radius: 15px;">
			<div class="row center"  >
				<img class="col s8 offset-s2" src="../css/images/entete.jpg" >
			</div>
			<div class="row" style="padding: 5px; ">
				<h3 class="col s12 center"><u>Dossier personnel <br>MATRICULE : <b>N°<?=$matricule ?></b></u></h3>
			</div>
			<div class="row " style="padding-bottom: 50px">
				<h5 class="col s6 m6 l6">
					Prenom : <b><?=$prenom?> </b><br>
					Nom : <b><?=$nom?> </b><br>
					Sexe : <b><?=$sexe?> </b>
				</h5>
				<h5 class="col s6 m6 l6">
					Fonction : <b><?=$fonction?> </b><br>
					Date embauche : <b><?=$date_embauche ?> </b><br>
					Téléphone : <b><?=$telephone ?> </b>
				</h5>
			</div>
			<div class="row">
				<h4 class="center col s12">Pièces Jointes</h4>
					<?php
					$req_pj=$db->prepare('SELECT * FROM pj_personnel WHERE id_personnel=?');
					$req_pj->execute(array($_GET['id']));
					while ($donnees_pj=$req_pj->fetch()) 
					{
						echo "<div class='row'>";
							echo "<a class='col s5' href='".$donnees_pj['2']."'>".$donnees_pj['1']."</a>";
							echo "&nbsp&nbsp&nbsp";
							if ($_SESSION['fonction']=='administrateur' )
      							{
							echo "<a class='col s2 red-text' href='s_pj_demande.php?s=".$donnees_pj['0']."' onclick='return(confirm(\"Voulez-vous supprimer cette pièce jointe ?\"))'>Supprimer</a>";
						}
							echo "<br>";
						echo "</div>";
					}
					?>
			</div>
			<div class="row">
				<h4 class="center col s12">Demandes de Permissions / Absences</h4>
						<div class="col s12">
							<?php
							$req_pj=$db->prepare('SELECT pj_demandes.id, pj_demandes.nom, pj_demandes.chemin
								FROM personnel,`demandes_p_a`, pj_demandes 
								WHERE personnel.id=demandes_p_a.id_personnel AND demandes_p_a.id=pj_demandes.id_demande AND personnel.id=?');
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
			</div>
		</body>
		<script type="text/javascript">
			$(document).ready(function (){
				function l_consultation()
				{
					var annee = $('select:eq(0)').val();
					var id=$('input:first').val();
					$.ajax({
					type:'POST',
					url:'dossier_p_ajax.php',
					data:'id='+id+'&annee='+annee,
					success:function (html) {
						$('.tableaux').html(html);
					}
				});
				}
				
				l_consultation();
				$('select').change(function(){
					l_consultation();
					});
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
				margin: 10px;
				margin: 5px;
			}
			@media print
			{
				.btn, a{
					display: none;
				}
				div
				{
				font: 12pt "times new roman";
				}
			}
			tr td {
				border: 1px solid;
				text-align: center;
				font: 14pt "georgia";
			}
		</style>
	</html>