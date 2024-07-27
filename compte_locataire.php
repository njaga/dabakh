<?php
session_start();
include'connexion.php';
$req=$db->prepare("SELECT prenom, nom FROM locataire WHERE id=?");
$req->execute(array($_GET['s']));
$donnees=$req->fetch();
$locataire=$donnees['0']." ".$donnees['1'];
$som_entree=0;
$som_sortie=0;

$req_caution=$db->prepare("SELECT caisse_caution.id, CONCAT(DATE_FORMAT(date_operation, '%d'), '/', DATE_FORMAT(date_operation, '%m'),'/', DATE_FORMAT(date_operation, '%Y')), motif, type, montant, id_location,section, caisse_caution.id_user
FROM `caisse_caution`, location, locataire
WHERE caisse_caution.id_location=location.id AND location.id_locataire=locataire.id AND locataire.id=? ORDER BY date_operation ASC");
$req_caution->execute(array($_GET['s']));


?>
<!DOCTYPE html>
<html>
<head>
	<title>Compte du locataire</title>
	<?php include 'entete.php';  ?>
</head>
<body style="background-image: url('<?=$image ?>infos_mens_loc.png');">
	<?php include 'verification_menu_immo.php';?>
	<div class="container white">
		<div class="row">
			<h5 class="col s12 center">Compte du locataire :<b><?=$locataire ?></b> </h5>
		</div>
		<table class="col s6">
		    <thead>
		        <tr>
		            <th >Date</th>
                    <th >Libellé</th>
                    <th  >Débit</th>
                    <th  >Crédit</th>
                    <th  >Solde</th>
		        </tr>
		    </thead>
		    <tbody>
		    	<?php
		    		$solde=0;
		    		while ($donnees_caution=$req_caution->fetch()) 
		    		{
						$id=$donnees_caution['0'];
						$date_operation=$donnees_caution['1'];
						$motif=$donnees_caution['2'];
						$type=$donnees_caution['3'];
						$montant=$donnees_caution['4'];
						$id_location=$donnees_caution['5'];
						$section=$donnees_caution['6'];
						$id_user=$donnees_caution['7'];
						echo "<tr>";
						echo "<td>". $date_operation. "</td>";
						echo "<td>".$motif."</td>";
						if ($type=="entree") {
							$solde=$solde+$montant;
							$som_entree=$som_entree+$montant;
						echo "<td>".number_format($montant,0,'.',' ')."</td>";
						echo "<td></td>";
						}
						elseif ($type=='sortie') 
						{
							$solde=$solde-$montant;
							$som_sortie=$som_sortie+$montant;
							echo "<td></td>";	
							echo "<td>".number_format($montant,0,'.',' ')."</td>";
						}
						else
						{
							$solde=$solde+$montant;
							echo "<td></td>";	
							echo "<td></td>";	
						}
							echo "<td>".number_format($solde,0,'.',' ')." </td>";
						echo "</tr>";
		    		}
		    	?>
		    </tbody>
		</table>
	</div>

</body>
<style type="text/css">
    /*import du css de materialize*/
    @import "../css/materialize.min.css"print;
    /*CSS pour la page à imprimer */
    /*Dimension de la page*/
    @page {
    size: portrait;
    margin: 15px;
    margin-top: 25px;
    }
    .compte{
    width: 90px;
    text-align: center;
    }
    .date{
    width: 70px;
    }
    
    @media print {
    .btn {
    display: none;
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
    nav {
    display: none;
    }
    div {
    font: 12pt "times new roman";
        
    }
  
    .page{
    padding: -20px;
    }
    img{
        width: 50%;
    }
    .datepicke{
    border-color: transparent;
    }
    table{
      margin-top: -35px;  
    }
    }
    </style>
</html>