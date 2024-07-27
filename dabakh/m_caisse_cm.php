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
$req=$db->prepare('SELECT id, type, motif, section, date_operation, montant, pj FROM caisse_commerce WHERE id=?');
$req->execute(array($_GET['id']));
$donnees=$req->fetch();
$type=$donnees['1'];
$motif=$donnees['2'];
$section=$donnees['3'];
$date_operation=$donnees['4'];
$montant=$donnees['5'];
$pj=$donnees['6'];
?>
<!DOCTYPE html>
<html>

<head>
    <title>Modification d'une opération</title>
    <?php include 'entete.php'; ?>
</head>

<body style="background-image: url(<?= $image ?>bgaccueil.jpg);">
    <?php include 'verification_menu_cm.php'; ?>
    <div class="container white">
        <div class="row z-depth-5" style="padding: 10px;">
            <h3 class="center">Modification d'une opération</h3>
            <form class="col s12 m12" method="POST" id="form" action="m_caisse_cm_trmnt.php?id=<?=$_GET['id']?>">
                <div class="row">
                    <div class="col s4 m4 input-field">
                        <select class="browser-default" name="type" required>
                            <option value="" disabled>Choisir type de l'opération</option>
                            <option value="entree" <?php if ($type=='entree' ) { echo "selected" ; } ?> > Débit</option>
                            <option value="sortie" <?php if ($type=='sortie' ) { echo "selected" ; } ?>>Crédit</option>
                        </select>
                    </div>
                    <div class="col s6 m6 input-field">
                        <select class="browser-default" name="section" required>
                            <option value="" disabled >Section</option>
                            <option value="Approvisionnement" <?php if ($section=='Approvisionnement' ) { echo "selected" ; } ?>>
                                Approvisionnement
                            </option>
                            <option value="Assurance" <?php if ($section=='Assurance' ) { echo "selected" ; } ?> >
                                Assurance
                            </option>
                            <option value="Diver" <?php if ($section=='Diver' ) { echo "selected" ; } ?> >
                                Diver
                            </option>
                            <option value="Eau" <?php if ($section=='Eau' ) { echo "selected" ; } ?> >
                                Eau
                            </option>
                            <option value="Electricite" <?php if ($section=='Electricite' ) { echo "selected" ; } ?> >
                                Electricite
                            </option>
                            <option value="Fournitures Bureau" <?php if ($section=='Fournitures Bureau' ) { echo "selected" ; } ?> >
                                Fournitures Bureau
                            </option>
                            <option value="Remuneration" <?php if ($section=='Remuneration' ) { echo "selected" ; } ?> >
                                Remuneration
                            </option>
                            <option value="Reglement facture" <?php if ($section=='Reglement facture' ) { echo "selected" ; } ?> >
                                Reglement facture
                            </option>
                            <option value="Repas" <?php if ($section=='Repas' ) { echo "selected" ; } ?> >
                                Repas
                            </option>
                            <option value="Salaire" <?php if ($section=='Salaire' ) { echo "selected" ; } ?> >
                                Salaire
                            </option>
                            <option value="Telephonie" <?php if ($section=='Telephonie' ) { echo "selected" ; } ?> >
                                Telephonie
                            </option>
                            <option value="Loyer" <?php if ($section=='Loyer' ) { echo "selected" ; } ?> >
                                Loyer
                            </option>
                            <option value="Transport" <?php if ($section=='Transport' ) { echo "selected" ; } ?> >
                                Transport
                            </option>
                            <option value="Versement" <?php if ($section=='Versement' ) { echo "selected" ; } ?> >
                                Versement
                            </option>
                            
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="input-field col s8 m8">
                        <input type="text" value="<?=$motif ?>" name="motif" id="motif">
                        <label for="motif">Motif de l'opération</label>
                    </div>
                     <div class="input-field col s3 m2">
                        <input type="number" value="<?=$pj ?>" name="pj" id="pj">
                        <label for="pj">N° pièce jointz</label>
                    </div>
                </div>
                <div class="row">
                    <div class="col s3 m3 input-field">
                        <input type="text" value="<?=$date_operation ?>" class="datepicker" name="date_operation" id="date_operation" required>
                        <label for="date_operation">Date de l'opération</label>
                    </div>
                    <div class="input-field col s6 m6">
                        <input type="number" value="<?=$montant ?>" name="montant" id="montant">
                        <label for="montant">Montant de l'opération</label>
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
            if (!confirm('Voulez-vous confirmer la Modification de cette opération ?')) {
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
