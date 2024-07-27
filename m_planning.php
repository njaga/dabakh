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
$id_planning=$_GET['id'];
$id_user=$_SESSION['prenom'].".".$_SESSION['nom'];
//Enregistrement du planning


$db->query("SET lc_time_names = 'fr_FR';");
$req=$db->prepare("SELECT CONCAT(day(planning_recouvrement.date_debut),' ', monthname(planning_recouvrement.date_debut),' ', year(planning_recouvrement.date_debut)), CONCAT(day(planning_recouvrement.date_fin),' ', monthname(planning_recouvrement.date_fin),' ', year(planning_recouvrement.date_fin))
FROM planning_recouvrement
WHERE id=?");
$req->execute(array($id_planning)) or die(print_r($req->errorInfo()));
$donnee=$req->fetch();
$date_planning_fin=$donnee['0'];
$date_planning_debut=$donnee['1'];
 ?>
<!DOCTYPE html>
<html>
	<head>
    <title>Modifcation planning</title>
	</head>
	<?php include 'entete.php'; ?>
	<body style="background-image: url(<?= $image ?>bgaccueil.jpg);">
		<?php
		include 'verification_menu_immo.php';
		?>
        <input type="number" name="id_planning" value="<?=$id_planning ?>" hidden class="id_planning">
		<div class="container white">
			<div class="row z-depth-5" style="padding: 10px;">
				<h3 class="center">Modifcation planning de recouvrement du <b><?=$date_planning_debut ?> AU <?=$date_planning_fin ?></b></h3>
					<div class="row" style="overflow:scroll; border:#000000 1px solid; height: 400px;">
                        <table class="bordered highlight centered striped">
                            <thead>
                                <tr style="color: #fff; background-color: #bd8a3e">
                                    <th></th>
                                    <th data-field="">locataire</th>
                                    <th data-field="">Adresse</th>
                                    <th data-field="">Bailleur</th>
                                    <th ></th>
                                </tr>
                            </thead>
                            <tbody class="list_recouvrement">
                                <?php
                                    $req=$db->prepare("SELECT CONCAT(locataire.prenom,' ',locataire.nom), CONCAT(bailleur.prenom,' ', bailleur.nom), logement.adresse, planning_recouv_locataire.id 
                                    FROM `planning_recouv_locataire`
                                    INNER JOIN locataire ON planning_recouv_locataire.id_locataire=locataire.id
                                    INNER JOIN location ON location.id_locataire=locataire.id
                                    INNER JOIN logement ON location.id_logement=logement.id
                                    INNER JOIN bailleur ON logement.id_bailleur=bailleur.id
                                    WHERE planning_recouv_locataire.id_planning=?");
                                    $req->execute(array($id_planning)) or die(print_r($req->errorInfo()));
                                    $nbr=$req->rowCount();
                                    if($nbr>0)
                                    {
                                        $i=0;
                                        while($donnees=$req->fetch())
                                        {
                                            $i++;
                                            //$date_recouvrement=$donnees['0'];
                                            $locataire=$donnees['0'];
                                            $bailleur=$donnees['1'];
                                            $adresse=$donnees['2'];
                                            $planning_recouv_locataire=$donnees['3'];
                                            //echo"<td>".$date_recouvrement."</td>";                                
                                            echo"<tr>";
                                            echo"<td><b>".$i."</b></td>";                                
                                            echo"<td>".$locataire."</td>";                                
                                            echo"<td>".$adresse."</td>";                                
                                            echo"<td>".$bailleur."</td>";                                
                                            echo"<td><a href='s_loc_planning.php?s=".$planning_recouv_locataire."' class='btn red'>Supprimer</a></td>";                                
                                            echo"</tr>";
                                        }
                                    }
                                    else
                                    {
                                        echo"<td colspan='5'><h4>Aucun résultat</h5>";
                                    }
                                ?>
                            </tbody>
                        </table>
                    </div>
                    <h4 class='center'>Ajout des locataires au planning</h4>
                    <div class="row" style="overflow:scroll; border:#000000 1px solid; height: 400px;">
                        <div class="col s12 m4 input-field white" style="border-radius: 45px">
                            <i class="material-icons prefix">search</i>
                            <input type="text" name="search" id="search" class="search">
                            <label for="search">Prénom / Nom</label>
                        </div>
                        <div class=" row">
                            <table class="bordered highlight centered striped col s12">
                                <thead>
                                    <tr style="color: #fff; background-color: #bd8a3e">
                                        <th  data-field="">N° dossier</th>
                                        <th data-field="prenom">Prénom et nom</th>
                                        <th data-field="tel">N° Téléphone</th>
                                        <th data-field="tel">Bailleur</th>
                                        <th ></th>
                                    </tr>
                                </thead>
                                <tbody class='list_locataires'>
                                    
                                    
                                </tbody>
                            </table>
                        </div>
                    </div>
					<div class="row">
						<div class="col s4 offset-s6 input-field">
                        <a href="l_planning_recouv.php" class="btn">Enregistrer modification</a>
						</div>
					</div>
				</form>
			</div>
		</div>
	</body>
	<script type="text/javascript">
		$(document).ready(function () {
			$('select').formSelect();
			$('#form').submit(function () {
				if (!confirm('Voulez-vous confirmer la modification de ce planning ?')) {
					return false;
				}
            });
            $('.datepicker').datepicker({
                autoClose: true,
                yearRange:[2014,2021],
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
            function l_locataires() {
                var search = $('.search').val();
                var id_planning = $('.id_planning').val();
				$.ajax({
					type:'POST',
					url:'e_planning_locataire_ajax.php',
					data:'search='+search+'&id='+id_planning,
					success:function (html) {
						$('.list_locataires').html(html);
					}
				});
			}

			var search ="";
			l_locataires();
			$('.search').keyup(function(){
			
			l_locataires()
				});
		});
		
	</script>
</html>