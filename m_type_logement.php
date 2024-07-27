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
if (isset($_POST['type_logement']))
{
$req=$db->prepare('UPDATE type_logement SET type_logement=? WHERE id=? ');
$req->execute(array($_POST['type_logement'],$_GET['id'])) or die(print_r($req->errorInfo()));
$nbr=$req->rowCount();
if ($nbr>0)
{
?>
<script type="text/javascript">
alert('Modification réussi');
window.location="type_logement.php";
</script>
<?php
}
}
$req=$db->prepare('SELECT * FROM type_logement WHERE id=?');
$req->execute(array($_GET['id']));
$donnees= $req->fetch();
$id=$donnees['0'];
$type_logement=$donnees['1'];
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Modification type de logement</title>
		<?php include 'entete.php';?>
	</head>
	<body style="background-color: white">
		<?php
		include('verification_menu_immo.php');
		?>
		<div class="container center-align ">
			<form method="POST" class="row" name="form" action="m_type_logement.php?id=<?= $id ?>">
				
				<div class="row">
					<h4 class="center-align red-text col s12" >Modificarion d'un type de logement</h4>
				</div>
				<div class="row">
					<div class="input-field  col s6" >
						<input required id="type_logement" type="text" class="validate " name="type_logement" value="<?= $type_logement ?>"  >
						<label for="type_logement">Type logement</label>
					</div>
				</div>
				
				
				<div class="row">
					<div class="input-field center-align col s6">
						<button class="btn  waves-light blue darken-4" type="submit" name="envoyer">Modifier
						<i class="material-icons right">send</i>
						</button>
					</div>
				</div>
			</form>
			
		</div>
	</body>
</html>