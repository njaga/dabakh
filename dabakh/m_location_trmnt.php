<?php
include 'connexion.php';

$date_debut = htmlspecialchars($_POST['date_debut']);
$caution = htmlspecialchars($_POST['caution']);
$montant_mensuel = htmlspecialchars($_POST['montant_mensuel']);
$req = $db->prepare('UPDATE location SET date_debut=?, caution=?, prix_location=? WHERE id=?');
$req->execute(array($date_debut, $caution, $montant_mensuel, $_GET['id'])) or die(print_r($req->errorInfo()));
$nbr = 1;
if ($nbr > 0) {
?>
	<script type="text/javascript">
		alert('Location modifiée');
		window.location = "l_location.php";
	</script>
<?php
} else {
?>
	<script type="text/javascript">
		alert('Erreur location non modifiée');
		window.location = "l_location.php";
	</script>
<?php
}
?>