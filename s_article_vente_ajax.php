<?php
include 'connexion.php';
if(isset($_POST['id']))
{
    $req=$db->prepare("SELECT nbr_article, id_article FROM vente_article 
    WHERE id=? ");
    $req->execute(array($_POST['id']));
    $donnees=$req->fetch();
    $qt=$donnees['0'];
    $id_article=$donnees['1'];

    $req=$db->prepare("DELETE FROM vente_article WHERE id=? ");
    $req->execute(array($_POST['id']));
    
    $req=$db->prepare("SELECT article.qt FROM article WHERE article.id=? ");
    $req->execute(array($id_article));
    $donnees=$req->fetch();
    $qt=$qt + $donnees['0'];

    $req=$db->prepare("UPDATE article SET qt=? WHERE id=? ");
    $req->execute(array($qt, $id_article));

    $total=0;
    $req_article_vente=$db->prepare("SELECT article.article, article.pu, vente_article.nbr_article, vente_article.montant, vente_article.id  
    FROM `vente_article` 
    INNER JOIN article ON vente_article.id_article=article.id 
    WHERE id_vente=?");
    $req_article_vente->execute(array($_POST['id_vente']));
    while($donnees=$req_article_vente->fetch())
    {
        $total=$total+$donnees['3'];
        echo"<tr>";
            echo "<td>".$donnees['0']."</td>";
            echo "<td>".number_format($donnees['1'],'0',',',' ')."</td>";
            echo "<td>".$donnees['2']."</td>";
            echo "<td>".number_format($donnees['3'],'0',',',' ')."</td>";
            echo "<td class=''> <a class='red-text btn red white-text tooltipped supprimer_article' data-position='top' data-delay='50' 
                data-tooltip='Supprimer' id='".$donnees['4']."' 
                onclick='return(confirm(\"Voulez-vous supprimer cet article ?\"))'>X</a> </td>";
        echo"</tr>";
    }
    echo "<tr class='grey'>";
        echo "<td colspan='2' class='center'><b>TOTAL</b></td>";
        echo "<td class='center' colspan='2'><b>".number_format($total,'0',',',' ')."Fcfa</b></td>";
    echo "</tr>";
}

?>