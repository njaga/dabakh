<?php
include 'connexion.php';
$db->query("SET lc_time_names = 'fr_FR';");
$somme_debit=0;
$somme_credit=0;

$date_debut=$_POST['date_debut'];
$date_fin=$_POST['date_fin'];
$search=$_POST['search'];

$req_consultation_debit=$db->prepare('SELECT CONCAT(patient.prenom, " ", patient.nom), CONCAT(DATE_FORMAT(consultation.date_consultation, "%d"),"/", DATE_FORMAT(consultation.date_consultation, "%m"),"/", DATE_FORMAT(consultation.date_consultation, "%Y")), consultation.montant 
FROM `patient`, consultation 
WHERE consultation.id_patient=patient.id_patient AND date_consultation BETWEEN ? AND ? AND patient.id_patient=?');
$req_consultation_debit->execute(array($date_debut, $date_fin, $search));

$req_consultation_credit=$db->prepare('SELECT CONCAT(DATE_FORMAT(consultation.date_consultation, "%d"),"/", DATE_FORMAT(consultation.date_consultation, "%m"),"/", DATE_FORMAT(consultation.date_consultation, "%Y")), CONCAT(DATE_FORMAT(caisse_sante.date_operation, "%d"),"/", DATE_FORMAT(caisse_sante.date_operation, "%m"),"/", DATE_FORMAT(caisse_sante.date_operation, "%Y")), caisse_sante.motif, caisse_sante.montant 
FROM `patient`, consultation,caisse_sante 
WHERE caisse_sante.id_consultation=consultation.id_consultation AND consultation.id_patient=patient.id_patient AND date_consultation BETWEEN ? AND ? AND patient.id_patient=? AND consultation.reglement="oui"');
$req_consultation_credit->execute(array($date_debut, $date_fin, $search));


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
            
            while ($donnees=$req_consultation_debit->fetch()) 
            {
                $patient=$donnees['0'];
                $date_consultation=$donnees['1'];
                $montant=$donnees['2'];
                echo"<tr>";
                    echo "<td>".$date_consultation."</td>";
                    echo "<td>Consultation <b>".$patient."</b> du <b>".$date_consultation."</b></td>";
                    echo "<td>".number_format($montant,0,'.',' ')." Fcfa</td>";
                    echo"</tr>";
                    $somme_debit=$somme_debit+$montant;
            }
            $req_consultation_debit->closeCursor();
            
            
            echo "<tr class='grey white-text'>";
            echo "<td colspan='2'>Total</td>";
            echo "<td >".number_format($somme_debit,0,'.',' ')." Fcfa</td>";
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
            
            while ($donnees=$req_consultation_credit->fetch()) 
            {
                $date_consultation=$donnees['0'];
                $date_operation=$donnees['1'];
                $motif=$donnees['2'];
                $montant=$donnees['3'];
                
                echo"<tr>";
                    echo "<td>".$date_operation."</td>";
                    echo "<td><b>".$motif."</b> du <b>".$date_consultation."</b></td>";
                    echo "<td>".number_format($montant,0,'.',' ')." Fcfa</td>";
                    echo"</tr>";
                    $somme_credit=$somme_credit+$montant;
            }
            $req_consultation_credit->closeCursor();
            
            
            echo "<tr class='grey white-text'>";
            echo "<td colspan='2'>Total</td>";
            echo "<td >".number_format($somme_credit,0,'.',' ')." Fcfa</td>";
            echo "</tr>";
        ?>
    </tbody>
</table>
<h4 class="col s12 center">
    Le solde du patient est de <?= number_format(($somme_debit-$somme_credit),0,'.',' ')?> Fcfa
</h4>