<?php
$dateFormat = date("Y-m-d");
$jour = array("Dimanche","Lundi","Mardi","Mercredi","Jeudi","Vendredi","Samedi");
$mois = array("","Janvier","Février","Mars","Avril","Mai","Juin","Juillet","Août","Septembre","Octobre","Novembre","Décembre");
$datefr = $mois[date("n")];
$mois1='Juillet';
$annee=2019;
include 'connexion.php';
$req=$db->query("SELECT produit.produit, COUNT(vente_produit.quantite) 
FROM `vente_produit`
INNER JOIN produit ON vente_produit.id_produit=produit.id
INNER JOIN consultation ON consultation.id_consultation=vente_produit.id_consultation
WHERE YEAR(consultation.date_consultation)=2020
GROUP BY produit.id");
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Liste mensualités</title>
		<meta charset="utf-8">
		<!--Import materialize.min.css-->
		<link type="text/css" rel="stylesheet" href="../css/materialize.min.css"  media="screen,projection"/>
		<link type="text/css" rel="stylesheet" href="../css/icones.css"  media="screen,projection"/>
		<link type="text/css" rel="stylesheet" href="../css/formulaire.css"  media="screen,projection"/>
		<!--Let browser know website is optimized for mobile-->
		<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
		<!--Import jQuery before materialize.js-->
		<script type="text/javascript" src="../js/jquery.min.js"></script>
		<script type="text/javascript" src="../js/materialize.min.js"></script>
	</head>
	<body style="background-image: url(../css/images/l_mensualite.jpg);">
		<?php
		include 'verification_menu_immo.php';
		?>
		
		
	</body>
	<style type="text/css">
		body
		{
			background-position: center center;
			background-repeat:  no-repeat;
			background-attachment: fixed;
			background-size:  cover;
			background-color: #999;
		}
		.table
		{
			background: white;
		}
	</style>
	<script type="text/javascript">
		$(document).ready(function () {
			window.location="l_location.php";
		}
	</script>
	<style type="text/css">

		/*import du css de materialize*/
		@import "../css/materialize.min.css" print;
		/*CSS pour la page à imprimer */
		/*Dimension de la page*/
		@page
		{
			size: landscape;
			margin: 0;
			margin-top: 25px;
		}
		@media print
		{
			.btn,.img{
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