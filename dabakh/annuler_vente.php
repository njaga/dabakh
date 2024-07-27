<?php
include 'connexion.php';

$req=$db->prepare("SELECT nbr_article, id_article FROM vente_article 
WHERE id_vente=? ");
$req->execute(array($_GET['id']));
while($donnees=$req->fetch())
{
    $qt=$donnees['0'];
    $id_article=$donnees['1'];
    
    $req_qt=$db->prepare("SELECT article.qt FROM article WHERE article.id=? ");
    $req_qt->execute(array($id_article));
    $donnees_qt=$req_qt->fetch();
    $qt=$qt + $donnees_qt['0'];
    
    $req_update_qt=$db->prepare("UPDATE article SET qt=? WHERE id=? ");
    $req_update_qt->execute(array($qt, $id_article));
}

$req=$db->prepare("DELETE FROM vente_article WHERE id_vente=?");
$req->execute(array($_GET['id'])) or die($req->errorInfo());

$req=$db->prepare("DELETE FROM vente WHERE id=?");
$req->execute(array($_GET['id'])) or die($req->errorInfo());

?>
<script type="text/javascript">
    alert("Vente annulé");
    window.location="l_vente.php";
</script>