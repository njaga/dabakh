<?php
session_start();

$annee= date('Y');
$dateFormat = date("Y-m-d");
$jour = array("Dimanche","Lundi","Mardi","Mercredi","Jeudi","Vendredi","Samedi");
$mois = array("","Janvier","Février","Mars","Avril","Mai","Juin","Juillet","Août","Septembre","Octobre","Novembre","Décembre");
if(date("n")==12){
        $datefr=1;
        $annee=$annee+1;
 }
else{
$datefr = $mois[date("n")];
  }
?>
<!DOCTYPE html>
<html>

<head>
    <title>Etat de la caisse</title>
   <?php include 'entete.php'; ?>
</head>

<body style="background-image: url(../css/images/polygon.jpg);">
    <?php
		include 'verification_menu_sante.php';
		?>
    <div class="row">

        <div class="col s12   ">
            <div class="row l_d">
                <br>
                <select class="browser-default col s2" name="annee">
                    <option value="" disabled>--Choisir Annee--</option>
                    <?php
                echo '<option value="2018">'. 2018 .'</option>';
                echo '<option value="2019" selected>'. 2019 .'</option>';
               ?>
                </select>
                <select class="browser-default col s2 offset-s1" name="mois" >
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
            </div>
            <table class="bordered highlight centered">
                <thead>
                    <tr>
                        <th colspan="5">
                            <h4 class="center" style="color: #0d47a1">Journal de la caisse du</h4>
                            <h5 class="row">
                                    <div class="col s4 offset-s4 input-field">
                                        <input type="date" class="datepicke" value="<?= date('Y').'-'.date('m').'-'.date('d') ?>" name="date_pointage" id="date_pointage" required>
                                        <label for="date_pointage"></label>
                                    </div>
                            </h5>
                        </th>
                    </tr>
                    <tr style="color: #fff; background-color: #bd8a3e">
                        <th data-field="" class="trait">Date</th>
                        <th data-field="" class="trait">Libellé</th>
                        <th data-field="" class="trait">Entrée</th>
                        <th data-field="" class="trait">Sortie</th>
                        <th data-field="" class="trait">Solde</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
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
    $('.datepicker').datepicker({
			autoClose: true,
			yearRange:[2017,<?=(date('Y')+1) ?>],
			showClearBtn: true,
			i18n:{
				nextMonth: 'Mois suivant',
				previousMonth: 'Mois précédent',
				labelMonthSelect: 'Selectionner le mois',
				labelYearSelect: 'Selectionner une année',
				months: [ 'Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre' ],
				monthsShort: [ 'Jan', 'Fev', 'Mar', 'Avr', 'Mai', 'Jun', 'Jul', 'Aou', 'Sep', 'Oct', 'Nov', 'Dec' ],
				weekdays: [ 'Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi' ],
				weekdaysShort: [ 'Dim', 'Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam' ],
				weekdaysAbbrev: [ 'D', 'L', 'M', 'M', 'J', 'V', 'S' ],
				today: 'Aujourd\'hui',
				clear: 'Réinitialiser',
				cancel: 'Annuler',
				done: 'OK'
					
				},
				format: 'dd-mmmm-yyyy'
			});
    $(document).ready(function() {
        function caisse_journalier()
        {
            var mois = $('select:eq(1)').val();
            var annee = $('select:eq(0)').val();            
            var jour = $('.datepicke').val();
            $.ajax({
                type: 'POST',
                url: 'etat_caisse_journalier_ajax.php',
                data: 'mois=' + mois + '&annee=' + annee + '&jour=' + jour,
                success: function(html) {
                    $('tbody').html(html);
                }
            });
        }
        caisse_journalier();
        $('select').change(function() {
            caisse_journalier();
        });
        $('.datepicke').change(function() {
            caisse_journalier();
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
        margin: 0px;
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

        .l_d {
            display: none
        }
        .trait
        {
            border: 1px solid;
        }
        img{
            width: 140px;
            height: 140px;
        }
        .datepicke{
            border-color: transparent;
        }
    }

</style>

</html>
