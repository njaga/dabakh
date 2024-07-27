<?php
$formatter = \NumberFormatter::create('fr_FR', \NumberFormatter::SPELLOUT);
$formatter->setAttribute(\NumberFormatter::FRACTION_DIGITS, 0);
$formatter->setAttribute(\NumberFormatter::ROUNDING_MODE, \NumberFormatter::ROUND_HALFUP);
include 'connexion.php';

$db->query("SET lc_time_names='fr_FR';");

$req = $db->prepare('SELECT * FROM `contrat` WHERE id=?');
$req->execute(array(2));
$donnees = $req->fetch();


$req = $db->prepare('SELECT  location.id, type_logement.type_logement, logement.adresse, CONCAT(day(location.date_debut)," ",monthname(location.date_debut)," ",year(location.date_debut)), location.prix_location, CONCAT(bailleur.prenom," ",bailleur.nom), CONCAT(locataire.prenom," ", locataire.nom), location.caution, CONCAT(day(now())," ",monthname(now())," ",year(now())), location.type_contrat, location.id_user, locataire.cni, locataire.tel, logement.designation, CONCAT(day(DATE_ADD(location.date_debut, INTERVAL 1 YEAR))," ",monthname(DATE_ADD(location.date_debut, INTERVAL 1 YEAR))," ",year(DATE_ADD(location.date_debut, INTERVAL 1 YEAR)))
FROM `location`, logement, bailleur, locataire, type_logement 
WHERE logement.id_bailleur=bailleur.id AND location.id_logement=logement.id AND logement.id_type=type_logement.id AND location.id_locataire=locataire.id AND location.id=?
ORDER BY location.date_debut DESC');
$req->execute(array($_GET['id']));
$donnees = $req->fetch();
$id = $donnees['0'];
$designation = $donnees['1'];
$adresse = $donnees['2'];
$date_debut = $donnees['3'];
$pu = $donnees['4'];
$bailleur = $donnees['5'];
$locataire = $donnees['6'];
$caution = $donnees['7'];
$date_actuelle = $donnees['8'];
$type_location = $donnees['9'];
$id_user = $donnees['10'];
$cni = $donnees['11'];
$tel = $donnees['12'];
$designation_logement = $donnees['13'];
$date_fin = $donnees['14'];
?>
<!DOCTYPE html>
<html>

<head>
    <title>Location du <?= $date_debut ?></title>
    <?php include 'entete.php'; ?>
</head>

<body style="background-image: url(<?= $image ?>i_mensualite.png);">
    <a href="" class="btn " onclick="window.print();">Imprimer</a>
    <a onclick="window.history.go(-1)" class="btn ">Retour</a>


    <div class="container white">
        <div class="row center white" style="margin-bottom: 1px">
            <img class="col s8 offset-s2" src="css/images/banniere_immo.png">
            <p class="col s6 left-align" style="font-family: 'times new roman'; font-size: 8px">Imprimé le <?= $date_actuelle ?></p>
        </div>
        <div class="row">
            <div class="col s12" style="font: 12pt 'times new roman';">
                <h5 class="col s8 center offset-s2" style="border:1px solid"><b>CONTRAT DE LOCATION A USAGE D'HABITATION </b>
                </h5>
                <div class="col s12" style="font: 12pt 'times new roman';">
                    <br>
                    <br>
                    ENTRE LES SOUSSIGNES :
                    <br>
                    <br>
                    La <b>Société Carré d'Or SAS</b> inscrite au RCCM sous le <b>N° SN-DKR-2023-B-8664</b>, prise en la personne de son représentant légal, <b>M. Ndiaga Ndiaye</b> le Directeur réseau
                    <br>
                    Siège social : <b>Cité Keur Gorgui, Dakar</b> Tel : <b>+221 33 867 77 33</b>.
                    <br>
                </div>
                <div class="col s10 right-align">
                    <b>Le Propriétaire</b> d’une part,
                    <br>
                    <br>
                </div>
                <div class="col s12">Et</div>
                <br>
                <br>
                <div class="col s12" style="font: 12pt 'times new roman';">
                    M <b><?= $locataire ?></b>
                    <br>
                    Tel : <b><?= $tel ?></b>
                    <br>
                    Titulaire de la CIN : <b><?= $cni ?></b>
                    <br>
                </div>
                <div class="col s10 right-align">
                    <b>La Preneur </b> d’autre part,
                    <br>
                </div>
                <div class="col s10 center">
                    <br>
                    IL A ÉTÉ CONVENU ET ARRETE CE QUI SUIT :
                    <br>
                    <br>
                </div>
                <div class="col s12">
                    <b><u>ARTICLE 1er : OBJET DU BAIL</u></b>
                    <br>
                    Ce bail est à usage d’habitation.
                    <br>
                    Le bailleur donne bail à loyer pour le temps aux charges et conditions ci-après fixées au preneur qui accepte les locaux dont la désignation suit :
                    <br>
                    <br>
                    <b><u>ARTICLE 2: DESIGNATION</u></b>
                    <br>
                    <b><?= $designation_logement ?></b> situé à <b><?= $adresse ?></b>
                    <br>
                    Le preneur déclare connaitre le local pour l’avoir visité en bon état, sans qu’il soit besoin d’en établir une description plus détaillée et consent à l’occuper en bon responsable sans aucune réserve.
                    <br>
                    <br>
                    <b><u>ARTICLE 3 : DUREE DU BAIL.</u></b>
                    <br>
                    Le présent bail est consenti et accepté pour une durée de un (1) ans, renouvelable. Il prend effet à compter du <b><?= $date_debut ?></b> pour se terminer le <b><?= $date_fin ?></b>.
                    <br>
                    S’il ne souhaite pas bénéficier de la reconduction, le preneur devra servir au bailleur un préavis de congé de trois (3) mois avant l’expiration du bail.
                    <br>
                    Le bailleur aura la faculté de refuser le renouvellement du contrat en notifiant au preneur un préavis de six (6) mois avant l’expiration du bail, conformément aux dispositions de l’article 574 du COCC.
                    <br>
                    <br>
                    <br>
                    <br>
                    <br>
                    <br>
                    <br>
                    <br>
                    <br>
                    <br>
                    <b><u>ARTICLE 4 : CONDITIONS FINANCIERES.</u></b>
                    <br>
                    <b>4-a</b> : Loyer mensuel
                    <br>
                    Le présent bail est consenti moyennant un loyer mensuel de <b><?= $pu ?></b> F TTC, payable avant le cinq (05) de chaque mois entamé, ainsi décomposé :
                    <div class="row">
                        <table class=" ">
                            <tr>
                                <td>Loyer HT </td>
                                <td><b><?= ($pu * 96.4) / 100 ?></b> F/mois</td>
                            </tr>
                            <tr>
                                <td>TOM à reverser au bailleur 3.6%</td>
                                <td><b><?= ($pu * 3.6) / 100 ?></b> F/mois</td>
                            </tr>
                            <tr>
                                <td>Total loyer TTC</td>
                                <td><b><?= $pu ?></b> F/mois</td>
                            </tr>
                        </table>
                    </div>
                    <br>
                    Tout mois entamé est dû.
                    <br>
                    <b>4-b</b>- Retard paiement
                    <br>
                    <br>
                    Chaque jour de retard est passible de frais d'un montant de <b>deux mille cinq cent (2.500)</b> par jour. Tous les paiements doivent être effectués au siege de l'agence par chèque, espèce ou virement (dans ce cas le numéro de compte vous sera communiqué) <b>au plus le 10 de chaque mois</b>.
                    <br>
                    <b>4-c</b>- Caution de garantie
                    <br>
                    Le preneur versera la somme de <b><?= $pu ?></b> FCFA , représentant un (1) mois de caution, non productible d’intérêts pour la garantie de l’entretien et des réparations locatives incombant au preneur.
                    <br>
                    La caution sera restituée à la fin du bail, après l’état des lieux contradictoire constatant la remise en parfait état locatif des lieux loués et dans tous les cas, sur présentation de quitus SENELEC, SEN EAU, SONATEL suivie de la remise des clés des locaux.
                    <br>
                    <br>

                    <b><u>ARTICLE 5 : CHARGES ET CONDITIONS.</u></b>
                    <br>
                    La présente location est consentie et acceptée sous les charges et conditions que les parties s’engagent à respecter sous peine de résiliation du contrat.
                    <br>
                    <b>a- </b>Droits et obligations du preneur
                    <br>
                    <br>
                    <b>a. 1</b> : Le preneur s’engage à supporter intégralement tous les frais d’huissier, honoraires des avocats et autres débours qui seront engagés dans le cadre des procédures de recouvrement des loyers impayés et indemnités d’occupation et lors de l’exécution des décisions de justice qui seront rendues.
                    <br>
                    <b>a. 2</b> : : Le preneur doit entretenir les lieux loués en bon responsable, étant précisé qu’ils lui seront remis en bon état lors de son entrée en jouissance. Il ne pourra rien changer dans la disposition ou dans l’affectation des lieux loués sans le consentement préalable du bailleur donné par écrit. Tous aménagements, embellissements, améliorations appartiendront de plein droit au bailleur à la fin du contrat sans aucune indemnité.
                    <br>
                    <b>a. 3</b> : Il incombera au preneur l’entretien et les réparations locatives courantes, notamment : le débouchage des tuyaux de descente, de fuite d’eau, sauf en ce qui concerne les grosses réparations.
                    <br>
                    <b>a. 4</b> : Il est formellement interdit au preneur de céder ou de sous louer en tout ou en partie son droit au bail sans l'autorisation écrite du bailleur.
                    <br>
                    <b>a. 5</b> : Le preneur s’engage à souscrire une police d’assurance pour assurer ses risques locatifs et de voisinages afin de garantir le recours du propriétaire en cas d’incendie ou de dégâts des eaux. Il s’acquittera régulièrement de la prime d’assurance et devra présenter la quittance correspondante sur simple demande du bailleur.
                    <br>
                    <b>a. 6</b> : Le preneur s’acquittera de ses factures d’électricité, d’eau, de téléphone, ainsi que toutes ses taxes, droits d’enregistrement, charges de voirie, de police et d’hygiène afin qu’aucun recours ne puisse être exercé à cet égard contra le bailleur.
                    <br>
                    Il remettra un exemplaire du contrat enregistré au bailleur.
                    <br>
                    <b>a. 7</b> : Le preneur acceptera lorsqu’il aura reçu ou donné congé, que le bailleur puisse mettre un écriteau à l’emplacement de son choix, indiquant que les lieux sont à louer. Le preneur ne pourra s’opposer aux visites pendant les jours ouvrables sur les rendez-vous.
                    <br>
                    <b>a. 8</b> : Conformément à la loi, un état des lieux contradictoire sera établi lors de la remise des clés au locataire et lors de leur restitution. A défaut il pourra être établi par huissier à l’initiative de la partie la plus diligente, les frais étant partagés de façon équitable.
                    <br>
                    <b>a.</b> Droits et obligations du bailleur :
                    <br>
                    <b>b.1 :</b> le bailleur se réserve le droit d’entrer dans les lieux loués pour effectuer les réparations nécessaires, pour autant qu’une telle entrée soit faite aux dates et heures préalablement fixées d’un commun accord.
                    <br>
                    <b>b. 2</b> : Le bailleur mettra à la disposition du preneur les installations pour l’eau, l’électricité, la plomberie et l’évacuation des eaux usées et prendra toutes les dispositions requises contre les troubles de jouissance.
                    <br>
                    <b>b. 3</b> : Le bailleur sera responsable à ses frais des gros entretiens et réparations des murs, plafond, toits, sols, plomberie et installations annexes.
                    <br>
                    <b>b.4 :</b> Le bailleur s’engage à prendre toutes les diligences pour les réparations lui incombant dans les meilleurs délais après notification écrite faite par le preneur.
                    <br>
                    <b>b. 5</b> : En cas d’impérieuse nécessité, le preneur pourra entreprendre ces réparations à ses frais après approbation du devis par le bailleur. Dans ce cas, les frais seront considérés comme avance sur le loyer ou remboursés intégralement par le bailleur ou déduits des loyers ultérieurs
                    <br>
                    <br>

                    <b><u>ARTICLE 6 : CLAUSE RESOLUTOIRE</u></b>
                    <br>
                    À défaut de paiement du loyer à son échéance, ou en cas d’inexécution d’une clause ou condition du contrat, la présente location sera résiliée de plein droit un mois après une mise en demeure par acte extra judiciaire restée sans effet.
                    <br>
                    <br>
                    <b><u>ARTICLE 7 : REGLEMENT DES LITIGES</u></b>
                    <br>
                    En cas de litige et à défaut de solution amiable, l’affaire sera portée devant les Juridictions compétentes.
                    <br>
                    <br>

                    <b><u>ARTICLE 8 : ELECTION DE DOMICILE</u></b>
                    <br>
                    Pour l’exécution des présentes, les parties font élection de domicile, pour le preneur dans les lieux loués et pour le bailleur à l’adresse sus indiquée.

                </div>

            </div>
            <h6 class="col s12 right-align ">
                Fait le <b><?= $date_debut ?></b>
            </h6>
            <h6 class="col s6 left-align center"><b>Visas du locataire <br><br><br>Lu et approuvé</b></h6>
            <h6 class="col s6 right-align"><b>Visa du cabinet</b></h6>
        </div>

    </div>
</body>
<script type="text/javascript">
    $(document).ready(function() {})
</script>
<style type="text/css">
    /*import du css de materialize*/
    @import "css/materialize.min.css" print;

    /*CSS pour la page à imprimer */
    /*Dimension de la page*/
    @page {
        size: A4 portrait;
        margin-top: 25px;
        margin-bottom: 25px;

    }

    @media print {
        .btn {
            display: none;
        }

        td,
        th {
            padding: initial;
            border-right: 1px solid;
            padding: 2px;
        }

    }

    body {
        font-family: 'times new roman';
    }
</style>

</html>