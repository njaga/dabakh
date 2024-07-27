<?php
session_start();
include 'connexion.php';
include 'supprim_accents.php';
$id_planning=htmlspecialchars($_GET['id']);
$compte_rendu=htmlspecialchars($_POST['compte_rendu']);
$id_user=$_SESSION['prenom'].".".$_SESSION['nom'];

$req=$db->prepare('UPDATE  planning_recouvrement SET compte_rendu=?, id_user_compte_rendu=? WHERE id=? ');
$req->execute(array($compte_rendu, $id_user, $id_planning)) or die(print_r($req->errorInfo()));
$donnee=$req->fetch();
$nbr=$donnee['0'];
?>
<script type="text/javascript">
    window.history.go(-1);
</script>
 
