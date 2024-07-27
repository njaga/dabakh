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
$req=$db->prepare('SELECT * FROM analyse WHERE id=?');
$req->execute(array($_GET['id']));
$donnees=$req->fetch();
$id=$donnees['0'];
$analyse=$donnees['1'];
$cout=$donnees['2'];
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Modificarion analyse</title>
		<?php include 'entete.php';?>
	</head>
	<body>
		<?php
		include 'verification_menu_sante.php';
		?>
		<div class="col s4 m4 l4 ax white">
			<div class="container center-align ax ">
				<form method="POST" action="m_analyse_trmnt.php?id=<?php echo $_GET['id'] ?>"   >
					
					<h3 class="center-align" >Modificarion d'une analyse</h3>
					<div class="row">
						<div class="input-field col s5 " >
							<input required id="analyse" type="text" class="validate " name="analyse" value="<?= $analyse ?>"  >
							<label for="analyse">Analyse</label>
						</div>
					</div>
					<div class="row">
						<div class="input-field col s5 " >
							<input required id="cout" type="number" class="validate " name="cout" value="<?= $cout ?>"  >
							<label for="cout">Coût</label>
						</div>
					</div>
					
					<div class="input-field center-align col s5">
						<button class="btn  waves-light blue darken-4" type="submit" name="envoyer">Modifier
						<i class="material-icons right">send</i>
						</button>
					</div>
				</form>
				
			</div>
		</div>
	</body>
</html>