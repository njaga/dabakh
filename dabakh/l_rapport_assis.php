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
?>
<!DOCTYPE html>
<html>

<head>
    <title>Consultaions du mois</title>
   <?php include 'entete.php'; ?>
</head>

<body style="background-image: url(<?= $image ?>bgaccueil.jpg);">
    <?php
       include 'verification_menu_sante.php';              
    ?>
        <div class="row ">
        	<br>
            <select class="browser-default col s2" name="annee">
                <option value="" disabled>--Choisir Annee--</option>
	                <?php
                        $req=$db->query("SELECT DISTINCT year(date_rapport) FROM rapport_consultation_domicile");
                       while ($donnee=$req->fetch())
                        {
                            echo '<option value="'. $donnee['0'] .'">'. $donnee['0'] .'</option>';
                        }
		            ?>
            </select>
        </div>
        <div class="row white">
            <h4 class="center col s12" style="color: #0d47a1">Consultaions du mois de</h4>
            <h5><select class="browser-default col s2 offset-s5" style="background-color: transparent;" name="mois" class="mois" >
                    <?php
					for ($i=1; $i <= 12; $i++) {
						echo "<option value='$i'";
									if ($mois[$i]==$datefr) {
										echo "selected";
									}
						echo">$mois[$i]</option>";
					}
					?>
                </select></h5>
            <table class="bordered highlight centered col s12">
                <thead>
                    <tr>
                        <th colspan="5">
                        </th>
                    </tr>
                    <tr style="color: #0d47a1;">
                       
                        <th>Date rapport</th>
						<th>Patient</th>
                        <th>Date consultation</th>
						<th>Infirmier</th>
					</tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>

</body>
<style type="text/css">
    select {
        font-family: georgia;
    }

    th {
        font: 16pt georgia;
        font-weight: bold;
    }

    body {
        background-position: center center;
        background-repeat: no-repeat;
        background-attachment: fixed;
        background-size: cover;
        background-color: #999;
    }

    table {
        background: white;
    }

</style>
<script type="text/javascript">
    $(document).ready(function() {
        function rapport_assis() {
             var mois = $('select:eq(1)').val();
        	 var annee = $('select:eq(0)').val();
            $.ajax({
                type: 'POST',
                url: 'l_rapport_assis_ajax.php',
                data: 'mois=' + mois + '&annee=' + annee,
                success: function(html) {
                    $('tbody').html(html);
                }
            });
        }

        rapport_assis();
        $('select').change(function() {
         rapport_assis();
        });

        $('.tooltipped').tooltip();
    });

</script>
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

    @media print {
        .btn {
            display: none;
        }

        nav {
            display: none;
        }

        div {
            font: 12pt "times new roman";
        }

        select {
            border-color: transparent
        }
    }

</style>

</html>
