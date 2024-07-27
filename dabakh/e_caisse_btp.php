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
            <h3 class="center">Caisse BTP</h3>
            <h4 class="center">Enregistrement d'une opération</h4>
            <form class="col s12 m12" method="POST" id="form" action="e_caisse_btp_trmnt.php" enctype="multipart/form-data">
                <div class="row">
                    <div class="col s4 m4 input-field">
                        <select class="browser-default" name="type" required>
                            <option value="" disabled selected>Choisir type de l'opération</option>
                            <option value="entree">Débit</option>
                            <option value="sortie">Crédit</option>

                        </select>
                    </div>
                    <div class="col s6 m6 input-field">
                        <select class="browser-default" name="section" required>
                            <option value="" disabled selected>Section</option>
                            <option value="Approvisionnement banque par caisse">Approvisionnement banque par caisse</option>
                            <option value="Approvisionnement BTP par Immo">Approvisionnement BTP par Immo</option>
                            <option value="Approvisionnement caisse par banque">Approvisionnement caisse par banque</option>
                            <option value="Approvisionnement par DG">Approvisionnement par DG</option>
                            <option value="Achats de sable">Achats de sable </option>
                            <option value="Achats de béton">Achats de béton </option>
                            <option value="Achats de ciment">Achats de ciment</option>
                            <option value="Achats de fer">Achats de fer</option>
                            <option value="Autres achats">Autres achats</option>
                            <option value="Materiaux Sanitaire">Materiaux Sanitaire </option>
                            <option value="Materiaux Plomberie">Materiaux Plomberie </option>
                            <option value="Materiel Electricite">Materiel Electricite </option>
                            <option value="Versement">Versement</option>
                            <option value="Transport agent">Transport agent</option>
                            <option value="Transport materiel">Transport materiel</option>
                            <option value="Main d'oeuvre maçon">Main d'oeuvre maçon</option>
                            <option value="Main d'oeuvre mouleur">Main d'oeuvre mouleur</option>
                            <option value="Main d'oeuvre electricien">Main d'oeuvre electricien</option>
                            <option value="Remboursement DG par BTP">Remboursement DG par BTP</option>
                            <option value="Remboursement Immo par BTP">Remboursement Immo par BTP</option>
                            <option value="Main d'oeuvre plombier">Main d'oeuvre plombier</option>
                            <option value="Autres mains d'oeuvre">Autres mains d'oeuvre</option>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="input-field col s8 m8">
                        <input type="text" name="motif" id="motif">
                        <label for="motif">Motif de l'opération</label>
                    </div>
                    <div class="input-field col s8 m2">
                        <input type="number" name="pj" id="pj" value="0" required>
                        <label for="pj">N° pièce jointe</label>
                    </div>
                </div>
                <div class="row">
                    <div class="col s6 m3 input-field">
                        <input type="text" value="<?=date('Y').'-'.date('m').'-'.date('d') ?>" class="datepicker" name="date_operation" id="date_operation" required>
                        <label for="date_operation">Date de l'opération</label>
                    </div>
                    <div class="input-field col s6 m6">
                        <input type="number" name="montant" id="montant">
                        <label for="montant">Montant de l'opération</label>
                    </div>
                </div>
                <!--Pièces Jointes -->
					<div class="row" id="doc">
						<h4 class="center col s12">Pièces Jointes</h4>
						<h5 class="center">
						<br>
						</h5>
						<div class="file-field input-field col s10">
							<div class="btn blue darken-4">
								<span >Sélectionner</span>
								<input type="file" accept="" name="fichier[]" class=" fichier" multiple>
							</div>
							<div class="file-path-wrapper">
								<input class="file-path validate fichier" placeholder="Sélectionner le(s) document(s)"  type="text" >
							</div>
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
