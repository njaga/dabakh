<?php
session_start();
include 'connexion.php';
$id_vente=$_POST['id_vente'];
$id_client=$_POST['id_client'];
$date_vente=$_POST['date_vente'];
$total=$_POST['total'];
$frais_transport=$_POST['frais_transport'];
$id_user=$_SESSION['prenom'].".".$_SESSION['nom'];

$req=$db->prepare("UPDATE vente SET date_vente=?, id_client=?, montant=?, frais_transport=?, id_user=? WHERE id=?");
$req->execute(array($date_vente, $id_client, ($total + $frais_transport), $frais_transport, $id_user, $id_vente)) or die($req->errorInfo());

$motif="Vente article(s)";
$req=$db->prepare("UPDATE caisse_commerce SET date_operation=?, montant=? WHERE id_vente_article=?");
$req->execute(array($date_vente, ($total + $frais_transport), $id_vente)) or die($req->errorInfo());
?>
<script type="text/javascript">
    alert("Vente modifiée");
    window.location="l_vente.php";
</script>