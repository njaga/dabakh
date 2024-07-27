<?php
$formatter = \NumberFormatter::create('fr_FR', \NumberFormatter::SPELLOUT);
$formatter->setAttribute(\NumberFormatter::FRACTION_DIGITS, 0);
$formatter->setAttribute(\NumberFormatter::ROUNDING_MODE, \NumberFormatter::ROUND_HALFUP);
include 'connexion.php';
	$db->query("SET lc_time_names='fr_FR';");
	$req=$db->prepare("SELECT CONCAT(DATE_FORMAT(vente.date_vente, '%d'), '/', DATE_FORMAT(vente.date_vente, '%m'), '/', DATE_FORMAT(vente.date_vente, '%Y')),
    CONCAT(client.prenom,' ', client.nom), vente.montant, vente.id_user, vente.frais_transport
    FROM `vente` 
    INNER JOIN client ON client.id=vente.id_client
    WHERE vente.id=?");
	$req->execute(array($_GET['id']));
	$donnees=$req->fetch();
    $date_vente=$donnees['0'];
    $client=$donnees['1'];
    $montant=$donnees['2'];
    $id_user=$donnees['3'];
    $frais_transport=$donnees['4'];
	$date_facture=date("d/m/y");

	$req_article=$db->prepare("SELECT article.article, vente_article.nbr_article,
    vente_article.montant
    FROM `vente` 
    INNER JOIN vente_article ON vente_article.id_vente=vente.id
    INNER JOIN article ON article.id=vente_article.id_article
    WHERE vente.id=?");
	$req_article->execute(array($_GET['id']));

?>
<!DOCTYPE html>
<html>
	<head>
		<title>Facture</title>
		<?php include 'entete.php';?>
	</head>
	<body style="background-image: url('<?=$image ?>e_o.jpg'); font: 12pt 'times new roman';">
		<a href="" class="btn" onclick="window.print();">Imprimer</a>
		<?php
			if(isset($_GET['p']))
			{
				?>
				<a class="btn" href="l_vente.php">Retour</a>
				<?php
			}
			else
			{
				?>
				<a class="btn" onclick="window.close();">Fermer</a>
				<?php
			}
		?>
		<div class="container  white" style="padding:  10px">
			<div class="row center"  >
				<img class="col s8 offset-s2" style="margin-bottom: -30px" src="../css/images/entete_cm.jpg" >
			</div>	
            <br>		
			<h6 class="center"><b  style="font: 22pt 'times new roman';">FACTURE N° <?= str_pad($_GET['id'], 4, "0", STR_PAD_LEFT) ?></b></h6>
			<div class="row">
				<h6 class="col s7 offset-s2 " style="border: 2px solid black; border-radius: 10px">
					Client : <b><?= $client ?></b> <br>
					Date vente : <b><?=$date_vente ?></b> 
				</h6>
				<p class="right-align">Imprimer le <?=date('d')."/".date('m')."/".date('y') ?></p>
			</div>
			<table>
				<thead>
					<tr>
						<th><b>Article</b></th>
						<th><b>Nbr</b></th>
						<th><b>PU</b></th>
						<th><b>TOTAL</b></th>
					</tr>
				</thead>
				<tbody>
					<?php
					$total=0;
					while ($donnees=$req_article->fetch()) 
					{
                        $total=$total+$donnees['2'];
						echo "<tr>";
							echo "<td>".$donnees['0']."</td>";
							echo "<td>".str_pad($donnees['1'], 2, "0", STR_PAD_LEFT)."</td>";
							echo "<td>".number_format(($donnees['2']/$donnees['1']),0,'.',' ')."</td>";
							echo "<td>".number_format($donnees['2'],0,'.',' ')."</td>";
					    echo "</tr>";
					}
					if($frais_transport>0)
					{
						echo "<tr>";
							echo "<td colspan='3'><b>Frais transport<b></td>";
							echo "<td><b>".number_format(($frais_transport),0,'.',' ')." FCFA</b></td>";
					echo "</tr>";
					}
					echo "<tr>";
						echo "<td colspan='3'><b>Total<b></td>";
						echo "<td><b>".number_format(($total+$frais_transport),0,'.',' ')." FCFA</b></td>";
					echo "</tr>";
					
					?>
				</tbody>
			</table>
			<br>
			<div class="row">
				<div class="col s12" style="font: 18pt 'times new roman';">
						Arrété à la présente somme <b><i><?=$formatter->format($total); ?> Fcfa</i></b>
				</div>
				<br>
				<br>
				<br>
				<br>
				<br>
                <!--
				<div class="col s12 center" style="font: 16pt 'comic sans ms';">
					*** La propreté garantie ***<br>
					
				</div>
                -->

			</div>
            <!--
			<div class="row" style="border:1px solid;">
				<span style="font-size : 8px;">
				</span>
			</div>
            -->
		</div>
		<?php
			$req=$db->prepare("SELECT CONCAT(DATE_FORMAT(vente.date_vente, '%d'), '/', DATE_FORMAT(vente.date_vente, '%m'), '/', DATE_FORMAT(vente.date_vente, '%Y')),
			CONCAT(client.prenom,' ', client.nom), vente.montant, vente.id_user, vente.frais_transport
			FROM `vente` 
			INNER JOIN client ON client.id=vente.id_client
			WHERE vente.id=?");
			$req->execute(array($_GET['id']));
			$donnees=$req->fetch();
			$date_vente=$donnees['0'];
			$client=$donnees['1'];
			$montant=$donnees['2'];
			$id_user=$donnees['3'];
			$frais_transport=$donnees['4'];
			$date_facture=date("d/m/y");
		
			$req_article=$db->prepare("SELECT article.article, vente_article.nbr_article,
			vente_article.montant
			FROM `vente` 
			INNER JOIN vente_article ON vente_article.id_vente=vente.id
			INNER JOIN article ON article.id=vente_article.id_article
			WHERE vente.id=?");
			$req_article->execute(array($_GET['id']));
		?>

		<div class="container  white" style="padding:  10px">
			<div class="row center"  >
				<img class="col s8 offset-s2" style="margin-bottom: -30px" src="../css/images/entete_cm.jpg" >
			</div>	
            <br>		
			<h6 class="center"><b  style="font: 22pt 'times new roman';">FACTURE N° <?= str_pad($_GET['id'], 4, "0", STR_PAD_LEFT) ?></b></h6>
			<div class="row">
				<h6 class="col s7 offset-s2 " style="border: 2px solid black; border-radius: 10px">
					Client : <b><?= $client ?></b> <br>
					Date vente : <b><?=$date_vente ?></b> 
				</h6>
				<p class="right-align">Imprimer le <?=date('d')."/".date('m')."/".date('y') ?></p>
			</div>
			<table>
				<thead>
					<tr>
						<th><b>Article</b></th>
						<th><b>Nbr</b></th>
						<th><b>PU</b></th>
						<th><b>TOTAL</b></th>
					</tr>
				</thead>
				<tbody>
					<?php
					$total=0;
					while ($donnees=$req_article->fetch()) 
					{
                        $total=$total+$donnees['2'];
						echo "<tr>";
							echo "<td>".$donnees['0']."</td>";
							echo "<td>".str_pad($donnees['1'], 2, "0", STR_PAD_LEFT)."</td>";
							echo "<td>".number_format(($donnees['2']/$donnees['1']),0,'.',' ')."</td>";
							echo "<td>".number_format($donnees['2'],0,'.',' ')."</td>";
					    echo "</tr>";
					}
					if($frais_transport>0)
					{
						echo "<tr>";
							echo "<td colspan='3'><b>Frais transport<b></td>";
							echo "<td><b>".number_format(($frais_transport),0,'.',' ')." FCFA</b></td>";
					echo "</tr>";
					}
					echo "<tr>";
						echo "<td colspan='3'><b>Total<b></td>";
						echo "<td><b>".number_format(($total+$frais_transport),0,'.',' ')." FCFA</b></td>";
					echo "</tr>";
					
					?>
				</tbody>
			</table>
			<br>
			<div class="row">
				<div class="col s12" style="font: 18pt 'times new roman';">
						Arrété à la présente somme <b><i><?=$formatter->format($total); ?> Fcfa</i></b>
				</div>
				<br>
				<br>
				<br>
				<br>
				<br>
                <!--
				<div class="col s12 center" style="font: 16pt 'comic sans ms';">
					*** La propreté garantie ***<br>
					
				</div>
                -->

			</div>
            <!--
			<div class="row" style="border:1px solid;">
				<span style="font-size : 8px;">
				</span>
			</div>
            -->
		</div>
	</body>
	<style type="text/css">
		
		/*import du css de materialize*/
		@import "../css/materialize.min.css" print;
		/*CSS pour la page à imprimer */
		/*Dimension de la page*/
		@page
		{
			size: portrait;
			margin: 0px;
			margin-bottom: 10px;
			margin-top: 1px;
		}
		@media print
		{
			
			.btn{
				display: none;
			}
			
			p {
				margin-top : -5px;
			}
			.row h5{
				margin-top: -5px;
			}
			
		}
		td, th{
	    padding: initial;
	    border-right: 1px solid;
	    padding :2px;
	    }
	    th
	    {
	    border: 1px solid;
	    }
	    table
	    {
	    border: 1px solid;
	    }
		td{
			text-align: center;
			border:1px solid black;
		}
		
		th{
			text-align: center;
			border:1px solid black;
		}

			p{
				

				margin-top : -5px;
			}
			
	</style>
</html>