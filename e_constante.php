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
$req=$db->prepare('SELECT * FROM `patient` WHERE id_patient=?');
$req->execute(array($_GET['id']));
$donnees=$req->fetch();

?>
<!DOCTYPE html>
<html>

<head>
    <title>Enregistrement d'une constante</title>
    <?php include 'entete.php'; ?>
</head>
<style type="text/css">
            body {
            
            background-position: center center;
            background-repeat:  no-repeat;
            background-attachment: fixed;
            background-size:  cover;
            background-color: #999;
        
        }
        </style>
<body style="background-image: url(<?= $image ?>bgaccueil.jpg);">
    <?php
    include 'verification_menu_sante.php';         
    ?>
    <div class="container white">

        <div class="row z-depth-5" style="padding: 10px;">
            <h3 class="center">Enregistrement d'une constante</h3>
            <form class="col s12" method="POST" id="form" action="e_constante_trmnt.php?id=<?=$_GET['id'] ?>">
                <div class="row">
                    <h4>Patient : <b><?=$donnees['1']." ".$donnees['2'] ?></b></h4>
                </div>
                 <div class="row">
                    <div class="col s2 m4 input-field">
                        <input type="date" class="" name="date_prise" id="date_prise" required>
                        <label for="date_prise">Date de la prise de constante</label>
                    </div>
                    <div class="input-field col s2 m2">
                        <input type="time" name="heure_prise" id="heure_prise">
                        <label for="heure_prise">Heure</label>
                    </div>
                </div>  
                <div class="row">
                    <div class="col s4 m2 input-field">
                        <input type="text" name="pouls" id="pouls">
                        <label for="pouls">Pouls</label>
                    </div>
                    <div class="col s4 m2 input-field">
                        <input type="text" name="temperature" id="temperature">
                        <label for="temperature">Température</label>
                    </div>
                    <div class="col s4 m2 input-field">
                        <input type="text" name="tension" id="tension">
                        <label for="tension">Tension</label>
                    </div>
                    <div class="col s4 m2 input-field">
                        <input type="text" name="dextro" id="dextro">
                        <label for="dextro">Dextro</label>
                    </div>
                    <div class="col s4 m2 input-field">
                        <input type="text" name="spo2" id="spo2">
                        <label for="spo2">%SpO<sub>2</sub> </label>
                    </div>
                </div>
                <div class="row">
                    <div class="col s12 m4 input-field">
                        <textarea class="materialize-textarea"  id="conscience" name="conscience"></textarea>
                        <label for="conscience">Conscience</label>
                    </div>
                    <div class="col s12 m4 input-field">
                        <textarea class="materialize-textarea" id="vomissement" name="vomissement"></textarea>
                        <label for="vomissement">Vomissement</label>
                    </div>
                    <div class="col s12 m4 input-field">
                        <textarea class="materialize-textarea" id="diarrhee" name="diarrhee"></textarea>
                        <label for="diarrhee">Diarrhée</label>
                    </div>
                </div>
               
                <div class="row">
                    <div class="col s2 offset-s8 input-field">
                        <input class="btn" type="submit" name="enregistrer" value="Sauvegarder">
                    </div>
                </div>
        </div>
        </form>
    </div>
    </div>
</body>
<script type="text/javascript">
    $(document).ready(function() {
        $('select').formSelect();
        $('.datepicker').datepicker({
            autoClose: true,
            yearRange: [2017, 2022],
            showClearBtn: true,
            i18n: {
                nextMonth: 'Mois suivant',
                previousMonth: 'Mois précédent',
                labelMonthSelect: 'Selectionner le mois',
                labelYearSelect: 'Selectionner une année',
                months: ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'],
                monthsShort: ['Jan', 'Fev', 'Mar', 'Avr', 'Mai', 'Jun', 'Jul', 'Aou', 'Sep', 'Oct', 'Nov', 'Dec'],
                weekdays: ['Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'],
                weekdaysShort: ['Dim', 'Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam'],
                weekdaysAbbrev: ['D', 'L', 'M', 'M', 'J', 'V', 'S'],
                today: 'Aujourd\'hui',
                clear: 'Réinitialiser',
                cancel: 'Annuler',
                done: 'OK'

            },
            format: 'yyyy-mm-dd'
        });
    });
</script>

</html>
