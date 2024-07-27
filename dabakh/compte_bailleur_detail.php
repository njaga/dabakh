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
        <input type="number" hidden name="" class="search" value="<?=$_GET['id'] ?>">
        <div class="row">
            <h4 class="center #0d47a1 col s12 m12" style="">Compte bailleur <b><?= $prenom." ".$nom ?></b> du :
            </h4>
            <div class="col s5 m2 offset-m3 input-field">
                <input type="date" class="date_debut" value="<?= date('Y').'-'.date('m').'-'.date('d') ?>" name="date_debut" id="date_debut" required>
                <label for="date_debut">Date début</label>
            </div>
            <div class="col s2 m2 center">
                <p>AU</p>
            </div>
            <div class="col s5 m2 input-field">
                <input type="date" class="date_fin" value="<?= date('Y').'-'.date('m').'-'.date('d') ?>" name="date_fin" id="date_fin" required>
                <label for="date_fin">Date fin</label>
            </div>
        </div>
        <div class="row">
            <a href="e_mensualite_bailleur.php?id=<?= $_GET['id'] ?>" class="btn">Mensualité</a>
        </div>
        <div class="row tbody">
            
            
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
    function journal_caisse()
    {
    var jour_d = $('.date_debut').val();
    var jour_f = $('.date_fin').val();
    var search = $('input:first').val();
    $.ajax({
    type:'POST',
    url:'compte_bailleur_ajax.php',
    data:'date_debut='+jour_d+'&date_fin='+jour_f+'&search='+search,
    success:function (html) {
    $('.tbody').html(html);
    }
    });
    }
    journal_caisse();
    $('input').change(function(){
    journal_caisse();
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
    td, th{
    padding: initial;
    border-right: 1px solid;
    padding :2px;
    }
    th
    {
    border: 1px solid;
    }
    table
    {
    border: 1px solid;
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