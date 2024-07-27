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

$annee= date('Y');
$dateFormat = date("Y-m-d");
$jour = array("Dimanche","Lundi","Mardi","Mercredi","Jeudi","Vendredi","Samedi");
$mois = array("","Janvier","Février","Mars","Avril","Mai","Juin","Juillet","Août","Septembre","Octobre","Novembre","Décembre");
$datefr = $mois[date("n")];
$req_annee=$db->query('SELECT DISTINCT YEAR(date_operation) FROM `caisse_caution` ORDER BY date_operation DESC');

?>
<!DOCTYPE html>
<html>

<head>
    <title>Etat caisse des dépôts</title>
   <?php include 'entete.php'; ?>
</head>
<body  id="debut" style="background-image: url(<?= $image ?>bgaccueil.jpg);">
    <?php
     include 'verification_menu_immo.php';
    ?>
     <div class="fixed-action-btn">
          <a class="btn-floating btn-large brown">
            <i class="large material-icons">import_export</i>
          </a>
          <ul>
            <li><a href="#debut" class="btn-floating green"><i class="material-icons">arrow_upward</i></a></li>
            <li><a href="#fin" class="btn-floating red darken-1"><i class="material-icons">arrow_downward</i></a></li>
          </ul>
        </div>
    <div class="row">
        <br>
        <div class="col s12 m12   ">
            <div class="row">
                <select class="browser-default col s3 m2" name="annee">
                    <option value="" disabled>--Choisir Annee--</option>
                    <?php
                    while ($donnee=$req_annee->fetch())
                    {
                        echo '<option value="'. $donnee['0'] .'"';
                        if ($annee==$donnee['0']) {
                            echo "selected";
                        }
                        echo ">"; 
                        echo $donnee['0'] .'</option>';
                    }
                    ?>
                </select>
                <a href="e_caisse_depot_locataire.php?p=d" class="btn col s4 offset-s1 ">Nouvelle opération</a>

                <a onclick="window.print()" href="" class="btn col s2 offset-s7">Imprimer</a>
            </div>
            <table class="bordered highlight centered">
                <thead>
                    <tr>
                        <th colspan="5">
                            <h4 class="center" style="color: #0d47a1">Journal de la caisse des dépots de garantie du mois de</h4>
                            <h5 class="row">
                                <select class="browser-default mois col s4 m2 offset-m5" name="mois" style="background-color: transparent;">
                                    <?php
									for ($i=1; $i <= 12; $i++) {
										echo "<option value='$i'";
													if ($mois[$i]==$datefr) {
														echo "selected";
													}
										echo">$mois[$i]</option>";
									}
									?>
                                </select>
                            </h5>
                        </th>
                    </tr>
                    <tr style="color: #fff; background-color: #bd8a3e">
                        <th data-field="">Date</th>
                        <th data-field="">Libellé</th>
                        <th data-field="">Débit</th>
                        <th data-field="">Crédit</th>
                        <th data-field="">Solde</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
 <span id="fin"></span>
</body>
<style type="text/css">
    select {
        font-family: georgia;
    }

    th {
        font: 16pt georgia;
        font-weight: bold;
    }

    table {
        background: white;
        margin-bottom: 100px;
    }

</style>
<script type="text/javascript">
    $(document).ready(function() {
        function etat_caisse(mois, annee) {
            $.ajax({
                type: 'POST',
                url: 'etat_caisse_depot_ajax.php',
                data: 'mois=' + mois + '&annee=' + annee,
                success: function(html) {
                    $('tbody').html(html);
                }
            });
        }

        var mois = $('select:eq(1)').val();
        var annee = $('select:eq(0)').val();
        etat_caisse(mois, annee);

        $('select').change(function() {
            var mois = $('select:eq(1)').val();
            var annee = $('select:eq(0)').val();
         etat_caisse(mois, annee);
           
        });
        $('.tooltipped').tooltip();
        $('.fixed-action-btn').floatingActionButton();
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

        select {
            border-color: transparent
        }
    }

</style>

</html>
