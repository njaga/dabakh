<?php
include 'connexion.php';

$req_l_article=$db->prepare("SELECT id_article, nbr_article FROM `vente_article` WHERE id_vente=? ");
$req_l_article->execute(array($_GET['id']));
while($donnees_l_article=$req_l_article->fetch())
{
    $req=$db->prepare("SELECT article.qt FROM article WHERE article.id=? ");
    $req->execute(array($donnees_l_article['0']));
    $donnees=$req->fetch();
    $qt=$donnees_l_article['1'] + $donnees['0'];

    $req=$db->prepare("UPDATE article SET qt=? WHERE id=? ");
    $req->execute(array($qt, $donnees_l_article['0']));
}

$req=$db->prepare("DELETE FROM vente WHERE id=? ");
$req->execute(array($_GET['id']));

//header("location:m_consultation.php?id=".$id) ;
$nbr=$req->rowCount();
if ($nbr>0) 
{
	?>
	<script type="text/javascript">
		alert("Suppression effectuée");
		window.history.go(-1);
	</script>
	<?php
};
?>