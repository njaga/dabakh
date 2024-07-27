<?php
include 'connexion.php';
$db->query("SET lc_time_names = 'fr_FR';");
$somme_mens_locataire=0;
$somme_mens_depense=0;
$somme_mens_bailleur=0;
$somme_commission=0;

if (isset($_POST['date_debut']) AND isset($_POST['date_fin'])) 
{
    $date_debut=$_POST['date_debut'];
    $date_fin=$_POST['date_fin'];
    $search=$_POST['search'];

    $req_mens_locataire=$db->prepare('SELECT mensualite.mois, CONCAT(DATE_FORMAT(date_versement, "%d"),"/", DATE_FORMAT(date_versement, "%m"),"/", DATE_FORMAT(date_versement, "%Y")), montant, CONCAT(locataire.prenom," ",locataire.nom), type_logement.type_logement 
    FROM `mensualite`, location,logement, bailleur, type_logement, locataire 
    WHERE mensualite.id_location=location.id AND location.id_logement=logement.id AND logement.id_bailleur=bailleur.id 
    AND logement.id_type=type_logement.id AND locataire.id=location.id_locataire AND mensualite.date_versement BETWEEN ? AND ? AND bailleur.id=? ORDER BY date_versement');
    $req_mens_locataire->execute(array($date_debut, $date_fin, $search));

    $req_mens_depense=$db->prepare('SELECT depense_bailleur.motif, CONCAT(DATE_FORMAT(depense_bailleur.date_depense, "%d"),"/", DATE_FORMAT(depense_bailleur.date_depense, "%m"),"/", DATE_FORMAT(depense_bailleur.date_depense, "%Y")), depense_bailleur.montant, depense_bailleur.mois
    FROM depense_bailleur
    WHERE depense_bailleur.date_depense BETWEEN ? AND ? AND depense_bailleur.id_bailleur=?   ORDER BY date_depense');
    $req_mens_depense->execute(array($date_debut, $date_fin, $search));

    $req_mens_bailleur=$db->prepare("SELECT CONCAT(DATE_FORMAT(date_versement, '%d'),'/', DATE_FORMAT(date_versement, '%m'),'/', DATE_FORMAT(date_versement, '%Y')), montant, mois, commission
                 FROM `mensualite_bailleur`WHERE date_versement BETWEEN ? AND ? AND id_bailleur=? ORDER BY date_versement");
    $req_mens_bailleur->execute(array($date_debut, $date_fin, $search));
}
else
{
    $mois=$_POST['mois'];
    $annee=$_POST['annee'];
    $search=$_POST['search'];

    $req_mens_locataire=$db->prepare('SELECT mensualite.mois, CONCAT(DATE_FORMAT(date_versement, "%d"),"/", DATE_FORMAT(date_versement, "%m"),"/", DATE_FORMAT(date_versement, "%Y")), montant, CONCAT(locataire.prenom," ",locataire.nom), type_logement.type_logement 
    FROM `mensualite`, location,logement, bailleur, type_logement, locataire 
    WHERE mensualite.id_location=location.id AND location.id_logement=logement.id AND logement.id_bailleur=bailleur.id 
    AND logement.id_type=type_logement.id AND locataire.id=location.id_locataire AND mensualite.annee=? AND mensualite.mois=? AND bailleur.id=? ORDER BY date_versement');
    $req_mens_locataire->execute(array($annee, $mois, $search));

    $req_mens_depense=$db->prepare('SELECT depense_bailleur.motif, CONCAT(DATE_FORMAT(depense_bailleur.date_depense, "%d"),"/", DATE_FORMAT(depense_bailleur.date_depense, "%m"),"/", DATE_FORMAT(depense_bailleur.date_depense, "%Y")), depense_bailleur.montant, depense_bailleur.mois
    FROM depense_bailleur
    WHERE depense_bailleur.mois=? AND depense_bailleur.annee=? AND depense_bailleur.id_bailleur=?   ORDER BY date_depense');
    $req_mens_depense->execute(array($mois, $annee, $search));

    $req_mens_bailleur=$db->prepare("SELECT CONCAT(DATE_FORMAT(date_versement, '%d'),'/', DATE_FORMAT(date_versement, '%m'),'/', DATE_FORMAT(date_versement, '%Y')), montant, mois, commission
                 FROM `mensualite_bailleur`WHERE mensualite_bailleur.annee=? AND mensualite_bailleur.mois=? AND id_bailleur=? ORDER BY date_versement");
    $req_mens_bailleur->execute(array($annee, $mois, $search));
}
?>
<table class="col s6">
    <thead>
        <tr>
            <th colspan="3" class="center"><h4>Débit</h4></th>
        </tr>
        <tr>
            <th>Date</th>
            <th> Libellé</th>
            <th> Montant</th>
        </tr>
    </thead>
    <tbody>
        <?php
            
            
            while ($donnees=$req_mens_locataire->fetch()) 
            {
                $mois=$donnees['0'];
                $date_versement=$donnees['1'];
                $montant=$donnees['2'];
                $locataire=$donnees['3'];
                $logement=$donnees['4'];
                echo"<tr>";
                    echo "<td>".$date_versement."</td>";
                    echo "<td>Loyer <b>".$locataire."</b> de <b>".$mois."</b> pour : <b>".$logement."</b></td>";
                    echo "<td>".number_format($montant,0,'.',' ')." Fcfa</td>";
                    echo"</tr>";
                    $somme_mens_locataire=$somme_mens_locataire+$montant;
            }
            $req_mens_locataire->closeCursor();
            
            
            $req_mens_locataire->closeCursor();
            echo "<tr class='grey white-text'>";
            echo "<td colspan='2'>Total</td>";
            echo "<td >".number_format($somme_mens_locataire,0,'.',' ')." Fcfa</td>";
            echo "</tr>";
        ?>
    </tbody>
</table>
<table class="col s6 ">
    <thead>
        <tr>
            <th colspan="3" class="center"><h4>Crédit</h4></th>
        </tr>
        <tr>
            <th>Date</th>
            <th> Libellé</th>
            <th> Montant</th>
        </tr>
    </thead>
    <tbody>
        <?php    
            while ($donnees=$req_mens_depense->fetch()) 
            {
                $motif=$donnees['0'];
                $date_depense=$donnees['1'];
                $montant=$donnees['2'];
                echo"<tr>";
                    echo "<td>".$date_depense."</td>";
                    echo "<td>".$motif."</td>";
                    echo "<td>".number_format($montant,0,'.',' ')." Fcfa</td>";
                    echo"</tr>";
                    $somme_mens_depense=$somme_mens_depense+$montant;
            }        
            while ($donnees=$req_mens_bailleur->fetch()) 
            {

                $date_versement=$donnees['0'];
                $montant=$donnees['1'];
                $mois=$donnees['2'];
                $commission=$donnees['3'];
                echo"<tr>";
                    echo "<td>".$date_versement."</td>";
                    echo "<td>Payement du mois de ".$mois."</td>";
                    echo "<td>".number_format($montant,0,'.',' ')." Fcfa</td>";
                echo"</tr>";
                 echo"<tr>";
                    echo "<td>".$date_versement."</td>";
                    echo "<td>Commission gérence du mois de : ".$mois."</td>";
                    echo "<td>".number_format($commission,0,'.',' ')." Fcfa</td>";
                echo"</tr>";
                    $somme_mens_bailleur=$somme_mens_bailleur+$montant;
                    $somme_commission=$somme_commission+$commission;
            }

             $req_mens_bailleur->closeCursor();
            
            echo "<tr class='grey white-text'>";
                echo "<td style='border:1px solid black;' colspan='2'>Total</td>";
                echo "<td style='border:1px solid black;' >".number_format(($somme_mens_bailleur+$somme_mens_depense+$somme_commission),0,'.',' ')." Fcfa</td>";
            echo "</tr>";
            $solde=$somme_mens_locataire-($somme_mens_bailleur+$somme_mens_depense+$somme_commission);
        ?>
    </tbody>
</table>
<h4 class="col s12 center">
    Le solde du bailleur est de <?= number_format($solde,0,'.',' ')?> Fcfa
</h4>