<?php
$formatter = \NumberFormatter::create('fr_FR', \NumberFormatter::SPELLOUT);
$formatter->setAttribute(\NumberFormatter::FRACTION_DIGITS, 0);
$formatter->setAttribute(\NumberFormatter::ROUNDING_MODE, \NumberFormatter::ROUND_HALFUP);
include 'connexion.php';
	$db->query("SET lc_time_names='fr_FR';");
	$req=$db->prepare("SELECT CONCAT(locataire.prenom,' ', locataire.nom), CONCAT(bailleur.prenom, ' ', bailleur.nom), logement.designation, logement.adresse, location.caution, location.montant_mensuel, location.nbr_mois, location.commission, CONCAT(day(location.date_debut),' ', monthname(location.date_debut),' ', year(location.date_debut)), location.id
FROM bailleur, locataire, logement, location
WHERE bailleur.id=logement.id_bailleur AND logement.id=location.id_logement AND locataire.id=location.id_locataire AND location.id=?");
	$req->execute(array($_GET['id']));
	$donnees=$req->fetch();
	$locataire=$donnees['0'];
	$bailleur=$donnees['1'];
	$logement=$donnees['2'];
	$adresse=$donnees['3'];
	$caution=$donnees['4'];
	$mensualite=$donnees['5'];
	$nbr_mois=$donnees['6'];
	$commission=$donnees['7'];
	$date_debut=$donnees['8'];
	$num_dossier=$donnees['9'];
	
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Bail</title>
		<?php include 'entete.php'; ?>
	</head>
	<body style="background-color: grey">
		<a href="" class="btn "  onclick="window.print();">Imprimer</a>
		<a href="l_location.php" class="btn " >Retour au menu</a>
		<table style="border:0px solid">
			<thead >
				<tr >
					<div class="row center white" style="margin-bottom: 1px" >
						<img class="col s8 offset-s2" src="<?=$image ?>banniere_immo.png" >
					</div>
				</tr>
			</thead>
			<tbody>
				<div class="container white">
					<div class="row">
						<p class="right-align"><b>Fait à Dakar le <?php echo date('d')."/".date('m')."/".date('Y'); ?></b></p>
						<h3 class="center "><b>Avis de crédit</b></h3>
						<h5><b>Dossier N° : <?=str_pad($num_dossier, 3, "0", STR_PAD_LEFT) ?></b></h5>
						<h5 style="line-height: 180%">
							ARRETE AU ....<b><?=$date_debut ?></b>.....<br>
							AU PROFIT DE ........<b><?=$locataire ?></b>.........<br>
							POUR LA LOCATION DE : ........<b><?=$logement ?></b>....<br>
							PROPRIETAIRE : ........<b><?=$bailleur ?></b>......<br>
							CAUTION : ........<b><?=$caution ?> Fcfa</b>...<br>
							REDEVENCE MENSUEL : ........<b><?=$mensualite ?> Fcfa</b>......<br>
							COMMISSION : ........<b><?=(($mensualite*$commission)/100) ?> Fcfa</b>......<br>

						</h5>
						<h5 class="col s12 right-align" ><u><b>Visa du cabinet</b></u></h5>
					</div>
				</div>
			</tbody>
		</table>
	</body>
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
			.btn{
				display: none;
			}
			
		}
	</style>
</html>