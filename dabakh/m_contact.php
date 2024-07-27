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

$req=$db->prepare("SELECT id, telephone, mail, contact, autres_infos  FROM `contact` WHERE id=?");
$req->execute(array($_GET['id']));       
$donnees=$req->fetch();
$id=$donnees['0'];
$telephone=$donnees['1'];
$mail=$donnees['2'];
$contact=$donnees['3'];
$autres_infos=$donnees['4'];
?>
<!DOCTYPE html>
<html>

<head>
    <title>Modification d'un contact</title>
    <?php include 'entete.php'; ?>
</head>

<body style="background-image: url(<?= $image ?>bgaccueil.jpg);">
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
            <h4 class="center">Modification  d'un contact</h4>
            <form class="col s12" method="POST" id="form" action="m_contact_trmnt.php?id=<?= $id ?>">
                <div class="row">
                    <div class="input-field col s12 m8">
                        <input type="text" name="contact" value="<?= $contact ?>" id="contact">
                        <label for="contact">Prénom et Nom</label>
                    </div>
                </div>
                <div class="row">
                    <div class="col s3 input-field">
                        <input type="text" name="telephone" value="<?= $telephone ?>" id="telephone" required>
                        <label for="telephone">N° Téléphone</label>
                    </div>
                    <div class="input-field col s12 m6">
                        <input type="text" name="mail" value="<?= $mail ?>" id="mail">
                        <label for="mail">Adresse Mail</label>
                    </div>
                </div>
                <div class="row">
                    <div class="input-field col s12 m8">
                        <input type="text" name="autres_infos" value="<?= $autres_infos ?>" id="autres_infos">
                        <label for="autres_infos">Autres Informations</label>
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
            yearRange: [2017, <?=(date('Y')+1) ?>],
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
