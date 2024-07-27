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
if($_SERVER['REQUEST_METHOD']=='POST')
{
    $id_user=$_SESSION['prenom'].".".$_SESSION['nom'];
    $req=$db->prepare("INSERT INTO  planning_recouvrement (date_debut, date_fin, date_creation, id_user, id_agent) VALUES(?,?,NOW(),?,?)");
    $req->execute(array($_POST['date_debut'], $_POST['date_fin'], $id_user, $_POST['agent'])) or die(print_r($req->errorInfo()));
    $id_planning=$db->lastInsertId();
    header("location:e_planning1.php?id=".$id_planning);
}
$req_agent=$db->query("SELECT id, prenom, nom, fonction, service FROM personnel WHERE etat='activer' and fonction<>'administrateur' and fonction<>'daf' ORDER BY nom");
 ?>
<!DOCTYPE html>
<html>
	<head>
    <title>Nouveau planning</title>
	</head>
	<?php include 'entete.php'; ?>
	<body style="background-image: url(<?= $image ?>bgaccueil.jpg);">
		<?php
		include 'verification_menu_immo.php';
		?>
		<div class="container white">
			<div class="row z-depth-5" style="padding: 10px;">
				<h3 class="center">Création d'un planning de recouvrement</h3>
				<form class="col s12" method="POST" id="form" action="<?= $_SERVER['PHP_SELF'];?>" >
					<div class="row">
                        <div class="col s5 input-field">
                            <input type="text"   name="date_debut" class=datepicker id="date_debut" required>
                            <label for="date_debut">Date début planning</label>
                        </div>                        
                        <div class="col s5 input-field">
                            <input type="text"   name="date_fin" class=datepicker id="date_fin" required>
                            <label for="date_fin">Date fin planning</label>
                        </div>
                    </div>
                    <div class="row">
                    <select class="browser-default col s10 m5" name="agent">
                        <option value="" disabled selected>--Sélectionner l'agent--</option>
                        <?php
                        while ($donnee=$req_agent->fetch())
                        {
                            echo '<option value="'. $donnee['0'] .'"';
                            echo ">"; 
                            echo $donnee['1']." ".$donnee['2'] .'</option>';
                        }
                        ?>
                    </select>
                    </div>
                    <?php
                        if($_SESSION['fonction']=="administrateur")
                        {
                            ?>
                            <div class="row">
                                <div class="col s2 offset-s8 input-field">
                                    <input type="submit" class="btn "  name="enregistrer" value="Enregistrer" >
                                </div>
                            </div>
                            <?php
                        }
                    ?>
                    
				</form>
			</div>
		</div>
	</body>
	<script type="text/javascript">
		$(document).ready(function () {
			$('select').formSelect();
			$('#form').submit(function () {
				if (!confirm('Voulez-vous confirmer l\'enregistrement de ce nouveau planning ?')) {
					return false;
				}
            });
            $('.datepicker').datepicker({
                autoClose: true,
                yearRange:[2014,<?=(date('Y')+1) ?>],
                showClearBtn: true,
                i18n:{
                    nextMonth: 'Mois suivant',
                    previousMonth: 'Mois précédent',
                    labelMonthSelect: 'Selectionner le mois',
                    labelYearSelect: 'Selectionner une année',
                    months: [ 'Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre' ],
                    monthsShort: [ 'Jan', 'Fev', 'Mar', 'Avr', 'Mai', 'Jun', 'Jul', 'Août', 'Sep', 'Oct', 'Nov', 'Dec' ],
                    weekdays: [ 'Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi' ],
                    weekdaysShort: [ 'Dim', 'Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam' ],
                    weekdaysAbbrev: [ 'D', 'L', 'M', 'M', 'J', 'V', 'S' ],
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