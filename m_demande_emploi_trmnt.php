<?php
session_start();
include 'connexion.php';
include 'supprim_accents.php';

$id_demande=$_POST['id_demande'];
$date_enregistrement=htmlspecialchars($_POST['date_enregistrement']);
$prenom=htmlspecialchars(ucwords(strtolower(suppr_accents($_POST['prenom']))));
$nom=htmlspecialchars(strtoupper(suppr_accents($_POST['nom'])));
$poste=htmlspecialchars(ucfirst(suppr_accents($_POST['poste'])));
$id_user=substr($_SESSION['prenom'], 0,1).".".substr($_SESSION['nom'], 0,1);
//$id_user=$_SESSION['prenom']." ".$_SESSION['nom'];

$req=$db->prepare('UPDATE demande_emploi SET  date_enregistrement=?, prenom=?, nom=?, poste=?, id_user=? WHERE id=?');
$nbr=$req->execute(array($date_enregistrement, $prenom, $nom, $poste, $id_user, $id_demande)) or die(print_r($req->errorInfo()));

//upload des pièces jointes
$repertoire='../www/pj/demande_emploi/'.$id_demande.'/';
if (is_dir($repertoire)) 
{
	$repertoire_cv='../www/pj/demande_emploi/'.$id_demande.'/cv/';
	$repertoire_diplomes='../www/pj/demande_emploi/'.$id_demande.'/diplomes/';
	$repertoire_autres_docs='../www/pj/demande_emploi/'.$id_demande.'/autres_docs/';
}
else
{
	mkdir($repertoire);
	$repertoire_cv='../www/pj/demande_emploi/'.$id_demande.'/cv/';
	$repertoire_diplomes='../www/pj/demande_emploi/'.$id_demande.'/diplomes/';
	$repertoire_autres_docs='../www/pj/demande_emploi/'.$id_demande.'/autres_docs/';
	mkdir($repertoire_cv);
	mkdir($repertoire_diplomes);
	mkdir($repertoire_autres_docs);
}

$nbr_pj = count($_FILES['cv']['name']);
for ($i=0; $i < $nbr_pj; $i++) 
{ 
	switch ($_FILES['cv']['error'][$i])
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
			$extension= strrchr($_FILES['cv']['name'][$i], '.');
			$extensions_autorisees = array('.pdf', '.jpg', '.jpeg', '.png');
			if (!in_array($extension, $extensions_autorisees))
			{
				$error='Seul les fichiers d\'extension pdf sont autorisé, contacter votre administrateur pour plus de renseignements';
			}
			else
			{
				$nom=str_replace("'", "", suppr_accents($_FILES['cv']['name'][$i]));
				move_uploaded_file($_FILES['cv']['tmp_name'][$i], $repertoire_cv.$nom.$extension);
				//$doc=$repertoire_cv.$nom;
				$doc='../pj/demande_emploi/'.$id_demande.'/cv/'.$nom;
				$req=$db->prepare('INSERT INTO pj_demande_emploi(nom, type_demande, chemin, id_demande) VALUES (?,"CV",?,?)');
				$req->execute(array($nom, $doc, $id_demande)) or die(print_r($req->errorInfo()));
			}
		}
	}
	
}
//Fin

$nbr_pj = count($_FILES['diplomes']['name']);
for ($i=0; $i < $nbr_pj; $i++) 
{ 
	switch ($_FILES['diplomes']['error'][$i])
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
			$extension= strrchr($_FILES['diplomes']['name'][$i], '.');
			$extensions_autorisees = array('.pdf', '.jpg', '.jpeg', '.png');
			if (!in_array($extension, $extensions_autorisees))
			{
				$error='Seul les fichiers d\'extension pdf sont autorisé, contacter votre administrateur pour plus de renseignements';
			}
			else
			{
				$nom=str_replace("'", "", suppr_accents($_FILES['diplomes']['name'][$i]));
				move_uploaded_file($_FILES['diplomes']['tmp_name'][$i], $repertoire_diplomes.$nom.$extension);
				//$doc=$repertoire_diplomes.$nom;
				$doc='../pj/demande_emploi/'.$id_demande.'/diplomes/'.$nom;
				$req=$db->prepare('INSERT INTO pj_demande_emploi(nom, type_demande, chemin, id_demande) VALUES (?,"Diplomes",?,?)');
				$req->execute(array($nom, $doc, $id_demande)) or die(print_r($req->errorInfo()));
			}
		}
	}
	
}
//Fin

$nbr_pj = count($_FILES['autres_docs']['name']);
for ($i=0; $i < $nbr_pj; $i++) 
{ 
	switch ($_FILES['autres_docs']['error'][$i])
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
			$extension= strrchr($_FILES['autres_docs']['name'][$i], '.');
			$extensions_autorisees = array('.pdf', '.jpg', '.jpeg', '.png');
			if (!in_array($extension, $extensions_autorisees))
			{
				$error='Seul les fichiers d\'extension pdf sont autorisé, contacter votre administrateur pour plus de renseignements';
			}
			else
			{
				$nom=str_replace("'", "", suppr_accents($_FILES['autres_docs']['name'][$i]));
				move_uploaded_file($_FILES['autres_docs']['tmp_name'][$i], $repertoire_autres_docs.$nom.$extension);
				//$doc=$repertoire_autres_docs.$nom;
				$doc='../pj/demande_emploi/'.$id_demande.'/autres_docs/'.$nom;
				$req=$db->prepare('INSERT INTO pj_demande_emploi(nom, type_demande, chemin, id_demande) VALUES (?,"Autres diplomes",?,?)');
				$req->execute(array($nom, $doc, $id_demande)) or die(print_r($req->errorInfo()));
			}
		}
	}
	
}
//Fin

if ($nbr>0) {
?>
<script type="text/javascript">
	alert('Demande modifiée');
	window.location="l_demande_emploi.php";
</script>
<?php
}

?>