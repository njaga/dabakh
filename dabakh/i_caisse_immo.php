<!DOCTYPE html>
<html>
<head>
	<title></title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link type="text/css" rel="stylesheet" href="../css/pure.css"  />
</head>
<body>
	<?php		
session_start();			
include 'connexion.php';
$mois=10;
$annee=2019;
$search="d";
//$db->query("SET lc_time_names = 'fr_FR';");
?>
<div class="pure-g">
	<div class="pure-u-1-4"></div>
	<div class="pure-u-1-2">
		<img style="" src="../css/images/banniere_immo.png" width="100%" >
	</div>
	<div class="pure-u-1-4"></div>
</div>
<div class="pure-g">
	<div class="pure-u-1-4"></div>
	<div class="pure-u-1-2" style="font-family: 'times new roman'; font-size: 21pt; font-weight: bold;">
		Grand livre caisse du mois de
	</div>
	<div class="pure-u-1-4"></div>
</div>
<table class="pure-table pure-table-bordered">
	<thead>
		<tr style="color: #fff; background-color: #bd8a3e">
            <th data-field="">Date</th>
            <th data-field="">Libellé</th>
            <th data-field="" class="compte">Débit</th>
            <th data-field="" class="compte">Crédit</th>
            <th data-field="" class="compte">Solde</th>
        </tr>
        <tbody>
			<?php
				$reponse=$db->prepare("SELECT id, CONCAT(DATE_FORMAT(date_operation, '%d'), '/', DATE_FORMAT(date_operation, '%m'),'/', DATE_FORMAT(date_operation, '%Y')), motif, type, montant, id_mensualite,section, id_mensualite_bailleur, id_depense_bailleur, id_user, id_depense_locataire
				FROM `caisse_immo`
				WHERE month(date_operation)=? AND year(date_operation)=? ORDER BY date_operation, id ASC");
				$reponse->execute(array($mois, $annee));
				$nbr=$reponse->rowCount();
				if ($nbr>0) 
				{
					$solde=0;
					while ($donnees= $reponse->fetch())
					{
						$id=$donnees['0'];
						$date_operation=$donnees['1'];
						$motif=$donnees['2'];
				        $type=$donnees['3'];
						$montant=$donnees['4'];
						$id_mensualite=$donnees['5'];
						$section=$donnees['6'];
						$id_mensualite_bailleur=$donnees['7'];
						$id_depense_bailleur=$donnees['8'];
						$id_user=$donnees['9'];
						$id_depense_locataire=$donnees['10'];
					
						echo "<tr>";					
						echo "<td>".$date_operation. "</td>";
						echo "<td>".$motif."</td>";
						if ($type=="entree") {
							$solde=$solde+$montant;
						echo "<td>".number_format($montant,0,'.',' ')."</td>";
						echo "<td></td>";
						}
						elseif ($type=='sortie') 
						{
							$solde=$solde-$montant;
							echo "<td></td>";	
							echo "<td>".number_format($montant,0,'.',' ')."</td>";
						}
						else
						{
							$solde=$solde+$montant;
							echo "<td></td>";	
							echo "<td></td>";	
						}
							echo "<td>".number_format($solde,0,'.',' ')."</td>";
					

					}
					$reponse->closeCursor();
					//Total et solde
					echo "</tr>";
					$req=$db->prepare("SELECT SUM(montant) 
						FROM `caisse_immo` WHERE type='entree' AND month(date_operation)=?");
					$req->execute(array($mois));
					$donnees= $req->fetch();
					$som_entree=$donnees['0'];
					$req->closeCursor();
					$req=$db->prepare('SELECT SUM(montant) 
						FROM `caisse_immo` WHERE type="sortie" AND month(date_operation)=?');
					$req->execute(array($mois));
					$donnees=$req->fetch();
					$som_sortie=$donnees['0'];
					$req->closeCursor();
					$req=$db->prepare('SELECT SUM(montant) 
						FROM `caisse_immo` WHERE type="solde" AND month(date_operation)=?');
					$req->execute(array($mois));
					$donnees=$req->fetch();
					$som_solde=$donnees['0'];
					$req->closeCursor();
					echo "<tr class='grey darken-3 white-text'>";
					echo "<td colspan='2'><b>TOTAL</b></td>";
					echo "<td><b>".number_format($som_entree,0,'.',' ')." </b></td>";
					echo "<td><b>".number_format($som_sortie,0,'.',' ')." </b></td>";
					echo "<td><b>".number_format(($som_solde+$som_entree-$som_sortie),0,'.',' ')." </b></td>";
					echo "</tr>";	
				}
				else
				{
					echo "<tr><td></td><td></td><td></td><td><h3>Aucune opération ce mois ci </td></tr>";
				}			
			?>
        	
        </tbody>
	</thead>
</table>
<script type="text/javascript">
	$('.tooltipped').tooltip();
</script>
<style type="text/css">
	.compte{
		width: 60px;
		text-align: center;
	}


	 /*Dimension de la page*/
	
    @page {
        size: portrait;
        margin: 15px;
        margin-top: 25px;
    }

    @media print {
    	.compte{
    		border: 1px solid;
		width: 72px;
		text-align: center;
	}
        .btn, .input-field {
            display: none;
        }


        nav {
            display: none;
        }

       

        select {
            border-color: transparent
        }
        th{
        	font-size: 40px;
        	font-family:"Comic Sans MS"; 
        	font-style: bold;
        }
    }
	
</style>
</body>
</html>