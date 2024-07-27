<?php
$formatter = \NumberFormatter::create('fr_FR', \NumberFormatter::SPELLOUT);
$formatter->setAttribute(\NumberFormatter::FRACTION_DIGITS, 0);
$formatter->setAttribute(\NumberFormatter::ROUNDING_MODE, \NumberFormatter::ROUND_HALFUP);
include 'connexion.php';
$db->query("SET lc_time_names='fr_FR';");
$req = $db->prepare("SELECT CONCAT(bailleur.prenom,' ', bailleur.nom) 
FROM caisse_caution 
INNER JOIN location on caisse_caution.id_location=location.id
INNER JOIN logement on location.id_logement= logement.id
INNER JOIN bailleur on logement.id_bailleur=bailleur.id
WHERE id_versement=?");
$req->execute(array($_GET['id']));
$donnees = $req->fetch();
$bailleur = strtoupper($donnees['0']);

$req = $db->prepare("SELECT CONCAT(day(caisse_caution.date_operation),' ', monthname(caisse_caution.date_operation),' ', year(caisse_caution.date_operation)), caisse_caution.montant FROM caisse_caution WHERE caisse_caution.id=?");
$req->execute(array($_GET['id']));
$donnees = $req->fetch();
$date_versement = $donnees['0'];
$montant_verse = $donnees['1'];
?>
<!DOCTYPE html>
<html>

<head>
    <title>Paiement caution</title>
    <?php include 'entete.php'; ?>
    <link type="text/css" rel="stylesheet" href="../css/tables-min.css" media="screen,projection" />
</head>

<body style="background-image: url('<?= $image ?>i_recu_bailleur.jpg'); font: 12pt 'times new roman';">
    <a href="" class="btn " onclick="window.print();">Imprimer</a>
    <a href="immobilier.php" class="btn ">Retour au menu</a>
    <div class="container  white" style="padding:  10px">
        <div class="row">
            <img class="col s8 offset-s2" src="../css/images/banniere_immo.png">
        </div>
        <div class="row center">
            <h3 class="col s12 center" style="margin-bottom: -8px; margin-top: -20px">
                <b>Reçu caution N°<?= str_pad($_GET['id'], 3, "0", STR_PAD_LEFT) ?></b>
            </h3>
            <p class="col s12 right-align">Imprimé le <?= date('d') . "/" . date('m') . "/" . date('Y') ?></p>
        </div>
        <div class="row">
            <h5 class="col s12" style="margin-bottom: -6px">
                Bénéficiare :<b> <?= $bailleur ?></b>
            </h5>
            <h6 class="col s3"></h6>
            <h6 class="col s6">Date du paiement : <b><?= $date_versement ?></b></h6>
        </div>
        <div class="row">
            <table class="col s12 pure-table pure-table-bordered">
                <thead>
                    <th colspan="2">Logement</th>
                    <th>&nbsp&nbsp&nbspPU&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp</th>
                    <th>Montant</th>
                </thead>
                <tbody>

                    <?php
                    $db->query("SET lc_time_names = 'fr_FR';");
                    $req = $db->prepare("SELECT CONCAT(CONCAT(locataire.prenom,' ', locataire.nom),' : ',logement.designation), location.prix_location FROM caisse_caution INNER JOIN location on caisse_caution.id_location=location.id INNER JOIN locataire on location.id_locataire=locataire.id INNER JOIN logement on location.id_logement= logement.id INNER JOIN type_logement on logement.id_type=type_logement.id INNER JOIN bailleur on logement.id_bailleur=bailleur.id WHERE id_versement=? ORDER BY logement.designation;");
                    $req->execute(array($_GET['id']));
                    $sous_total = 0;
                    $i = 0;
                    while ($donnees = $req->fetch()) {
                        $i++;
                        $designation = $donnees['0'];
                        $montant = $donnees['1'];

                        $sous_total = $sous_total + $montant;
                        echo "<tr>";
                        echo "<td  class='designation' colspan='2'><b>" . $i . ") </b> <b>" . $designation . " </b></td>";
                        echo "<td>" . number_format($montant, 0, '.', ' ') . " </td>";
                        echo "<td>" . number_format($montant, 0, '.', ' ') . " </td>";
                        echo "</tr>";
                    }


                    echo "<tr class=''>";
                    echo "<td colspan='2'><b>SOUS TOTAL LOCATIONS</td></td>";
                    echo "<td colspan='2'>" . number_format(($sous_total), 0, '.', ' ') . " </td>";
                    echo "</tr>";


                    echo "<tr class='grey '>";
                    echo "<td colspan='2'><b>TOTAL</b></td>";
                    echo "<td colspan='2'><b>" . number_format(($sous_total), 0, '.', ' ') . " </b></td>";
                    echo "</tr>";
                    ?>
                </tbody>
            </table>
        </div>
        <div class="row">
            <h6 class="col s12">
                Arrêté à la présente somme de <b><?= number_format($sous_total, 0, '.', ' ') ?> </b> Fcfa.....<b><i><?= $formatter->format($sous_total); ?> </i></b>....
            </h6>
        </div>
        <div class="row">
            <h6 class="col s6 center"><b><u>Le bénéficiare</u></b></h6>
            <h6 class="col s6 center"><b><u>L'agence</u></b></h6>
        </div>
    </div>
</body>
<script type="text/javascript">
    $(document).ready(function() {})
</script>
<style type="text/css">
    /*import du css de materialize*/
    @import "../css/tables-min.css" print;
    @import "../css/materialize.min.css" print;

    /*CSS pour la page à imprimer */
    /*Dimension de la page*/
    @page {
        size: portrait;
        margin: 0px;
        margin-bottom: 10px;
        margin-top: 1px;
    }

    @media print {

        .btn {
            display: none;
        }

        p {
            margin-top: -5px;
        }

        .row h5 {
            margin-top: -5px;
        }

        td,
        th {
            padding: initial;
            border-right: 1px solid;
            padding: 2px;
        }

        th {
            border: 1px solid;
        }

        table {
            border: 1px solid;
        }

    }

    .designation {
        text-align: left;
    }

    td {
        text-align: center;
        border: 1px solid black;
    }

    th {
        text-align: center;
        border: 1px solid black;
    }

    p {


        margin-top: -5px;
    }
</style>

</html>