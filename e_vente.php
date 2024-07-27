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
//selection du dernier numéro de vente auquel on ajoute 1 pour former le nouveau
$reqCount=$db->query("SELECT id, date_vente FROM vente WHERE id=(SELECT MAX(id) FROM vente)");
$donnesCount=$reqCount->fetch();
$id_vente=$donnesCount['0'];
$date_vente=$donnesCount['1'];
if(!empty($date_vente))
{
    $id_vente=$id_vente+1;
    $req_depot=$db->prepare("INSERT INTO vente (id) VALUES(?)");
    $req_depot->execute(array($id_vente));
}

//selection du client
$req_client=$db->prepare("SELECT CONCAT(client.prenom,' ', client.nom) FROM `client` WHERE id=?");
$req_client->execute(array($_GET['id']));
$donnesCount=$req_client->fetch();
$client=$donnesCount['0'];
$id_client=$_GET['id'];

//selection des produits
$req_article=$db->query("SELECT id, article, pu, qt FROM article WHERE etat=1");

?>
<!DOCTYPE html>
<html>
	<head>
		<title>Enregistrer vente</title>
		<meta charset="utf-8">
		<?php include 'entete.php'; ?>
	</head>
	<body style="background-image: url(<?= $image ?>bgaccueil.jpg);">
		<?php
		include 'verification_menu_cm.php';
		?>
		<div class="container white">
			<div class="row z-depth-5" >
				<h3 class="center col s12">
                    Enregistrement d'une vente
                </h3>
				<form class="col s10 offset-s1 center" method="POST" id="form" action="e_vente_trmnt.php" >
                <input type="number" name="id_vente"  value="<?=$id_vente ?>" hidden>
                <input type="number" name="id_client"  value="<?=$id_client ?>" hidden>
                    <div class="row">
                        <!--
                        <div class="input-field col s5">
                            <input type="date" name="date_vente" value="<?= date('Y-m-d') ?>" id="date_vente" class="date_vente">
                            <label for="date_vente">Date</label>
                        </div>
                        -->
                        <h3 class="center ">Client : <?= $client ?></h3>
                    </div>
                    <div class="row">
                        <h4 class="col s12">Liste des articles</h4>
                        <table class="col s12 offset-s1">
                            <thead>
                                <tr class='grey'>
                                    <th><b>Article(s)</b></th>
                                    <th><b>PU</b></th>
                                    <th><b>Nbr</b></th>
                                    <th><b>Montant</b></th>
                                </tr>
                            </thead>
                            <tbody>
                            
                            </tbody>
                        </table>
                    </div>
                    <div class="row">
                        <div class="input-field col s5">
                            <select name="categorie" class="browser-default">
                                <option value="Aucun" disabled="" selected="" >Article(s)</option>
                                <?php
                                    while($donnes_produits=$req_article->fetch())
                                    {
                                        $id=$donnes_produits['0'];
                                        $article=$donnes_produits['1'];
                                        $pu=$donnes_produits['2'];
                                        $qt=$donnes_produits['3'];

                                        echo "<option value='".$id."'";
                                        if($qt<1){echo "disabled"; }
                                        echo ">".$article."(".$pu.") :::> ".$qt."</option>";

                                    }
                                ?>
                            </select>
                        </div>
                        <div class="col s2 input-field">
                            <input type="number" name="nbr" id="nbr" value="1" class="nbr_article" required>
                            <label for="nbr" class="active">Nbr artcile</label>
                        </div>
                        <div class="col s2 m2 input-field">
                            <a  class="btn " id="ajouter">Ajouter</a>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col s8 m4 l4 input-field">
                            <input type="text" name="date_vente" id="date_vente" value="<?= date('Y-m-d') ?>" class="datepicker date_vente" required>
                            <label for="date_vente">Date vente</label>
                        </div>
                        <div class="col s8 m4 l4 input-field">
                            <input type="text" name="frais_transport" id="frais_transport" value="0" required>
                            <label for="frais_transport">Frais de transport</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col s2 m2 input-field">
                            <a href="annuler_vente.php?id=<?=$id_vente ?>" class="btn red">Annuler</a>
                        </div>
                        <div class="col s2 m2 offset-m6 input-field">
                            <input class="btn" type="submit" name="enregistrer" value="Enregistrer" >
                        </div>
                    </div>
			    </form>
		    </div>
	    </div>
	</body>
<script type="text/javascript">
    $(document).ready(function(){
        function ajout_article()
        {
            var id_article = $('select:first').val();
            var id_vente = $('input:first').val();
            var date_vente = $('.date_vente').val();
            var nbr = $('.nbr_article').val();
            var total = $('.total').val();
                $.ajax({
                type: 'POST',
                url: 'ajout_article_vente_ajax.php',
                data: 'id_article=' + id_article+"&nbr="+nbr + '&id_vente=' + id_vente ,
                success: function(html) {
                    $('tbody').html(html);
                }
            });
        }
        $('#ajouter').click(function(){
            ajout_article();
        });
        $('.tooltipped').tooltip();
        $('.datepicker').datepicker({
				autoClose: true,
				yearRange:[<?=(date('Y')-1) ?>,<?=(date('Y')+1) ?>],
				showClearBtn: true,
				i18n:{
					nextMonth: 'Mois suivant',
					previousMonth: 'Mois précédent',
					labelMonthSelect: 'Selectionner le mois',
					labelYearSelect: 'Selectionner une année',
					months: [ 'Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre' ],
					monthsShort: [ 'Jan', 'Fev', 'Mar', 'Avr', 'Mai', 'Jun', 'Jul', 'Aou', 'Sep', 'Oct', 'Nov', 'Dec' ],
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
        $('#form').submit(function () {
			if (!confirm('Voulez-vous confirmer l\'enregistrement ?')) {
				return false;
			}
		});
    });
</script>
<style type="text/css">
        /* fallback */
    @font-face {
    font-family: 'Material Icons';
    font-style: normal;
    font-weight: 400;
    src: local('Material Icons'), local('MaterialIcons-Regular'),
    url("../digital-library/css/2fcrYFNaTjcS6g4U3t-Y5ZjZjT5FdEJ140U2DJYC3mY.woff2") format('woff2');
    }
    .material-icons {
    font-family: 'Material Icons';
    font-weight: normal;
    font-style: normal;
    font-size: 24px;
    line-height: 1;
    letter-spacing: normal;
    text-transform: none;
    display: inline-block;
    white-space: nowrap;
    word-wrap: normal;
    direction: ltr;
    -webkit-font-feature-settings: 'liga';
    -webkit-font-smoothing: antialiased;
    }
    .centrer{  margin:  auto;
    width: 800px;
    height: 25px;
    position: relative;
    right: 15%;
    
    }
    .ajout{
            position: fixed;
            right: 50px;
            top: 20%;
    }
    h4 {
    color: red;

    }
    th{
        font-size: 16px;
    }
    td{
        font-size: 16px;
    }
</style>
</html>