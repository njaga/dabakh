<?php
session_start();
include 'connexion.php';
include 'supprim_accents.php';
$section = "";
$id = htmlspecialchars($_GET['id']);
$montant = htmlspecialchars($_GET['montant']);
$bailleur = htmlspecialchars($_GET['b']);
$type = "sortie";
$section = "Versement caution";
$motif = "Versement caution " . $bailleur;
$id_user = $_SESSION['prenom'] . " " . $_SESSION['nom'];
$date_operation = date('Y-m-d');
$pj = 0;
$req = $db->prepare('INSERT INTO caisse_caution(type, section, motif, montant,date_operation,  id_user) values (?,?,?,?,?,?) ');
$nbr = $req->execute(array($type, $section, $motif, $montant, $date_operation, $id_user)) or die(print_r($req->errorInfo()));
$id_caisse_caution = $db->lastInsertId();
$pj = 0;
//Insertion dans la caisse immo
$req = $db->prepare('INSERT INTO caisse_immo(type, section, motif, montant,date_operation, id_caisse_caution, pj, id_user) values (?,?,?,?,?,?,?,?) ');
$nbr = $req->execute(array($type, $section, $motif, $montant, $date_operation, $id_caisse_caution, $pj, $id_user)) or die(print_r($req->errorInfo()));

//recupération des locations en question
$req_location = $db->prepare("SELECT caisse_caution.id 
FROM `caisse_caution` 
INNER JOIN location ON caisse_caution.id_location=location.id
INNER JOIN logement ON location.id_logement=logement.id
INNER JOIN bailleur ON logement.id_bailleur=bailleur.id
WHERE bailleur.etat='activer'  AND bailleur.id=?");
$req_location->execute(array($id)) or die(print_r($req->errorInfo()));
while ($donnees = $req_location->fetch()) {
    $id_caution = $donnees['0'];
    $req_update = $db->prepare("UPDATE `caisse_caution` SET id_versement=? 
    WHERE id=?");
    $req_update->execute(array($id_caisse_caution, $id_caution)) or die(print_r($req->errorInfo()));
}

if ($nbr > 0) {
?>
    <script type="text/javascript">
        alert('Opération enregistrée');
        window.location = "i_caution.php?id=" + <?= $id_caisse_caution ?>;
    </script>
<?php
} else {
?>
    <script type="text/javascript">
        alert('Erreur operation non enregistrée');
        window.history.go(-1);
    </script>
<?php
}
?>