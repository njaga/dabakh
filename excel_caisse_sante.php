
<?php
include 'connexion.php';

$jour_d=$_POST['date_debut'];
$jour_f=$_POST['date_fin'];
/*
$jour_d='2020-03-01';
$jour_f='2020-08-01';
*/
$mois=date("m", strtotime($jour_d));
$annee=date("Y", strtotime($jour_d));
$som_entree=0;
$som_sortie=0;
$som_solde=0;

$db->query("SET lc_time_names = 'fr_FR';");

//Dernier jour d'opération
$reponse=$db->prepare("SELECT CONCAT(DATE_FORMAT(MAX(date_operation), '%d'), '/', DATE_FORMAT(MAX(date_operation), '%m'),'/', DATE_FORMAT(MAX(date_operation), '%Y')),MAX(date_operation) 
FROM `caisse_sante`
WHERE date_operation<?");
$reponse->execute(array($jour_d));
$donnees=$reponse->fetch();
$jour_lettre=$donnees['0'];
$annee_passe=date("Y", strtotime($donnees['1']));
$jour_chiffre=$donnees['1'];
$reponse->closeCursor();
///Solde du jour précédent

	//entree
$reponse=$db->prepare("SELECT COALESCE(SUM(montant),0) FROM caisse_sante WHERE  date_operation<=? AND type='entree'");
$reponse->execute(array($jour_chiffre));
$donnees=$reponse->fetch();
$entree=$donnees['0'];
	//sortie
$reponse=$db->prepare("SELECT COALESCE(SUM(montant),0) FROM caisse_sante WHERE  date_operation<=? AND type='sortie'");
$reponse->execute(array( $jour_chiffre));
$donnees=$reponse->fetch();
$sortie=$donnees['0'];

$solde_jour_j=$entree-$sortie;

$req=$db->prepare("SELECT id_operation,  CONCAT(DATE_FORMAT(date_operation, '%d'), '/', DATE_FORMAT(date_operation, '%m'),'/', DATE_FORMAT(date_operation, '%Y')), motif, type, montant, id_consultation,section, id_patient_externe, id_consultation_domicile, id_user, pj
FROM `caisse_sante`
WHERE type<>'solde' AND date_operation BETWEEN ? AND ? ORDER BY date_operation, id_operation ASC,  section");
$req->execute(array( $jour_d, $jour_f));
$nbr=$req->rowCount();
$pj_excel="";




require 'vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$phpExcel = new Spreadsheet();
$phpExcel->getProperties()->setCreator('Jappal Ma Japp')
         ->setTitle('Ventillation caisse santé')
         ->setSubject('Ventillation caisse santé')
         ->setDescription('Ventillation caisse santé');
$phpExcel->getActiveSheet()->setTitle("Ventillation caisse santé");
$phpExcel->setActiveSheetIndex(0)
         ->setCellValue('A1','Date')
         ->setCellValue('B1','PJ')
         ->setCellValue('C1','Libelle')
         ->setCellValue('D1','Débit')
         ->setCellValue('E1','Crédit')
         ->setCellValue('F1','Solde');
//Solde prédédent
$phpExcel->setActiveSheetIndex(0)
         ->setCellValue('A2','')
         ->setCellValue('B2','')
         ->setCellValue('C2','Solde du '.$jour_lettre)
         ->setCellValue('D2','')
         ->setCellValue('E2','')
         ->setCellValue('F2',$solde_jour_j);
$i=2;
if ($nbr>0) 
{
    $solde=$solde_jour_j;
    $entree=0;
    $sortie=0;
    while ($donnees= $req->fetch())
    {
        $id_caisse=$donnees['0'];
		$date_operation=$donnees['1'];
		$motif=$donnees['2'];
        $type=$donnees['3'];
		$montant=$donnees['4'];
		$id_consultation=$donnees['5'];
		$section=$donnees['6'];
		$id_patient_externe=$donnees['7'];
		$id_consultation_domicile=$donnees['8'];
		$id_user=$donnees['9'];
		$pj=$donnees['10'];
        $i++;
        if ($type=='entree') 
        {
            $som_entree=$som_entree+$montant;
        }
        elseif($type=='sortie')
        {
            $som_sortie=$som_sortie+$montant;
        }
        
        
        //Affichage des pièces jointes
        if (isset($id_consultation)) 
            {
                $pj_excel='N°'.$id_consultation;	
            }
        elseif (isset($id_consultation_domicile)) 
            {
                $pj_excel='N°'.$id_consultation_domicile;						
            }
        elseif (isset($id_patient_externe)) 
            {
                $pj_excel='N°'.$id_patient_externe;	
            }
        else
            {
                if ($section<>"solde") 
                {
                    $pj_excel='N°'.$pj;
                }
                else
                {
                    $pj_excel="";

                }
                
            }
        if ($type=="entree") 
        {
            $solde=$solde+$montant;
            $entree=$entree+$montant;	
            $phpExcel->setActiveSheetIndex(0)
                        ->setCellValue('A'.$i, $date_operation)
                        ->setCellValue('B'.$i, $pj_excel)
                        ->setCellValue('C'.$i, $motif)
                        ->setCellValue('D'.$i, $montant)
                        ->setCellValue('E'.$i, '')
                        ->setCellValue('F'.$i, $solde);	
        }
        elseif ($type=='sortie') 
        {
            $solde=$solde-$montant;
            $sortie=$sortie+$montant;
            $phpExcel->setActiveSheetIndex(0)
                        ->setCellValue('A'.$i, $date_operation)
                        ->setCellValue('B'.$i, $pj_excel)
                        ->setCellValue('C'.$i, $motif)
                        ->setCellValue('D'.$i, '')
                        ->setCellValue('E'.$i, $montant)
                        ->setCellValue('F'.$i, $solde);
        }
        else
        {
            $solde=$solde+$montant;	
            $phpExcel->setActiveSheetIndex(0)
                        ->setCellValue('A'.$i, $date_operation)
                        ->setCellValue('B'.$i, $pj_excel)
                        ->setCellValue('C'.$i, $motif)
                        ->setCellValue('D'.$i, '')
                        ->setCellValue('E'.$i, '')
                        ->setCellValue('F'.$i, $solde);
        }
    }
    $reponse->closeCursor();
    $i++;
    $phpExcel->setActiveSheetIndex(0)
                ->setCellValue('A'.$i, '')
                ->setCellValue('B'.$i, 'TOTAL')
                ->setCellValue('C'.$i, '')
                ->setCellValue('D'.$i, $entree)
                ->setCellValue('E'.$i, $sortie)
                ->setCellValue('F'.$i, $solde);	
}
$writer = new Xlsx($phpExcel);

$filename = 'Ventillation Caisse Sante.xlsx';
$writer->save($filename);
header('Content-disposition: attachment; filename='.$filename);
header('Content-type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Length: ' . filesize($filename));
header('Content-Transfer-Encoding: binary');
header('Cache-Control: must-revalidate');
header('Pragma: public');
ob_clean();
flush(); 

readfile($filename);
unlink($filename);