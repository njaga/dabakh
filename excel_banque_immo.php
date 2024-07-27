<?php
include 'connexion.php';
include 'supprim_accents.php';

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
FROM `banque`
WHERE date_operation<?");
$reponse->execute(array($jour_d));
$donnees=$reponse->fetch();
$jour_lettre=$donnees['0'];
$annee_passe=date("Y", strtotime($donnees['1']));
$jour_chiffre=$donnees['1'];
$reponse->closeCursor();
///Solde du jour précédent

	//entree
$reponse=$db->prepare("SELECT COALESCE(SUM(montant),0) FROM banque WHERE  date_operation<=? AND type='entree'");
$reponse->execute(array($jour_chiffre));
$donnees=$reponse->fetch();
$entree=$donnees['0'];
	//sortie
$reponse=$db->prepare("SELECT COALESCE(SUM(montant),0) FROM banque WHERE  date_operation<=? AND type='sortie'");
$reponse->execute(array( $jour_chiffre));
$donnees=$reponse->fetch();
$sortie=$donnees['0'];

$solde_jour_j=$entree-$sortie;

$req=$db->prepare("SELECT id, CONCAT(DATE_FORMAT(date_operation, '%d'), '/', DATE_FORMAT(date_operation, '%m'),'/', DATE_FORMAT(date_operation, '%Y')), motif, type, montant, section, num_cheque, id_mensualite_bailleur, id_depense_bailleur, id_user, pj
FROM `banque`
WHERE  date_operation BETWEEN ? AND ?  AND type <>'solde' ORDER BY date_operation, id ASC,  section");
$req->execute(array( $jour_d, $jour_f));
$nbr=$req->rowCount();
$pj_excel="";




require 'vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$phpExcel = new Spreadsheet();
$phpExcel->getProperties()->setCreator('Jappal Ma Japp')
         ->setTitle('Ventillation BANQUE')
         ->setSubject('Ventillation BANQUE')
         ->setDescription('Ventillation BANQUE');
$phpExcel->getActiveSheet()->setTitle("Ventillation BANQUE");
$phpExcel->setActiveSheetIndex(0)
         ->setCellValue('A1','Date')
         ->setCellValue('B1','PJ')
         ->setCellValue('C1','N° chèque')
         ->setCellValue('D1','Libelle')
         ->setCellValue('E1','Débit')
         ->setCellValue('F1','Crédit')
         ->setCellValue('G1','Solde');
//Solde prédédent
$phpExcel->setActiveSheetIndex(0)
         ->setCellValue('A2','')
         ->setCellValue('B2','')
         ->setCellValue('C2','')
         ->setCellValue('D2','Solde du '.$jour_lettre)
         ->setCellValue('E2','')
         ->setCellValue('F2','')
         ->setCellValue('G2',$solde_jour_j);
$i=2;
if ($nbr>0) 
{
    $solde=$solde_jour_j;
    $entree=0;
    $sortie=0;
    while ($donnees= $req->fetch())
    {
        $id=$donnees['0'];
		$date_operation=$donnees['1'];
		$motif=ucfirst(strtolower(suppr_accents($donnees['2'])));
        $type=$donnees['3'];
		$montant=$donnees['4'];
		$section=$donnees['5'];
		$num_cheque=$donnees['6'];
		$id_mensualite_bailleur=$donnees['7'];
		$id_depense_bailleur=$donnees['8'];
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
        if (isset($id_depense_bailleur)) 
            {
                $pj_excel='N°'.$id_depense_bailleur;						
            }
        elseif (isset($id_mensualite_bailleur)) 
            {
                $pj_excel='N°'.$id_mensualite_bailleur;	
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
                        ->setCellValue('C'.$i, $num_cheque)
                        ->setCellValue('D'.$i, $motif)
                        ->setCellValue('E'.$i, $montant)
                        ->setCellValue('F'.$i, '')
                        ->setCellValue('G'.$i, $solde);	
        }
        elseif ($type=='sortie') 
        {
            $solde=$solde-$montant;
            $sortie=$sortie+$montant;
            $phpExcel->setActiveSheetIndex(0)
                        ->setCellValue('A'.$i, $date_operation)
                        ->setCellValue('B'.$i, $pj_excel)
                        ->setCellValue('C'.$i, $num_cheque)
                        ->setCellValue('D'.$i, $motif)
                        ->setCellValue('E'.$i, '')
                        ->setCellValue('F'.$i, $montant)
                        ->setCellValue('G'.$i, $solde);
        }
        else
        {
            $solde=$solde+$montant;	
            $phpExcel->setActiveSheetIndex(0)
                        ->setCellValue('A'.$i, $date_operation)
                        ->setCellValue('B'.$i, $pj_excel)
                        ->setCellValue('C'.$i, $motif)
                        ->setCellValue('D'.$i, $num_cheque)
                        ->setCellValue('E'.$i, '')
                        ->setCellValue('F'.$i, '')
                        ->setCellValue('G'.$i, $solde);
        }
    }
    $reponse->closeCursor();
    $i++;
    $phpExcel->setActiveSheetIndex(0)
                        ->setCellValue('A'.$i, '')
                        ->setCellValue('B'.$i, 'TOTAL')
                        ->setCellValue('C'.$i, '')
                        ->setCellValue('D'.$i, '')
                        ->setCellValue('E'.$i, $entree)
                        ->setCellValue('F'.$i, $sortie)
                        ->setCellValue('G'.$i, $solde);	
}
$writer = new Xlsx($phpExcel);

$filename = 'Ventillation BANQUE.xlsx';
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