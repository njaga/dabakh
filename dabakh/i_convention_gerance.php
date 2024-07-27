<?php
$formatter = \NumberFormatter::create('fr_FR', \NumberFormatter::SPELLOUT);
$formatter->setAttribute(\NumberFormatter::FRACTION_DIGITS, 0);
$formatter->setAttribute(\NumberFormatter::ROUNDING_MODE, \NumberFormatter::ROUND_HALFUP);
include 'connexion.php';
$db->query("SET lc_time_names='fr_FR';");

$req = $db->prepare('SELECT * FROM `contrat` WHERE id=?');
$req->execute(array(1));
$donnees = $req->fetch();


$req = $db->prepare('SELECT CONCAT(bailleur.prenom," ",bailleur.nom),bailleur.cni, CONCAT(day(bailleur.date_debut)," ",monthname(bailleur.date_debut)," ",year(bailleur.date_debut)), CONCAT(day(now())," ",monthname(now())," ",year(now())), duree_contrat, tel , pourcentage, adresse
		FROM bailleur 
		WHERE bailleur.id=?');
$req->execute(array($_GET['id']));
$donnees = $req->fetch();
$bailleur = strtoupper($donnees['0']);
$cni = $donnees['1'];
$date_debut = $donnees['2'];
$date_actuelle = $donnees['3'];
$duree_contrat = $donnees['4'];
$tel = $donnees['5'];
$pourcentage = $donnees['6'];
$adresse = $donnees['7'];
$req->closeCursor();

$req = $db->prepare('SELECT CONCAT(bailleur.prenom," ", bailleur.nom), type_logement.type_logement, logement.designation, logement.adresse,(logement.nbr+ logement.nbr_occupe), logement.pu
						FROM bailleur, logement, type_logement
						WHERE bailleur.id=logement.id_bailleur AND logement.id_type=type_logement.id AND bailleur.id=? and logement.etat="actif"');
$req->execute(array($_GET['id']));
?>
<!DOCTYPE html>
<html>

<head>
	<title>Location du <?= $date_debut ?></title>
	<?php include 'entete.php'; ?>
</head>

<body style="background-image: url(<?= $image ?>bgaccueil.jpg);">
	<a href="" class="btn " onclick="window.print();">Imprimer</a>
	<a href="immobilier.php" class="btn">Retour au menu</a>


	<div class="container white">
		<div class="row center white" style="margin-bottom: 1px">
			<img class="col s8 offset-s2" src="css/images/banniere_immo.png">
		</div>
		<div class="row">
			<h5 class="col s8 center offset-s2" style="border:1px solid"><b>MANDAT DE GERANCE
				</b>
			</h5>
			<div class="col s12" style="font: 12pt 'times new roman';">
				<br>
				<br>
				Entre
			</div>
				<br>
				<br>
			<div class="col s12" style="font: 12pt 'times new roman';">
			<br>
				La Société Carré d'Or, <b>NINEA 010099275 / RCCM SN-DKR-2023-B-8664</b>, siège social Cité Keur Gorgui Immeuble Horizon 3ième étage, Dakar, Tel: +221338677733, email: contact@carredor.sn
				<br>
				<br>
				Et
			<br>
			<br>
			<div>
			<b>M/MME <?= $bailleur ?> </b> propiétaire demeurant <?= $adresse ?>. Titulaire de la Carte d'identité N°: <b><?= $cni ?></b>. Tél : <b><?= $tel ?></b>
			</div>
			<br>
			<b>Il a été convenu ce qui suit :</b>
			<br>
			<br>
			</div>

			<div class="col s12">
				<b>M/MME <?= $bailleur ?></b> propriétaire (gérant), donne par les présentes, mandat à <b> Carré d’Or </b>qui accepte de gérer et administrer l'immeuble dont il est propriétaire sis à 
				<?php
					echo ucfirst(strtolower($donnees['7'])) ;
				?>. Lequel immeuble est reçu par Carré d’Or dans son état actuel, tant dans son ensemble que dans les détails et dont toute erreur ou mal façon ne peuvent être prises en charge que par le propriétaire qui voit ainsi seule sa responsabilité engagée. 
				<br>
				<br>
				<b><u>DESCRIPTIF :</u></b>
				<br>
				Détails :
				<br>
				<?php
				while ($donnees = $req->fetch()) {
					echo "&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp - " . (str_pad($donnees['4'], 2)) . ucfirst(strtolower($donnees['1'])) . " : " . ucfirst(strtolower($donnees['2'])) ;
					echo "<br>";
				}
				?>
				<br>
				Le présent mandat sera régi par les dispositions des articles 457 à 472 du Code des Obligations Civiles et Commerciales du Sénégal - COCC. Ce mandat prend effet à compter du <b><?= $date_debut ?></b> pour une durée d’un (01) an renouvelable par tacite reconduction. 
				<br>
				<br>
 				Chacune des parties pourra y mettre fin en prévenant l'autre par lettre recommandée avec accusé de réception. La période de préavis est de six (06) mois à compter de la réception de la demande de résiliation. 
				<br>
				<br>
	 			Après résiliation, un état de solde de tout compte lui sera remis ; passé un délai d'un mois si aucune contestation ou réserve n'est émise, cet état sera considéré comme définitif sans aucune possibilité de recours. 
				<br>
				<br>
				En outre, le présent mandat est donné à Carré d’Or aux conditions suivantes :
				<br>
				<br>
				1.	Obligation de faire parvenir à <b> M/MME <?= $bailleur ?></b> Chaque mois le compte détaillé de la gestion durant les périodes occupées, ainsi que <b>les sommes encaissées</b> qui lui seront dues par chèque ou virement bancaire <b>chaque le 10</b>. 
				<br>
				<br>
				2.	Obligation de percevoir à titre d'honoraire le taux de 10% ainsi que 18% sur les dits honoraires étant déduits des sommes mensuelles encaissées pour le compte de <b> M/MME <?= $bailleur ?></b>
				<br>
				<br>
				3.	Obligation de ne pouvoir pour quelques motifs que ce soit, se substituer à une autre, personne, fut-ce un autre administrateur de biens, sans le consentement préalable et écrit de  
				<br>
				<br>
				Pour permettre à Carré d’Or de remplir la mission qui lui est confiée par les présentes. <b> M/MME <?= $bailleur ?></b>. Donne également pouvoir en son nom et pour son compte de : <br><br>

				-	Dresser tout état des lieux et d'exiger toutes réparations locatives <br>
				-	Percevoir des loyers <br>
				-	Exercer contre les défaillants toutes les actions judiciaires ou extra judiciaires appropriées, <br>
				-	Assigner le locataire, de défendre ou de faire défendre le mandant. <br>
				-	Faire exécuter les décisions rendues, d'exercer toutes voies de recours <br>
				-	Délivrer toutes quittances, décharges, mains levées et plus généralement, de faire le nécessaire au mieux des intérêts du mandant. <br><br>

				En cas de travaux de réfection incombant au propriétaire, ce dernier doit dans les normes et de bref délai les faire exécuter afin de permettre une bonne occupation par le locataire du bien mis en location. Le règlement du ou des fournisseurs peut directement être fait par ses soins (propriétaire) ou sur ses instructions par l'agence en compense de ses loyers dans la mesure des sommes disponibles à son crédit. 

				<br><br>
				En cas d'inexécution surtout pour les urgences (fuite - problème alimentation eau ou électricité) et que le locataire préfinance, le mandataire ne pourra s'opposer à la déduction des factures sur le montant du loyer. 
 
				<br><br>
				Pour ce qui est des travaux de maintenance et d'entretien surtout pour une nouvelle location ou une relocation, la diligence est obligatoire par le propriétaire. 
				Si les travaux doivent être pilotés par l'agence, le devis devra être soumis au préalable au propriétaire pour accord avant exécution.

				<br><br>
				La mise à location d'un patrimoine en bon état, facilite l'état des lieux d'entrée. S'il y a des réserves émises lors de l'état des lieux d'entrée, les travaux qui s'imposent doivent être faites afin de présenter une situation exhaustive et normale du bien mis en location. 
				
				<br><br>
				A la sortie du client, obligation sans conditions peut lui être imposée pour la remise en état dans le même état qu'au début de son occupation. 
				
				<br><br>
				En cas de non-paiement d’un locataire, le propriétaire paiera les frais d’huissier et d’avocat. 

				<br><br>
				<b>Clauses particulières </b>
				<br><br>
				Le mandataire ne peut, de quelque manière que ce soit, être tenu pour responsable ou co-responsable des dommages que le propriétaire ou ses préposés pourraient causer aux tiers soit du fait de sa responsabilité contractuelle, soit du fait de sa responsabilité délictuelle. <br>
				Le propriétaire se chargera de régler toutes ses taxes et impôts sur le revenu concernant ledit immeuble. Le mandataire gérant ne saurait répondre des jugements ou condamnations, de quelque nature que ce soit, à la place du propriétaire. 

				<br><br><br><br>




			<h6 class="col s12 right-align ">
				Fait le <b><?= $date_actuelle ?></b>
			</h6>
			<h6 class="col s6 left-align center"><b>Visas du propiétaire <br><br><br>Lu et approuvé</b></h6>
			<h6 class="col s6 right-align"><b>Visa du cabinet</b></h6>
		</div>

	</div>
</body>
<script type="text/javascript">
	$(document).ready(function() {})
</script>
<style type="text/css">
	/*import du css de materialize*/
	@import "css/materialize.min.css"print;

	/*CSS pour la page à imprimer */
	/*Dimension de la page*/
	@page {
		size: A4 portrait;
		margin-top: 50px;
		margin-bottom: 50px;

	}

	@media print {
		.btn {
			display: none;
		}

	}

	body {
		font-family: 'times new roman';
	}
</style>

</html>