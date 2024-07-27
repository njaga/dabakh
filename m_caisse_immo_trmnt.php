<?php
include 'connexion.php';
include 'supprim_accents.php';
$type=htmlspecialchars($_POST['type']);
$section=htmlspecialchars($_POST['section']);
$motif=htmlspecialchars(ucfirst(strtolower($_POST['motif'])));
$montant=htmlspecialchars($_POST['montant']);
$date_operation=htmlspecialchars($_POST['date_operation']);
$pj=htmlspecialchars($_POST['pj']);

$req=$db->prepare('UPDATE caisse_immo SET type=?, section=?, motif=?, montant=?,date_operation=?, pj=? WHERE id=?');
$nbr=$req->execute(array($type, $section, $motif, $montant, $date_operation, $pj, $_GET['id'])) or die(print_r($req->errorInfo()));
$nbr=1;

$id_caisse=$_GET['id'];
//upload des pièces jointes
$nbr_pj = count($_FILES['fichier']['name']);
for ($i=0; $i < $nbr_pj; $i++)
{
	switch ($_FILES['fichier']['error'][$i])
	{
		case 1: // UPLOAD_ERR_INI_SIZE
		$error="Le fichier dépasse la limite autorisée par le serveur (fichier php.ini) !";
		break;
		case 2: // UPLOAD_ERR_FORM_SIZE
		$error= "Le fichier dépasse la limite autorisée dans le formulaire HTML !";
		break;
		case 3: // UPLOAD_ERR_PARTIAL
		$error= "L'envoi du fichier a été interrompu pendant le transfert !";
		break;
		case 4: // UPLOAD_ERR_NO_FILE
		$doc='';
		break;
		default:
		{
			// Testons si l'extension est autorisée
			$extension= strrchr($_FILES['fichier']['name'][$i], '.');
			$extensions_autorisees = array('.pdf', '.jpg', '.jpeg');
			if (!in_array($extension, $extensions_autorisees))
			{
				$error='Seul les fichiers d\'extension pdf et jpeg sont autorisé, contacter votre administrateur pour plus de renseignements';
			}
			else
			{
				$repertoire='../www/pj/caisse_immo/'.$id_caisse.'/';

				if (is_dir($repertoire))
				{
					
				}
				else
				{
					mkdir($repertoire);
				}
				$nom_fichier=str_replace("'", "", (suppr_accents($_FILES['fichier']['name'][$i])));
				move_uploaded_file($_FILES['fichier']['tmp_name'][$i], $repertoire.$nom_fichier);
				$doc='../pj/caisse_immo/'.$id_caisse.'/'.$nom_fichier;
				$req=$db->prepare('INSERT INTO pj_caisse(nom, chemin, id_caisse) VALUES (?,?,?)');
				$req->execute(array($nom_fichier, $doc, $id_caisse)) or die(print_r($req->errorInfo()));
			}
		}
	}

}
//Fin
?>
<script type="text/javascript">
	alert('Opération modifiée');
	window.location="etat_caisse_immo.php";
</script>