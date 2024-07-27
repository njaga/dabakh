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
$annee= date('Y');
$dateFormat = date("Y-m-d");
$jour = array("Dimanche","Lundi","Mardi","Mercredi","Jeudi","Vendredi","Samedi");
$mois = array("","Janvier","Février","Mars","Avril","Mai","Juin","Juillet","Août","Septembre","Octobre","Novembre","Décembre");
$datefr = $mois[date("n")];
$req_annee=$db->query('SELECT DISTINCT YEAR(date_operation) FROM `banque` ORDER BY date_operation DESC');

?>
<!DOCTYPE html>
<html>

<head>
    <title>Etat de la banque</title>
   <?php include 'entete.php'; ?>
</head>
<body id="debut" style="background-image: url(<?= $image ?>bgaccueil.jpg);">
    <?php 
        if ($_SESSION['service']=="immobilier") 
        {
            include 'verification_menu_immo.php';
        }
        elseif($_SESSION['service']=="sante")
        {
            include 'verification_menu_sante.php';
        }
        else
        {
            include 'verification_menu_cm.php';
        }

         ?>
     <div class="fixed-action-btn">
      <a class="btn-floating btn-large brown">
        <i class="large material-icons">import_export</i>
      </a>
      <ul>
        <li><a href="#debut" class="btn-floating green"><i class="material-icons">arrow_upward</i></a></li>
        <li><a href="#fin" class="btn-floating red darken-1"><i class="material-icons">arrow_downward</i></a></li>
      </ul>
    </div>
   <div class="row page">
            <br>
            <div class="col s12   ">
                <div class="row">
                    <?php
                    if ($_SESSION['fonction']=="daf" OR $_SESSION['fonction']=="administrateur")
                    {
                        if ($_SESSION['service']=="immobilier" OR ($_SESSION['fonction']=="administrateur" AND $_SESSION['service']=="sante")) 
                        {
                        }
                        ?>
                        <a href="banque.php" class="btn col s8 m3 l2 offset-s1 offset-m1 ">Nouvelle opération</a>
                        <?php
                            
                    }
                    ?>
                    <a onclick="window.print()" href="" class="btn col s8 offset-s1  m2 offset-m1">Imprimer</a>
                    <a href="etat_banque_journalier.php" class="btn col s8 m3 l2 offset-s3  offset-m1 offset-l1">Banque journalière</a>
                    <div class="col s4 input-field white" style="border-radius: 45px">
                        <i class="material-icons prefix">search</i>
                        <input type="text" name="search" id="search">
                        <label for="search">Recherche</label>
                    </div>
                </div>
                
                <div class="row" style="margin-top:-20px;">
                    <h4 class="center" style="color: #0d47a1; ">Grand livre banque CMS du mois de</h4>
                    <h5 class="row">
                    <select class="browser-default col offset-s3 s3 m2 offset-m4 l2 offset-l4" name="mois" class="mois" style="background-color: transparent;">
                        <?php
                        for ($i=1; $i <= 12; $i++) {
                        echo "<option value='$i'";
                            if ($mois[$i]==$datefr) {
                            echo "selected";
                            }
                        echo">$mois[$i]</option>";
                        }
                        ?>
                    </select>
                    <select class="browser-default col s2 offset-s1  m2 offset-m1 l2 offset-l1" name="annee">
                        <option value="" disabled>--Choisir Annee--</option>
                        <?php
                        while ($donnee=$req_annee->fetch())
                        {
                            echo '<option value="'. $donnee['0'] .'"';
                            if ($annee==$donnee['0']) {
                                echo "selected";
                            }
                            echo ">"; 
                            echo $donnee['0'] .'</option>';
                                                    }
                        ?>
                    </select>
                    </h5>
                    <table class="" >
                        <thead>
                            <tr style="color: #fff; background-color: #bd8a3e">
                                <th >Date</th>
                                <th >P.J&nbsp&nbsp&nbsp</th>
                                <th class="libelle">N° chéque</th>
                                <th class="libelle">Libellé</th>
                                <th  class="compte">Débit</th>
                                <th  class="compte">Crédit</th>
                                <th  class="compte">Solde</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <span id="fin"></span>
</body>
<style type="text/css">
    select {
        font-family: georgia;
    }

    th {
        font: 16pt georgia;
        font-weight: bold;
    }

    table {
        background: white;
        margin-bottom: 100px;
    }

</style>
<script type="text/javascript">
    $(document).ready(function() {
        function etat_banque(mois, annee) {
            $.ajax({
                type: 'POST',
                url: 'etat_banque_ajax.php',
                data: 'mois=' + mois + '&annee=' + annee,
                success: function(html) {
                    $('tbody').html(html);
                }
            });
        }

        var mois = $('select:eq(0)').val();
        var annee = $('select:eq(1)').val();
        etat_banque(mois, annee);

        $('select').change(function() {
            var mois = $('select:eq(0)').val();
            var annee = $('select:eq(1)').val();
         etat_banque(mois, annee);
           
        });
        $('.tooltipped').tooltip();
        $('.fixed-action-btn').floatingActionButton();
    });

</script>
<style type="text/css">
.btn{
        margin-top:10px;
    }
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
    width: 100px;
    text-align: center;
    }
    .date{
    width: 70px;
    }
    
    @media print {
    .btn, .input-field, .fixed-action-btn {
    display: none;
    }
    td, th
    {
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
        width: 50%;
        margin-bottom: -25px;
    }
    

    }
</style>

</html>
