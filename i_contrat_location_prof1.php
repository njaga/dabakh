<?php
$formatter = \NumberFormatter::create('fr_FR', \NumberFormatter::SPELLOUT);
$formatter->setAttribute(\NumberFormatter::FRACTION_DIGITS, 0);
$formatter->setAttribute(\NumberFormatter::ROUNDING_MODE, \NumberFormatter::ROUND_HALFUP);
include 'connexion.php';
$db->query("SET lc_time_names='fr_FR';");


$req = $db->prepare('SELECT  location.id, type_logement.type_logement, logement.adresse, CONCAT(day(location.date_debut)," ",monthname(location.date_debut)," ",year(location.date_debut)), location.prix_location, CONCAT(bailleur.prenom," ",bailleur.nom), CONCAT(locataire.prenom," ", locataire.nom), location.caution, CONCAT(day(now())," ",monthname(now())," ",year(now())), location.type_contrat, location.id_user, locataire.cni, locataire.tel, logement.designation, CONCAT(day(DATE_ADD(location.date_debut, INTERVAL 1 YEAR))," ",monthname(DATE_ADD(location.date_debut, INTERVAL 1 YEAR))," ",year(DATE_ADD(location.date_debut, INTERVAL 1 YEAR))), locataire.prenom, locataire.nom
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
$bailleur = ucfirst($donnees['5']);
$locataire = $donnees['6'];
$caution = $donnees['7'];
$date_actuelle = $donnees['8'];
$type_location = $donnees['9'];
$id_user = $donnees['10'];
$cni = $donnees['11'];
$tel = $donnees['12'];
$designation_logement = $donnees['13'];
$date_fin = $donnees['14'];
$societe = $donnees['15'];
$representant = $donnees['16'];

$nbr_mois = $caution / $pu;

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
                <h6 class="col s10 center offset-s1" style="border:1px solid"><b>CONTRAT DE LOCATION À USAGE PROFESSIONNEL <br>
                        (DUREE DETERMINEE) </b>
                </h6>
                <div class="col s12" style="font: 12pt 'times new roman';">
                    <br>
                    <br>
                    ENTRE LES SOUSSIGNES :
                    <br>
                    <br>
                    <b><?= $bailleur ?></b> représenté par la <b>Société Carré d'Or SAS</b> inscrite au RCCM sous le <b>N° SN-DKR-2023-B-8664</b>, prise en la personne de son représentant légal, <b>M. Ndiaga Ndiaye</b> le Directeur réseau
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
                    La Société <b><?= $societe ?></b>immatriculée au Registre du Commerce et du Crédit Mobilier de ladite ville sous le numéro <b><?= $cni ?></b> représentée par <b><?= $representant ?></b>
                    <br>
                    Tel : <b><?= $tel ?></b>
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
                    Les lieux loués sont destinés à usage professionnel régi par les dispositions de l’Acte Uniforme portant sur le Droit Commercial Général.
                    <br>
                    Le bailleur donne bail à loyer pour le temps aux charges et conditions au preneur qui accepte un <b><?= $type_location ?></b> situé à <b><?= $adresse ?></b> , composé de <b><?= $designation ?></b> .
                    <br>
                    Sans qu’il soit nécessaire de désigner les lieux plus amplement, le preneur reconnait par la présente, prendre les lieux loués en bon état et s’engage en conséquence à les rendre au moment de son départ, en parfait état d’entretien.

                    Un état des lieux contradictoire sera établi lors de la remise des clés au locataire et lors de leur restitution. À défaut, il pourra être établi par constat d’huissier à l’initiative de la partie la plus diligente, les frais étant partagés de façon équitable.

                    <br>
                    <br>
                    <b><u>ARTICLE 2: DUREE ET RENOUVELLEMENT DU CONTRAT</u></b>
                    <br>
                    Le présent contrat est conclu pour une durée de 01 ans. Il prend effet à compter du <b><?= $date_debut ?></b> pour se terminer le <b><?= $date_fin ?></b>.
                    <br>
                    Dans le cas où le preneur aura droit au renouvellement du bail en vertu de l’article 123 de l’AUDGC, il pourra demander le renouvellement de celui-ci, par signification d’huissier en justice ou notification par tout moyen permettant d’établir la réception par les destinataire au plus tard trois (3) mois avant l’expiration du bail. A défaut il sera déchu de son droit au renouvellement du bail.
                    <br>
                    Le bailleur pourra s’opposer au renouvellement du bail s’il justifie d’un motif grave et légitime à l’encontre du preneur sans aucune indemnité. Lorsque le bail est rompu dans des conditions qui donnent droit à l’indemnité d’éviction, les parties ont décidé de fixer d’avance ladite indemnité à la somme de <b><?= $pu ?></b> F CFA.
                    <br>
                    A défaut de renouvellement du bail par le preneur, le bailleur pourra faire mettre un écriteau à l’emplacement de son choix, indiquant que les lieux sont à louer. Le preneur devra laisser visiter tous les jours ouvrables sur les rendez-vous.
                    <br>
                    Le bailleur aura la faculté de refuser le renouvellement du contrat en notifiant au preneur un préavis de six (6) mois avant l’expiration du bail.

                    <br>
                    <br>
                    <b><u>ARTICLE 3 : MONTANT DU LOYER.</u></b>
                    <br>
                    La location est consentie au prix de <b><?= $pu ?></b> F CFA TTC (comme détaillé ci-dessous), payable par terme mensuel et d’avance au plus trad le 05 de chaque mois entamé
                    <br>
                    <br>
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
                    <br>
                    Le loyer est payable dans les bureaux de la Société Carré d'Or SAS ayant son siège social à Médina Rue 17x20. Tout mois commencé est dû dans son intégralité.
                    <br>
                    Le loyer peut être réglé par virement dans le compte ci-dessous et une quittance de loyer ne sera délivrée qu’après réception du bordereau de virement : (coordonnées bancaires)
                    <br>
                    <br>

                    <b><u>ARTICLE 5 : OBLIGATIONS DES PARTIES</u></b>
                    <br>
                    <b><u>OBLIGATIONS DU PRENEUR : </u></b>
                    <br>
                    <b>a- Paiement du loyer</b>
                    <br>
                    Le preneur s’engage de payer le loyer avant le terme convenu, par versement entre les mains de la société immobilière SARL ou par virement bancaire.
                    <br>
                    <br>
                    <b> b- Prise en charge des frais de procédures de recouvrement.</b>
                    <br>
                    <br>
                    Le preneur s’engage à supporter intégralement tous les frais d’huissier, honoraires des avocats et autres débours qui seront engagés dans le cadre des procédures de recouvrement des loyers impayés et indemnités d’occupation et lors de l’exécution des décisions de justice qui seront rendues.
                    <br>
                    <br>
                    <b> c- Usage conforme des lieux</b>
                    Ayant reçu et signé l’état des lieux contradictoire joint en annexe, le preneur s’oblige d’exploiter les locaux donnés à bail en bon responsable, conformément à la destination prévue à cet effet.
                    <br>
                    Il est en outre tenu de ne pas transformer, sans l’accord express et écrit du bailleur, les lieux loués et ses aménagements.
                    <br>
                    <b>d- Entretien des lieux</b>
                    <br>
                    <br>
                    Le preneur est tenu de prendre en charge l’entretien courant les lieux loués (installations électriques, sanitaires…), et les réparations locatives, notamment les frais de débouchage de toutes les canalisations sauf en ce qui concerne les grosses réparations.
                    <br>
                    <br>

                    <b> e- Paiement de factures et des charges locatives</b>
                    <br>
                    Le prendre s’acquittera pendant toute la durée du contrat les factures d’électricité, d’eau, de la SONATEL, ainsi que toutes les charges de voirie de police et d’hygiène dont les locataires sont tenus afin qu’aucun recours ne puisse être exercé à cet égard contre la société immobilière ou le propriétaire.
                    <br>
                    <br>
                    <b><u> OBLIGATIONS DU BAILLEUR.</u></b>
                    <br>
                    Le bailleur est tenu de livrer les locaux en bon état. Cette obligation s’étend également aux accessoires indispensables.
                    <br>
                    <b>a- Obligation de réparation</b>
                    <br>
                    Le bailleur fait procéder, à ses frais, dans les locaux donnés à bail à toutes les grosses réparations devenues nécessaires et urgentes.
                    Les grosses réparations sont notamment celles des gros murs, des voutes, des poutres, des toitures, des murs de clôtures et des branchements à l’égout.
                    <br>
                    Le montant du loyer est alors diminué en proportion du temps et de l’usage pendant lequel le preneur a été privé de l’usage des locaux.
                    <br>
                    Le preneur peut entreprendre les réparations, aux frais du bailleur après approbation du devis ce dernier. À ce sujet, seront considérées comme avance sur le loyer ou seront remboursées directement par le bailleur ou déduites des loyers ultérieurs
                    <br>
                    <br>
                    <b>b- Obligation de garantie</b>
                    <br>
                    Le bailleur est responsable envers le preneur du trouble de jouissance survenu de son fait ou du fait de ses préposés.
                    <br>
                    <br>
                    <b>c- Modification du bail</b>
                    <br>
                    Le bailleur ne peut, de son seul gré, ni apporter des changements à l’état des locaux donnés en bail, ni en restreindre l’usage.


                    <br>
                    <br>
                    <b><u>ARTICLE 6 : CAUTION. </u></b>
                    <br>
                    Une somme de <b><?= $caution ?></b> F CFA équivalente à <b><?= $nbr_mois ?></b> mois de loyer TTC devra être versée par le preneur à titre de caution du contrat de bail contre quittance.
                    <br>
                    <br>
                    Cette somme non productive d’intérêt, ne sera remboursée qu’après :
                    <br>

                    &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp - avoir restitué les lieux loues en parfaite état locatif notamment : la révision des installations de plomberie, d’électricité, de la réfection des peintures et dans tous les cas après constat de remise des lieux en parfaite état et la réception des clés ;
                    <br>
                    &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp- avoir présenté un quitus de la SENELEC, de SEN EAU, de la SONATEL par le preneur ;
                    <br>
                    &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp- avoir résilié son contrat d’électricité.
                    <br>

                    A défaut, il sera prélevé sur ladite caution les sommes correspondantes aux frais de remise en état des lieux, ainsi que le montant des factures d’eau, d’électricité et de téléphone non réglés. Il demeure bien entendu que ces formalités de remise en état et réalisation des travaux devront être effectués avant la fin du bail.

                    <br>
                    <br>

                    <b><u>ARTICLE 7 : CLAUSE RESOLUTOIRE.</u></b>
                    <br>
                    Le preneur et le bailleur sont tenu chacun en ce qui concerne au respect de chacune des clauses et conditions du bail sous peine de résiliation.
                    <br>
                    <br>
                    À défaut de paiement d’un seul terme du loyer à son échéance, ou d’inexécution d’une seule clause ou condition du contrat, le bailleur pourra demander à la juridiction compétente la résiliation du bail et l’expulsion du preneur ou de tout occupant de son chef après avoir servi par acte extra-judiciaire une mise en demeure d’avoir à respecter les clauses et conditions du bail conformément à l’Acte Uniforme sur le Droit Commercial Général.
                    <br>
                    <br>

                    Le bail peut aussi être résilié à l’initiative du preneur, avant son terme, en cas de non-respect par le bailleur de ses obligations contractuelles, sous réserves d’une mise en demeure d’avoir à respecter les clauses et conditions du bail, servi par acte extra-judiciaire ou par lettre recommandée avec accusé de réception, restée infructueuse pendant un mois.
                    <br>
                    <br>
                    <br>
                    <b><u>ARTICLE 8 : REGLEMENT DES LITIGES</u></b>
                    <br>
                    Tous litiges ou contestations nés de l’exécution ou de l’interprétation des termes du présent contrat seront réglés à l’amiable. A défaut de solution amiable l’affaire sera portée devant les tribunaux compétents.
                    <br>
                    <br>
                    <b><u>ARTICLE 9 : ELECTION DE DOMICILE </u></b>
                    <br>
                    Pour l’exécution des présentes et de leurs suites, les parties font élection de domicile en leur siège respectif indiqué en tête des présentes.
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

    }

    body {
        font-family: 'times new roman';
    }
</style>

</html>