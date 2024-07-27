<!DOCTYPE html>
<html>
	<head>
		<title>Authentification</title>
		<?php include 'entete.php'; ?>
	<body style="background-image: url(<?= $image ?>bgaccueil.jpg);">
		<div class="row" >
			<div class="col s12">
				<a  class="waves-light blue darken-4 hoverable btn" href="index.php"><i class="material-icons left">arrow_back</i>Retour
				</a>
			</div>
		</div>
		<div class="container white" style="border-radius: 50px">
			<div class="row">
				<div class="col s12 m8 offset-m2 " id="index_form">
					<form method="POST" class="row" action="authentification_immo_trmnt.php">
						<h3 class="center-align" >Authentification Immobilier</h3>
						<div class="input-field col s12" >
							<i class="material-icons prefix ">account_circle</i>
							<input id="login" type="text" class="validate" name="login" >
							<label for="login">Login</label>
						</div>
						<div class="input-field col s12 ">
							<i class="material-icons prefix">vpn_key</i>
							<input id="password" type="password" class="validate"  name="pwd" >
							<label for="password">Mot de passe</label>
						</div>
						<div class="input-field col s12">
							<button class="btn waves-light blue darken-4  hoverable right" type="submit"  name="connexion" ><i class="material-icons right" >send</i>Connexion</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</body>
	<script type="text/javascript">
		function surligne(champ, erreur) {
	if (erreur) {
		champ.setAttribute("class", "erreur");
	}
	else{
		champ.removeAttribute("class");
	}
	}
	function verifNombre(champ) {
	var nombre = parseInt(champ.value);
	if(isNaN(nombre))
	{
	alert(nombre);
	surligne(champ, true);
	return false;
	}
	else
	{
	surligne(champ, true);
	return true;
	}
	}
	</script>
	<style type="text/css">
	/* icon prefix focus color */
	.input-field .prefix.active {
	color: #0d47a1 ;
	}
	</style>
</html>