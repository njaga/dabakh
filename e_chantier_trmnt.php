<?php
session_start();
include 'connexion.php';
include 'supprim_accents.php';

$id_user=$_SESSION['prenom']." ".$_SESSION['nom'];
$date_prevu_fin=NULL;
$date_debut=htmlspecialchars($_POST['date_debut']);
if(isset($_POST['date_prevu_fin']) AND $_POST['date_prevu_fin']!="")
{
    $date_prevu_fin=htmlspecialchars($_POST['date_prevu_fin']);
}

$proprietaire=htmlspecialchars($_POST['proprietaire']);
$contact=htmlspecialchars($_POST['contact']);
$cout=htmlspecialchars($_POST['cout']);
$emplacement=htmlspecialchars($_POST['emplacement']);
$travail_demande=htmlspecialchars($_POST['travail_demande']);

$req=$db->prepare('INSERT INTO `chantier`(`proprietaire`, `contact`, `date_debut`, `date_prevu_fin`,  `cout`, `emplacement`, `travail_demande`,  `id_user`) values (?,?,?,?,?,?,?,?) ');
$req->execute(array($proprietaire, $contact, $date_debut, $date_prevu_fin, $cout, $emplacement, $travail_demande, $id_user)) or die(print_r($req->errorInfo()));
$nbr=$req->rowCount();
$id=$db->lastInsertId();
if ($nbr>0) 
{
	?>
        <script type="text/javascript">
            alert('Chantier enregistré');
            window.location="l_chantier.php";
        </script>
    <?php

}
else
{
?>
<script type="text/javascript">
	alert('Erreur bailleur non enregistré');
	window.history.go(-1);
</script>
<?php
}
?>