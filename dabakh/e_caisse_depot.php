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
$req=$db->prepare("SELECT location.id, locataire.prenom, locataire.nom, caisse_depot.montant
FROM `caisse_depot`
RIGHT JOIN location ON caisse_depot.id_location=location.id
RIGHT JOIN locataire ON locataire.id=location.id_locataire
WHERE locataire.id=?");

$req->execute(array($_GET['id']));
$donnee=$req->fetch();
$id_location=$donnee['0'];
$prenom=$donnee['1'];
$nom=$donnee['2'];
$montant=$donnee['3'];
?>
<!DOCTYPE html>
<html>

<head>
    <title>Enregistrement d'une opération</title>
    <?php include 'entete.php'; ?>
</head>
<body style="background-image: url(<?= $image ?>bgaccueil.jpg);">
    <?php
        include 'verification_menu_immo.php';   
    ?>
    <div class="container white">

        <div class="row z-depth-5" style="padding: 10px;">
            <h3 class="center">Caisse dépôts de garantie</h3>
            <h4 class="center">
                Enregistrement d'une opération
                <br>
                Locataire : <b><?=$prenom." ".$nom ?></b>
                <br>
                Montant dépôsé : <b><?=number_format($montant,0,'.',' ') ?> Fcfa</b>
            </h4>
            <form class="col s12" method="POST" id="form" action="e_caisse_depot_trmnt.php">
                <input type="number" name="id_location"  hidden value="<?=$id_location ?>">
                <div class="row">
                    <div class="col s4 m4 input-field">
                        <select class="browser-default" name="type" required>
                            <option value="" disabled selected>Choisir type de l'opération</option>
                            <option value="entree">Débit</option>
                            <option value="sortie">Crédit</option>

                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="input-field col s8">
                        <input type="text" name="motif" id="motif" required="">
                        <label for="motif">Motif de l'opération</label>
                    </div>
                </div>
                <div class="row">
                    <div class="col s5 m5 l3 input-field">
                        <input type="text" class="datepicker" name="date_operation" id="date_operation" required>
                        <label for="date_operation">Date de l'opération</label>
                    </div>
                    <div class="input-field col s6 m5 l3">
                        <input type="number" name="montant" value="0" id="montant" required="">
                        <label for="montant">Montant de l'opération</label>
                    </div>
                    <div class="input-field col s6 m3 l2">
                        <input type="number" value="0" name="pj" id="pj" required="">
                        <label for="pj">N° pièce jointe</label>
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
