<?php
session_start();
$id_user=$_SESSION['prenom']." ".$_SESSION['nom'];
$annee= date('Y');
$dateFormat = date("Y-m-d");
$jour = array("Dimanche","Lundi","Mardi","Mercredi","Jeudi","Vendredi","Samedi");
$mois = array("","Janvier","Février","Mars","Avril","Mai","Juin","Juillet","Août","Septembre","Octobre","Novembre","Décembre");
if(date("n")==12){
$datefr=1;
$annee=$annee+1;
}
else{
$datefr = $mois[date("n")];
}
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Ventillation caisse immobilier</title>
        <?php include 'entete.php'; ?>
    </head>
    <body style="background-color: grey">
        <?php include 'verification_menu_immo.php';?>
        <div class="container white">
            <h4 class="center" style="color: #0d47a1">Ventillation caisse immobilier</h4>
            <h5 class="row">
            <div class="col s5 m3 offset-m3 input-field">
                <input type="date" class="date_debut" value="<?= date('Y').'-'.date('m').'-'.date('d') ?>" name="date_debut" id="date_debut" required>
                <label for="date_debut">Date début</label>
            </div>
            <div class="col s2 m2 center">
                <p>AU</p>
            </div>
            <div class="col s5 m3 input-field">
                <input type="date" class="date_fin" value="<?= date('Y').'-'.date('m').'-'.date('d') ?>" name="date_fin" id="date_fin" required>
                <label for="date_fin">Date fin</label>
            </div>
            </h5>
            <div class="row">
                <table class="col s8 m8 offset-s2 offset-m2">
                    <thead>
                        <tr style="color: #fff; background-color: #bd8a3e">
                            <th data-field="" class="center">Section</th>
                            <th data-field="" class="center">Montant</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
            <div class="row">
                <div class="col s4 offset-s8 center"><b><u>Caissier(e)</u></b></div>
            </div>
            <div class="row">
                <div class="col s4 offset-s8 center"><b><?=$id_user ?></b></div>
            </div>
        </div>
    </body>
    <style type="text/css">
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
    $(document).ready(function() {
    function ventilation_caisse_sante()
    {
    var jour_d = $('.date_debut').val();
    var jour_f = $('.date_fin').val();
    $.ajax({
    type: 'POST',
    url: 'ventillation_caisse_immo_ajax.php',
    data: 'jour_d=' + jour_d+"&jour_f="+jour_f,
    success: function(html) {
    $('tbody').html(html);
    }
    });
    }
    ventilation_caisse_sante();
    $('input').change(function() {
    ventilation_caisse_sante();
    });
    $('.tooltipped').tooltip();
    });
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
    .compte{
    width: 90px;
    text-align: center;
    }
    .date{
    width: 70px;
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
    h4{
    margin-bottom: -20px;
    }
    select {
    border-color: transparent;
    margin-bottom: -15px;
    }
    .page{
    padding: -20px;
    }
    img{
    width: 65%;
    margin-bottom: -25px;
    margin-top: -15px;
    }
    table{
    margin-top: -35px;
    }
    .datepicke{
    border-color: transparent;
    }
    }
    </style>
</html>