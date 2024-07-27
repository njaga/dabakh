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
$req=$db->prepare('SELECT * FROM article WHERE id=?');
$req->execute(array($_GET['id']));
$donnees=$req->fetch();
$id=$donnees['0'];
$article=$donnees['1'];
$pu=$donnees['2'];
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Modificarion article</title>
		<?php include 'entete.php'; ?>	
	</head>
	<body>
		<?php
		include 'verification_menu_cm.php';
		?>
		<div class="container white">
				<form method="POST" class="row" action="m_article_trmnt.php?id=<?php echo $_GET['id'] ?>"   >
					
					<h3 class="center-align col s12 m12" >Modificarion d'un article </h3>
						<div class="input-field col s5 m5 " >
							<input required id="article" type="text" class="validate " name="article" value="<?= $article ?>"  >
							<label for="article">Article</label>
						</div>
						<div class="input-field col s5 m5 " >
							<input required id="pu" type="number" class="validate " name="pu" value="<?= $pu ?>"  >
							<label for="pu">Prix Unitaire</label>
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