<?php

session_start();
include 'connexion.php';
include 'supprim_accents.php';

$nom=htmlspecialchars(strtoupper(suppr_accents($_POST['nom'])));
$prenom=htmlspecialchars(strtoupper(suppr_accents($_POST['prenom'])));
$profil=htmlspecialchars($_POST['profil']);
$contact=htmlspecialchars($_POST['contact']);
$email=htmlspecialchars(strtolower(suppr_accents($_POST['email'])));
$login="";
$contact=htmlspecialchars($_POST['contact']);
$id=$_SESSION['id_vigilus_user'];

$reponse=$db->prepare('SELECT COUNT(*) FROM user WHERE nom=?');
$reponse->execute(array($nom));
$donnee= $reponse->fetch();
$nbr=($donnee['0'] + 1);
if ($nbr==1)
{
	$login= strtolower(str_replace(" ", "_", $nom))."@vigilus";
}
else
{
	$login= strtolower(str_replace(" ", "_", $nom)).$nbr."@vigilus";
}

//Génération du code
$chaine="ABCDEFGHJKLMNOPQRS&@TUVWXYZabcdefghijklmnopqrstuvwxyz";
$chaine=str_shuffle($chaine);
$i=random_int(0, 60);
$chaine=substr($chaine, $i,2);
$pwd=$chaine.strtolower($nom);

$req=$db->prepare('INSERT INTO user (nom, prenom, profil, telephone, email, login, pwd, id_user) VALUES(?,?,?,?,?,?,?,?) ');
$nbr=$req->execute(array($nom, $prenom, $profil, $contact, $email, $login, sha1($pwd), $id)) or die(print_r($req->errorInfo()));
if ($nbr>0) {
?>

<!DOCTYPE html>
<html>
	<head>
		<title>Insertion réussie</title>
		<?php include 'css.php'; ?>
	</head>
	<body >
		<div class="modal fade" id="centralModalSuccess" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
		aria-hidden="true">
			<div class="modal-dialog modal-notify modal-success" role="document">
				<!--Content-->
				<div class="modal-content">
					<!--Header-->
					<div class="modal-header">
						<p class="heading lead">Utilisateur enregistré</p>
					</div>

					<!--Body-->
					<div class="modal-body">
						<div class="text-center">
							<i class="fas fa-check fa-4x mb-3 animated rotateIn"></i>
							<p>Utilisateur : <b><?=$prenom ?> <?=$nom ?></b> enregistré </p>
							<p>Login :  <b><?=$login ?> </b> </p>
							<p>Mot de passe :  <b><?=$pwd ?> </b> </p>

						</div>
					</div>
					<!--Footer-->
					<div class="modal-footer justify-content-center">
						<a type="button" href="e_utilisateur.php" class="btn btn-success">Fermer <i class="far fa-gem ml-1 text-white"></i></a>
					</div>
				</div>
				<!--/.Content-->
			</div>
		</div>

    <?php include 'js.php'; ?>
    <script type="text/javascript">
        $(document).ready(function() {
            $('#centralModalSuccess').modal('show');
        });
    </script>
	</body>
	<style type="text/css">

	</style>
<?php
}
else
{
?>
<script type="text/javascript">
	alert('Echec d\'enregistrement ');
	window.history.go(-1);
</script>
<?php
}
?>
</html>
