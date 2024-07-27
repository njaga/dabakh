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
$req=$db->prepare('SELECT * FROM caisse_immo WHERE id=?');
$req->execute(array($_GET['id']));
$donnees=$req->fetch();
$type=$donnees['1'];
$motif=$donnees['2'];
$section=$donnees['3'];
$date_operation=$donnees['4'];
$montant=$donnees['5'];
$pj=$donnees['12'];
?>
<!DOCTYPE html>
<html>

<head>
    <title>Modification d'une opération</title>
    <?php include 'entete.php'; ?>
</head>

<body style="background-image: url(<?= $image ?>bgaccueil.jpg);">
    <?php include 'verification_menu_immo.php'; ?>
    <div class="container white">
        <div class="row z-depth-5" style="padding: 10px;">
            <h3 class="center">Modification d'une opération</h3>
            <form class="col s12 m12" method="POST" id="form" action="m_caisse_immo_trmnt.php?id=<?=$_GET['id']?>" enctype="multipart/form-data">
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
                            <option value="Approvisionnement banque par caisse" <?php if ($section=='Approvisionnement banque par caisse' ) { echo "selected" ; } ?>>
                                Approvisionnement banque par caisse
                            </option>
                            <option value="Approvisionnement caisse par banque" <?php if ($section=='Approvisionnement caisse par banque' ) { echo "selected" ; } ?>>
                                Approvisionnement caisse par banque
                            </option>
                            <option value="Approvisionnement immo par caisse sante" <?php if ($section=='Approvisionnement immo par caisse sante' ) { echo "selected" ; } ?>>
                                Approvisionnement immo par caisse sante
                            </option>
                            <option value="Approvisionnement DG" <?php if ($section=='Approvisionnement DG' ) { echo "selected" ; } ?>>
                                Approvisionnement DG
                            </option>
                            <option value="Assurance" <?php if ($section=='Assurance' ) { echo "selected" ; } ?> >
                                Assurance
                            </option>
                            <option value="Diver" <?php if ($section=='Diver' ) { echo "selected" ; } ?> >
                                Diver
                            </option>
                            <option value="BTP" <?php if ($section=='BTP' ) { echo "selected" ; } ?> >
                                BTP
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
                            <option value="Remboursement" <?php if ($section=='Remboursement' ) { echo "selected" ; } ?> >
                                Remboursement
                            </option>
                            <option value="Remboursement bailleur" <?php if ($section=='Remboursement bailleur' ) { echo "selected" ; } ?> >
                                Remboursement bailleur
                            </option>
                            <option value="Remboursement DG" <?php if ($section=='Remboursement DG' ) { echo "selected" ; } ?> >
                                Remboursement DG
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
                <!--Pièces Jointes -->
					<div class="row" id="doc">
						<h4 class="center col s12">Pièces Jointes</h4>
						<h5 class="center">
						<?php
						$req_pj=$db->prepare('SELECT * FROM pj_caisse WHERE id_caisse=?');
						$req_pj->execute(array($_GET['id']));
						$nbr=$req_pj->rowCount();
						if($nbr>0)
						{
							while ($donnees_pj=$req_pj->fetch()) 
							{
								echo "<div class='row'>";
									echo "<a class='col s5' href='".$donnees_pj['2']."'>".$donnees_pj['1']."</a>";
									echo "&nbsp&nbsp&nbsp";
									if ($_SESSION['fonction']=='administrateur' )
										{
									echo "<a class='col s2 red-text' href='s_pj_caisse_immo.php?s=".$donnees_pj['0']."' onclick='return(confirm(\"Voulez-vous supprimer cette pièce jointe ?\"))'>Supprimer</a>";
								}
									echo "<br>";
								echo "</div>";
							}
						}
						else
						{
							echo"Aucun fichier";
						}
						?>
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
