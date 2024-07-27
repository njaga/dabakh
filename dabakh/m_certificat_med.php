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
$req=$db->prepare('SELECT CONCAT(patient.prenom," ", patient.nom), certificat_medical.date_certificat, certificat_medical.date_debut, certificat_medical.nbr_jours, certificat_medical.motif
FROM certificat_medical
INNER JOIN patient ON patient.id_patient=certificat_medical.id_patient 
WHERE certificat_medical.id=?');
$req->execute(array($_GET['id'])) or die(print_r($req->errorInfo()));
$donnees=$req->fetch();
$patient=$donnees['0'];
$date_certificat=$donnees['1'];
$date_debut=$donnees['2'];
$nbr_jours=$donnees['3'];
$motif=$donnees['4'];
?>
<!DOCTYPE html>
<html>

<head>
    <title>Modification d'un certificat</title>
    <?php include 'entete.php'; ?>
</head>
<body style="background-image: url(<?= $image ?>bgaccueil.jpg);">
    <?php
    include 'verification_menu_sante.php';         
    ?>
    <div class="container white">

        <div class="row z-depth-5" style="padding: 10px;">
            <h3 class="center">Modification d'un certificat médical</h3>
            <form class="col s12" method="POST" id="form" action="m_certificat_med_trmnt.php?id=<?=$_GET['id'] ?>">
                <div class="row">
                    <h4 class="col s12">Patient : <?=$patient ?></h4>
                    
                </div>
                <div class="row">
                    <div class="col s3 input-field">
                        <input type="text" class="datepicker" value="<?=$date_certificat ?>" name="date_certificat" id="date_certificat" required>
                        <label for="date_certificat">Date certificat</label>
                    </div>
                </div>
                <div class="row">
                    <div class="col s3 input-field">
                        <input type="text" class="datepicker" value="<?= $date_debut ?>"name="date_debut" id="date_debut" required>
                        <label for="date_debut">Date début</label>
                    </div>
                    <div class="input-field col s2">
                        <input type="number" value="<?=$nbr_jours ?>" name="nbr_jours" id="nbr_jours">
                        <label for="nbr_jours">Nbr jour(s)</label>
                    </div>
                </div>
                <div class="row">
                    <div class="col s6 input-field">
                            <textarea required="" class='materialize-textarea' name="motif" id="motif"><?=$motif ?></textarea>
                            <label for="motif">Motif du certificat</label>
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
            if (!confirm('Voulez-vous confirmer l\'enregistrement de cette opération ?')) {
                return false;
            }
        });
        $('select').formSelect();
        $('.datepicker').datepicker({
            autoClose: true,
            yearRange: [2017, 2021],
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
