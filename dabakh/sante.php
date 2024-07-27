<?php
session_start();
$_SESSION['service']="sante";
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
<html>
	<head>
		<title>Accueil</title>
		<?php
		include 'entete.php';
		?>
	</head>
	<body style="background-image: url(<?=$image?>sante.jpg);">
		<?php include 'verification_menu_sante.php'; ?>
		 
		 <div id="sante" class="modal">
		    <div class="modal-content">
		      <h4 class="center" style="border: 2px solid black; border-radius: 52px">Mise à jour de Dabakh <b>V1.3</b></h4>
		      <h6><b>- Demandes de régularisation sur les autres soins</b></h6>
		      <h6><b>- Demandes de régularisation sur les soins à domicile</b></h6>
		      
		    </div>
		    <div class="modal-footer">
		      <a href="#!" class="modal-close waves-effect waves-green btn-flat grey black-text">Fermer</a>
		    </div>
		  </div>
	</body>
	<?php
	if (isset($_GET['a']) ) 
	{
		?>
		<script type="text/javascript">
			<?php
			if ($_SESSION['fonction']=="administrateur" OR $_SESSION['fonction']=="secretaire" OR $_SESSION['fonction']=="daf") 
			{
				?>
					//$('.modal').modal();
					//$('#sante').modal('open');
				<?php
			}
			?>
			M.toast({html: 'Bonjour <?=$_SESSION['prenom']?> <?=$_SESSION['nom']?>!', classes: 'rounded'});
		</script>
		<?php
	}
	?>
</html>