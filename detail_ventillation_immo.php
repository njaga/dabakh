<?php
session_start();
include 'connexion.php';
include 'supprim_accents.php';
$jour_d=$_GET['d'];
$jour_f=$_GET['f'];
$s=$_GET['s'];
$total=0;
$id_user=$_SESSION['prenom']." ".$_SESSION['nom'];
$req_section=$db->prepare("SELECT CONCAT(DATE_FORMAT(date_operation, '%d'), '/', DATE_FORMAT(date_operation, '%m'),'/', DATE_FORMAT(date_operation, '%Y')), motif, montant
FROM caisse_immo
WHERE date_operation BETWEEN ? AND ? AND section=?
ORDER BY date_operation");
$req_section->execute(array($jour_d, $jour_f, $s));
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Détails ventillation</title>
        <?php include 'entete.php'; ?>
    </head>
    <body style="background-color: grey">
        <?php include 'verification_menu_immo.php';?>
        <div class="container white">
            <a onclick="window.history.go(-1)" class="btn">Retour</a>
            <h4 class="center" style="color: #0d47a1">Ventillation "<?=$s ?>"</h4>
            <div class="row">
                <table class="col s10 m8 offset-s1 offset-m2">
                    <thead>
                        <tr style="color: #fff; background-color: #bd8a3e">
                            <th data-field="" class="center">Date</th>
                            <th data-field="" class="center">libellé</th>
                            <th data-field="" class="center">Montant</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        while ($donnees=$req_section->fetch()) 
                        {
                            echo "<tr>";
                                echo "<td class='center black-text'>".$donnees['0']."</td>";
                                echo "<td class='center black-text'>".ucfirst(strtolower(suppr_accents($donnees['1'])))."</td>";
                                echo "<td class='right black-text'>".number_format($donnees['2'],0,'.',' ')."</td>";
                            echo "</tr>";
                            $total=$total+$donnees['2'];
                        }
                        echo "<tr>";
                            echo "<td class='center black-text' colspan='2'><b>TOTAL</b></td>";
                            echo "<td class='right black-text'><b>".number_format($total,0,'.',' ')."</b></td>";
                        echo "</tr>";
                        ?>
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
    margin-top: 0px;
    }
    .datepicke{
    border-color: transparent;
    }
    }
    </style>
</html>