<?php
include 'connexion.php';
$prenom=htmlspecialchars(ucfirst(strtolower($_POST['prenom'])));
$nom=htmlspecialchars(strtoupper($_POST['nom']));
$telephone=htmlspecialchars($_POST['telephone']);
$cni=htmlspecialchars($_POST['cni']);
$statut="actif";
$req=$db->prepare('INSERT INTO locataire(prenom, nom, tel, cni, statut) values (?,?,?,?,?) ');
$req->execute(array($prenom,$nom,$telephone, $cni, $statut)) or die(print_r($req->errorInfo()));
$nbr=$req->rowCount();
if ($nbr>0) {
?>
<script type="text/javascript">
	alert('Locataire enregistré');
	window.location="l_locataire_actif.php";
</script>
<?php
}
else
{
?>
<script type="text/javascript">
	alert('Erreur locataire non enregistré');
	window.history.go(-1);
</script>
<?php
}
?>