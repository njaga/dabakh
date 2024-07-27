<?php
include 'connexion.php';
$req=$db->prepare("DELETE FROM vente_produit WHERE id_consultation=? AND id_produit=?");

?>