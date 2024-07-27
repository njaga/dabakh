<?php
include 'connexion.php';
session_start();
$id_user=substr($_SESSION['prenom'], 0,1).".".$_SESSION['nom'];
$id_vente=$_POST['id_vente'];
$id_article=$_POST['id_article'];
$qt=$_POST['nbr'];

//Vérification
$req_count=$db->prepare("SELECT COUNT(id) FROM `vente_article` WHERE id_vente=? AND id_article=?");
$req_count->execute(array($id_vente, $id_article));
$donnee_count=$req_count->fetch();
$nbr_count=$donnee_count['0'];


if($nbr_count<1)
{
    $req_client=$db->prepare("SELECT pu, qt from article WHERE id=?");
    $req_client->execute(array($id_article));
    $donnee=$req_client->fetch();
    $pu=$donnee['0'];
    $qt_restant=$donnee['1'] - $qt;
    
    if($qt_restant>=0)
    {
        $montant = $pu * $qt;
        $req=$db->prepare("INSERT INTO vente_article (id_article, id_vente, nbr_article, montant) VALUES (?, ?, ?, ?) ");
        $req->execute(array($id_article, $id_vente, $qt, $montant));
        
        $req=$db->prepare("UPDATE article SET qt=? WHERE id=?");
        $req->execute(array($qt_restant, $id_article));
    }

    
}
$total=0;
$req_article_vente=$db->prepare("SELECT article.article, article.pu, vente_article.nbr_article, vente_article.montant, vente_article.id  
FROM `vente_article` 
INNER JOIN article ON vente_article.id_article=article.id 
WHERE id_vente=?");
$req_article_vente->execute(array($id_vente));
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
echo '<tr class="hide"><td><input type="number" name="total"  class="total" value="'.$total.'" hidden></td></tr>';
if($qt_restant<0)
{
    ?>
    <script type="text/javascript">
        alert("Quantité saisié supérieur à la quantité restante");
    </script>
    <?php
}

?>

<script type="text/javascript">
function s_article(id)
        {
            var id_vente = $('input:first').val();
            $.ajax({
            type:'POST',
            url:'s_article_vente_ajax.php',
            data:'id='+id+'&id_vente='+ id_vente,
            success:function (html) {
                $('tbody').html(html);
            }
            });
        }
        $('.supprimer_article').click(function(){
            var id= $(this).attr('id');
            s_article(id);

        });
</script>