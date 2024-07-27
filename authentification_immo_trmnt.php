<?php
session_start();
include 'connexion.php';
if (isset($_POST['login']))
{

	$req=$db->prepare('SELECT id, prenom, nom, fonction, service, sexe  FROM personnel 
		WHERE login=? AND pwd=?  
		OR login=? AND pwd=? AND etat="activer"');
	$req->execute(array($_POST['login'], sha1($_POST['pwd']), $_POST['login'], sha1($_POST['pwd']))) or die(print_r($req->errorInfo()));
	$num_of_rows = $req->rowCount() ;
	
	if ($num_of_rows<1)
	{
		?>
		<script type="text/javascript">
		alert("Login et/ou mot de passe Incorrect");
		window.location="authentification_immo.php";
		</script>
		<?php
	}
	elseif ($num_of_rows>0)
	{
		$donnees= $req->fetch();
		$_SESSION['id']=$donnees['0'];
		$_SESSION['nom']=$donnees['2'];
		$_SESSION['prenom']=$donnees['1'];
		$_SESSION['fonction']=$donnees['3'];
		//service en tant que tel
		$_SESSION['service1']=$donnees['4'];
		$_SESSION['sexe']=$donnees['5'];
		$_SESSION['service']="immobilier";
		if (!isset($_COOKIE['login']) OR $_COOKIE['login']!=$_POST['login'])
		{
			setcookie('login',htmlspecialchars($_POST['login']),time() + 10*24*3600,null, null, false, true); //création du cookie
		}
		if($_SESSION['service1']=="immobilier" OR $_SESSION['service1']=="service general")
		{
			header("location:immobilier.php?a=a");
			
		}
		else
		{
			header("location:sante.php?a=a");
			
		}
	}
}
else
{
	?>
	<script type="text/javascript">
	alert("Login et/ou mot de passe Incorrect");
	window.location="authentification_sante.php";
	</script>
	<?php
}
?>