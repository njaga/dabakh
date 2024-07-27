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
$annee = date('Y');
$dateFormat = date("Y-m-d");
$jour = array("Dimanche", "Lundi", "Mardi", "Mercredi", "Jeudi", "Vendredi", "Samedi");
$mois = array("", "Janvier", "Février", "Mars", "Avril", "Mai", "Juin", "Juillet", "Août", "Septembre", "Octobre", "Novembre", "Décembre");
$datefr = $mois[date("n")];
$req_annee = $db->query('SELECT DISTINCT YEAR(date_consultation) FROM `consultation_domicile` ORDER BY date_consultation DESC');
?>
<!DOCTYPE html>
<html>

<head>
    <title>Soins à domicile du mois</title>
    <?php include 'entete.php'; ?>
</head>

<body style="background-image: url(<?= $image ?>l_consultation_d.jpg);">
    <?php
    include 'verification_menu_sante.php';
    ?>
    <br>
    <div class="row">
        <?php
        if ($_SESSION['fonction'] != 'secretaire') {
        ?>
            <a class="col s4 btn" href="l_patient_cons_d.php?p=sd">Ajouter+</a>
        <?php
        }
        ?>
    </div>
    <div class="row ">
        <br>
        <select class="browser-default col s3 m2" name="annee">
            <option value="" disabled>--Choisir Annee--</option>
            <?php
            while ($donnee = $req_annee->fetch()) {
                echo '<option value="' . $donnee['0'] . '"';
                if ($annee == $donnee['0']) {
                    echo "selected";
                }
                echo ">";
                echo $donnee['0'] . '</option>';
            }
            ?>
        </select>
    </div>
    <div class="row white">
        <h4 class="center col s12" style="color: #0d47a1">Soins à domicile du mois de</h4>
        <h5><select class="browser-default col s3 m2 offset-m5" style="background-color: transparent;" name="mois" class="mois">
                <?php
                for ($i = 1; $i <= 12; $i++) {
                    echo "<option value='$i'";
                    if ($mois[$i] == $datefr) {
                        echo "selected";
                    }
                    echo ">$mois[$i]</option>";
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
                    <th></th>
                    <th>Date</th>
                    <th>Patient</th>
                    <th>Date et lieu de naissance</th>
                    <?php
                    if ($_SESSION['fonction'] == 'daf' or $_SESSION['fonction'] == 'administrateur') {

                        echo "<th>Montant</th>
                                <th>Reglement</th>";
                    }
                    ?>
                    <?php
                    if ($_SESSION['fonction'] == "administrateur" or $_SESSION['fonction'] == "medecin" or $_SESSION['fonction'] == 'daf') {
                    ?>
                        <th>Notation</th>
                        <th>Suggestions/<br>Remarques</th>
                    <?php
                    }
                    ?>
                    <th></th>
                    <th></th>
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
        function etat_caisse() {
            var mois = $('select:eq(1)').val();
            var annee = $('select:eq(0)').val();
            $.ajax({
                type: 'POST',
                url: 'l_consultation_d_ajax.php',
                data: 'mois=' + mois + '&annee=' + annee,
                success: function(html) {
                    $('tbody').html(html);
                }
            });
        }
        etat_caisse();
        $('select').change(function() {
            etat_caisse();
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