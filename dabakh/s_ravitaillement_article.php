<?php
include 'connexion.php';


if(isset($_GET['a_qt']))
{
	$req=$db->prepare("UPDATE article SET qt=? WHERE id_ravitaillement=? ");
	$req->execute(array($_GET['a_qt'], $_GET['id']));
}

$req=$db->prepare("DELETE FROM ravitaillement_article WHERE id=? ");
$req->execute(array($_GET['id']));
	

?>
<script type="text/javascript">
	alert("Suppression effectuée");
	window.history.go(-1);
</script>
<?php

?>