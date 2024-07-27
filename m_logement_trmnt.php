<?php
include 'connexion.php';
include 'supprim_accents.php';
$bailleur = htmlspecialchars(strtoupper($_POST['bailleur']));
$designation = htmlspecialchars(ucfirst(strtoupper(suppr_accents($_POST['designation']))));
$type_logement = htmlspecialchars($_POST['type_logement']);
$adresse = htmlspecialchars(strtolower(suppr_accents($_POST['adresse'])));
$pu = htmlspecialchars($_POST['pu']);
$etat = "libre";
$date_location = date('y') . "-" . date('m') . "-" . date('d');

$req = $db->prepare('UPDATE logement SET designation=?, adresse=?, id_type=?, id_bailleur=?, pu=? WHERE id=? ');
$req->execute(array($designation, $adresse, $type_logement, $bailleur, $pu, $_GET['id'])) or die(print_r($req->errorInfo()));
$nbr = 1;
if ($nbr > 0) {
?>
	<script type="text/javascript">
		alert('Logement modifié');
		window.location = "m_bailleur.php?id=" + <?= $bailleur ?>;
	</script>
<?php
} else {
?>
	<script type="text/javascript">
		alert('Erreur logement non modifié');
		window.location = "l_logement.php";
	</script>
<?php
}
?>