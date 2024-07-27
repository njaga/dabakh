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
$req=$db->prepare("SELECT * FROM contrat WHERE id=?");
$req->execute(array($_GET['id']));
$nbr=$req->rowCount();
$donnees=$req->fetch();
$part1=$donnees['2'];
$part2=$donnees['3'];
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Modification d'un contrat</title>
        <?php include 'entete.php'; ?>
    </head>
    <body style="background-image: url(<?=$image ?>e_caisse_immo.jpeg) ;">
        <?php
        if ($_SESSION['service']=="immobilier")
        {
        include 'verification_menu_immo.php';
        }
        else
        {
        include 'verification_menu_sante.php';
        }
        ?>
        <div class="container white">
            <div class="row z-depth-5" style="padding: 10px;">
                <h4 class="center">Modification du contrat de location</h4>
                <form class="col s12" method="POST" id="form" action="contrat_location_trmnt.php">
                    <!--
                    <div class="row">
                        <div class="input-field col s10 offset-s1">
                            <textarea id="part1" name="part1" class="materialize-textarea" ><?=$part1 ?></textarea>
                            <label for="part1">Partie 1</label>
                        </div>
                    </div>
                    -->
                     <div class="row">
                        <div class="input-field col s10 offset-s1">
                            <textarea id="part2" name="part2" class="materialize-textarea" ><?=$part2 ?></textarea>
                            <label for="part2">Partie 2</label>
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
$('#form').submit(function() {
if (!confirm('Voulez-vous confirmer la modification de ce contact ?')) {
return false;
}
});
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