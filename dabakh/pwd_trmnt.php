<?php
session_start();

include 'connexion.php';
$password = htmlspecialchars(sha1($_POST['new_password']));
$req = $db->prepare('UPDATE personnel SET pwd=? WHERE id=?');
$nbr = $req->execute(array($password, $_GET['id'])) or die(print_r($req->errorInfo()));
if ($nbr > 0) {
?>
	<script type="text/javascript">
		alert('Modification réussi');
		window.location = 'immobilier.php';
	</script>
<?php
}
?>