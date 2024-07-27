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
?>
<!DOCTYPE html>
<html lang="Fr">
<head>
  <title>Ajout Utilisateur</title>
  <?php include'entete.php'?>
  <?php include'css1.php'?>
</head>
<body id="debut" style="background-image: url(<?= $image ?>bgaccueil.jpg);">
    <?php include'verification_menu_immo.php';?>
    <!-- Material form register -->
    <div class="container">
        <div class="card">

        <h5 class="card-header info-color white-text text-center py-4">
            <strong>Ajout Utilisateur</strong>
        </h5>

        <!--Card content-->
        <div class="card-body px-lg-5 pt-0">

            <!-- Form -->
            <form class="text-center" style="color: #757575;" action="e_utilisateur_tmnt.php" id="form" method="POST">
                
                <!-- Nom utilisateurs -->
                <div class="form-row">
                    <div class="col-5">
                        <div class="md-form">
                            <input type="text" id="prenom" name="prenom" class="form-control" required="">
                            <label for="prenom">Prenom</label>
                        </div>
                    </div>
                    <div class="col-5 ">
                        <div class="md-form">
                            <input type="text" id="nom" name="nom" class="form-control" required="">
                            <label for="nom">Nom</label>
                        </div>
                    </div>
                   
                </div>
                 <!-- profil utilisateurs-->
                 <div class="form-row">
                    <div class="col-6">
                        <select class="browser-default custom-select" name="profil" required="">
                            <option selected>Sélectionnez la profil</option>
                            <option value="dg">Directeur réseau</option>
                            <option value="commercial">Business developer</option>
                            <option value="marketing">Support marketing & communication</option>
                            <option value="Comptable">Comptable</option>
                            <option value=""></option>

                        </select>
                    </div>
                 </div>   
                 <br>   
				<!-- Contact utilisateurs -->
                 <div class="form-row">
                    <div class="col-6">
                        <div class="md-form mt-0">
                            <input type="text" id="contact" name="contact" class="form-control" required="">
                            <label for="contact">Téléphone</label>
                        </div>
                    </div>
                </div>   
				<!-- Email utilisateurs -->
                 <div class="form-row">
                    <div class="col-6">
                        <div class="md-form mt-0">
                            <input type="email" id="email" name="email" class="form-control" required="">
                            <label for="email">Email</label>
                        </div>
                    </div>
                </div>   
                <!-- Sign up button -->
                <button class="btn btn-outline-info btn-rounded btn-block my-4 waves-effect z-depth-0 col-12 col-md-4" type="submit">Enregistrer</button>
            </form>
            <!-- Form -->
        </div>
        </div>
    </div>

    <?php include'js.php'?>
  <script type="text/javascript">
    $(document).ready(function() {
        $('.mdb-select').materialSelect();
        $('#form').submit(function () {
            if (!confirm('Voulez-vous confirmer l\'enregistrement de cet étudiant ?')) {
                return false;
            }
        });
        });
  </script>
</body>
</html>
