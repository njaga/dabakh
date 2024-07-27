<?php
session_start();
include 'connexion.php';
include 'supprim_accents.php';
$id_planning=htmlspecialchars($_GET['id_planning']);
$id_locataire=htmlspecialchars($_GET['id']);
$id_user=$_SESSION['prenom'].".".$_SESSION['nom'];

$req=$db->prepare('SELECT COUNT(*) FROM planning_recouv_locataire WHERE id_locataire=? AND id_planning=? ');
$req->execute(array($id_locataire, $id_planning)) or die(print_r($req->errorInfo()));
$donnee=$req->fetch();
$nbr=$donnee['0'];
if($nbr>0)
{
    ?>
    <script type="text/javascript">
    alert("Locataire déjà ajouté au planning");
        window.history.go(-1);
    </script>
    <?php 
    exit();
}
else
{
    $req=$db->prepare('INSERT INTO planning_recouv_locataire(id_locataire, id_planning, id_user) values (?, ?, ?) ');
    $nbr=$req->execute(array($id_locataire, $id_planning, $id_user)) or die(print_r($req->errorInfo()));
    if ($nbr>0) {
    ?>
    <script type="text/javascript">
        window.history.go(-1);
    </script>
    <?php
    }
    else
    {
    ?>
    <script type="text/javascript">
        window.history.go(-1);
    </script>
    <?php
    }

}
?>