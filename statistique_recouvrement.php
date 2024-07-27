<?php
session_start();
include 'connexion.php';
$req_annee=$db->query('SELECT DISTINCT annee FROM `mensualite` ORDER BY annee ASC');
$annee= date('Y');
if (isset($_GET['id'])) 
{
 $id=$_GET['id'];
 $req_bailleur=$db->prepare("SELECT CONCAT(prenom,' ',nom) FROM `bailleur` WHERE id=?");
  $req_bailleur->execute(array($id));
  $donnee=$req_bailleur->fetch();
  $bailleur=$donnee['0'];
}
else
{
  $id=0;
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Statitique des recouvrements : </title>
    <!-- MDB icon -->
    <link rel="icon" href="img/mdb-favicon.ico" type="image/x-icon">
    <!-- Font Awesome -->
    <!-- Google Fonts Roboto -->
    <!-- Bootstrap core CSS -->
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <!-- Material Design Bootstrap -->
    <link rel="stylesheet" href="../css/mdb.min.css">
    <!-- Your custom styles (optional) -->
    <link rel="stylesheet" href="../css/style.css">
  </head>
  <body>
    <!-- Start your project here-->
    <div class="container">
      <br>
      <input type="number" name="id" value="<?=$id ?>" hidden>
      <div class="row">
        <button type="button" class="btn btn-primary" onclick="window.history.go(-1)">Retour</button>
        <select class="browser-default custom-select col-md-1" name="annee">
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
      </div>
      <div class="row">
        <h2 class="col-md-12 center"><b>Statistique des recouvrements</b></h2>
      </div>
      <div class="graphe row">
      </div>
    </div>
    <!-- End your project here-->
    <!-- jQuery -->
    <script type="text/javascript" src="../js/jquery.min.js"></script>
    <!-- Bootstrap tooltips -->
    <script type="text/javascript" src="../js/popper.min.js"></script>
    <!-- Bootstrap core JavaScript -->
    <script type="text/javascript" src="../js/bootstrap.min.js"></script>
    <!-- MDB core JavaScript -->
    <script type="text/javascript" src="../js/mdb.min.js"></script>
    <!-- Your custom scripts (optional) -->
    <script type="text/javascript"></script>
    <script type="text/javascript">
    $(document).ready(function(){
    function statistique()
    {
    var annee = $('select:eq(0)').val();
    var id = $('input:eq(0)').val();
    $.ajax({
    type:'POST',
    url:'statistique_recouvrement_ajax.php',
    data:'annee='+annee+'&id='+id,
    success:function (html) {
    $('.graphe').html(html);
    }
    });
    }
    
    statistique();
    $('select').change(function(){
    statistique();
    });
    
    });
    </script>
  </body>
</html>