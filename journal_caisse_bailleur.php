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
include 'connexion.php';
$req=$db->query('SELECT DISTINCT YEAR(date_versement) FROM `mensualite_bailleur` WHERE date_versement IS NOT NULL');
$dateFormat = date("Y-m-d");
$jour = array("Dimanche","Lundi","Mardi","Mercredi","Jeudi","Vendredi","Samedi");
$mois = array("","Janvier","Février","Mars","Avril","Mai","Juin","Juillet","Août","Septembre","Octobre","Novembre","Décembre");
$datefr = $mois[date("n")];
?>
<!DOCTYPE html>
<html>

<head>
    <title>Compte du bailleur</title>
    <?php include 'entete.php';?>
</head>

<body style="background-image: url(<?= $image ?>bgaccueil.jpg);">
   <?php
        include 'verification_menu_immo.php';
        include 'connexion.php';
        $req_bailleur=$db->prepare("SELECT * FROM `bailleur` WHERE id=?");
        $req_bailleur->execute(array($_GET['id']));
        $donnees=$req_bailleur->fetch();
        $prenom=$donnees['2'];
        $nom=$donnees['3'];
        ?>
        <div class=" " style="padding: 5px">
            <input type="number" hidden name="" class="search" value="<?=$_GET['id'] ?>">
            <div class="row">
                <h3 class="center black-text col s12" style="color: white">Bailleur : <?= $prenom." ".$nom ?></h3>
            </div>   
            <div class="row">
                <select class="browser-default col s3" name="annee">
                    <option value="" disabled>--Choisir Annee--</option>
                    <?php
                    while ($donnee=$req->fetch())
                    {
                    echo '<option value="'. $donnee['0'] .'">'. $donnee['0'] .'</option>';
                    }
                    ?>
                </select>
            </div>
            <div class="row">
                <h4 class="center #0d47a1 col s12" style="color: white">Journal de caisse du mois de :</h4>
                <h5><select class="browser-default col s5 m3 offset-m3 l3 offset-l4" name="mois" class="mois" style=" background-color: white;">
                    <?php
                    for ($i=1; $i <= 12; $i++) {
                        echo "<option value='$mois[$i]'";
                        if ($mois[$i]==$datefr) {
                            echo "selected";
                        }
                        echo">$mois[$i]</option>";
                    }
                    ?>
                </select></h5>
            </div>
            <div class="row">
                <a href="e_mensualite_bailleur.php?id=<?= $_GET['id'] ?>" class="btn">Mensualité</a>
            </div>
            <div class="row tbody">
                
                
            </div>
        </div>
            
</body>
<style type="text/css">
   td{
    border:1px solid black;
   }
   th{
    border:1px solid black;
   }
    select {
        font-family: georgia;
    }

    th {
        font: 16pt georgia;
        font-weight: bold;
    }

    body {
        background-position: center center;
        background-repeat: no-repeat;
        background-attachment: fixed;
        background-size: cover;
        background-color: #999;
    }

    table {
        background: white;
    }
    

</style>
<script type="text/javascript">
        $(document).ready(function(){
            $('.tooltipped').tooltip();
            function journal_caisse(mois, annee,search)
            {
                $.ajax({
                type:'POST',
                url:'journal_caisse_bailleur_ajax.php',
                data:'mois='+mois+'&annee='+annee+'&search='+search,
                success:function (html) {
                    $('.tbody').html(html);
                }
            });
            }
            var mois = $('select:eq(1)').val();
            var annee = $('select:eq(0)').val();
            var search = $('input:first').val();
            journal_caisse(mois, annee,search);

            $('select').change(function(){
                var mois = $('select:eq(1)').val();
                var annee = $('select:eq(0)').val();
                var search = $('input:first').val();
                journal_caisse(mois, annee,search);
                });

        })
</script>
<style type="text/css">
    /*import du css de materialize*/
    @import "../css/materialize.min.css"print;

    /*CSS pour la page à imprimer */
    /*Dimension de la page*/
    @page {
        size: portrait;
        margin: 15px;
        margin-top: 25px;
    }

    @media print {
        .btn {
            display: none;
        }

        nav {
            display: none;
        }

        div {
            font: 12pt "times new roman";
        }

        select {
            border-color: transparent
        }
    }

</style>

</html>
