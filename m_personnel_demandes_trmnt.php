<?php
session_start();
include 'connexion.php';
include 'supprim_accents.php';

$id_demande=htmlspecialchars($_POST['id_demande']);
$date_demande=htmlspecialchars($_POST['date_demande']);
$type_demande=htmlspecialchars($_POST['type_demande']);
$id_user=substr($_SESSION['prenom'], 0,1).".".substr($_SESSION['nom'], 0,1);
//$id_user=$_SESSION['prenom']." ".$_SESSION['nom'];

if ($type_demande=="Demande de permission" OR $type_demande=="Demande de conge" OR $type_demande=="Demande d'explication") 
{
	if ($type_demande=="Demande d'explication") 
	{
		$date_debut=htmlspecialchars($_POST['date_emission']);
		$date_fin=htmlspecialchars($_POST['date_reception']);
	}
	else
	{
		$date_debut=htmlspecialchars($_POST['date_debut']);
		$date_fin=htmlspecialchars($_POST['date_fin']);
	}
	$req=$db->prepare('UPDATE demandes_p_a SET date_demande=?, type_demande=?, date_debut=?, date_fin=?  WHERE id=?');
	$nbr=$req->execute(array( $date_demande, $type_demande, $date_debut, $date_fin, $id_demande)) or die(print_r($req->errorInfo()));

}
elseif ($type_demande=="Autorisation d'absence") 
{
	$heure_debut=htmlspecialchars($_POST['heure_debut']);
	$heure_fin=htmlspecialchars($_POST['heure_fin']);
	$req=$db->prepare('UPDATE demandes_p_a SET date_demande=?, type_demande=?, heure_debut=?, heure_fin=?  WHERE id=?');
	$nbr=$req->execute(array( $date_demande, $type_demande, $heure_debut, $heure_fin, $id_demande)) or die(print_r($req->errorInfo()));
}

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
			$extensions_autorisees = array('.pdf');
			if (!in_array($extension, $extensions_autorisees))
			{
				$error='Seul les fichiers d\'extension pdf sont autorisé, contacter votre administrateur pour plus de renseignements';
			}
			else
			{
				$repertoire='../pj/demandes_p_a/'.$id_demande.'/';
				if (is_dir($repertoire)) 
				{
					
				}
				else
				{
					mkdir($repertoire);
				}
				$nom=suppr_accents($_FILES['fichier']['name'][$i]);
				move_uploaded_file($_FILES['fichier']['tmp_name'][$i], $repertoire.$nom.$extension);
				$doc=$repertoire.$nom.$extension;
				$req=$db->prepare('INSERT INTO pj_demandes(nom, chemin, id_demande) VALUES (?,?,?)');
				$req->execute(array($nom, $doc, $id_demande)) or die(print_r($req->errorInfo()));
			}
		}
	}
	
}
//Fin
$nbr=1;
if ($nbr>0) {
?>
<script type="text/javascript">
	alert('Demande modifiée');
	window.location="l_demandes_personnel.php";
</script>
<?php
}
else
{
?>
<script type="text/javascript">
	alert('Erreur demande non modifiée');
	window.history.go(-1)
	;
</script>
<?php
}
?>