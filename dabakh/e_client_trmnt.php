<?php
session_start();
include 'connexion.php';
include 'supprim_accents.php';
$id_user=$_SESSION['prenom']." ".$_SESSION['nom'];
$prenom=htmlspecialchars(ucfirst(strtolower(suppr_accents($_POST['prenom']))));
$nom=htmlspecialchars(strtoupper(suppr_accents($_POST['nom'])));
$telephone=htmlspecialchars($_POST['telephone']);
$adresse=htmlspecialchars($_POST['adresse']);
$sexe=htmlspecialchars($_POST['sexe']);

$req=$db->prepare("INSERT INTO client (prenom, nom, telephone, adresse, sexe, etat, id_user) VALUES(?, ?, ?, ?, ?, 1, ?)");
$req->execute(array($prenom, $nom, $telephone, $adresse, $sexe , $id_user)) or die($req->errorInfo());
$id=$db->lastInsertId();
header("location:e_vente.php?id=".$id);
?>