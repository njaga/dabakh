<?php
session_start();
include 'connexion.php';
include 'supprim_accents.php';

$type_courrier=htmlspecialchars(suppr_accents($_POST['type_courrier']));
$numero=htmlspecialchars(suppr_accents($_POST['numero']));
$intitule=htmlspecialchars(suppr_accents($_POST['intitule']));
$description=htmlspecialchars(suppr_accents($_POST['description']));
$expediteur=htmlspecialchars(suppr_accents($_POST['expediteur']));
$destinataire=htmlspecialchars(suppr_accents($_POST['destinataire']));
$date_courrier=htmlspecialchars(suppr_accents($_POST['date_courrier']));
$id_user=$_SESSION['prenom']." ".$_SESSION['nom'];
$doc="";
$nom_fichier="";

$query=$db->query("SELECT MAX(id) FROM `courrier`");
$donnees=$query->fetch();
$id_courrier=$donnees['0'] + 1;

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
	$extensions_autorisees = array('.pdf', '.jpg', '.jpeg', '.png');
	if (!in_array($extension, $extensions_autorisees))
	{
		$error='Seul les fichiers d\'extension pdf et jpeg sont autorisé, contacter votre administrateur pour plus de renseignements';
	}
	else
	{
		$repertoire='../www/pj/courrier/'.$id_courrier.'/';

		if (is_dir($repertoire))
		{
			
		}
		else
		{
			mkdir($repertoire);
		}
		$nom_fichier=str_replace("'", "", (suppr_accents($_FILES['fichier']['name'][$i])));
		move_uploaded_file($_FILES['fichier']['tmp_name'][$i], $repertoire.$nom_fichier);
		$doc='../pj/courrier/'.$id_courrier.'/'.$nom_fichier;

	}
}
}

}
//Fin

$req=$db->prepare('INSERT INTO courrier(`id`, `numero`, `date_courrier`, `type_courrier`, `intitule`, `description`, `expediteur`, `destinataire`, `chemin`, `id_user`) values (?,?,?,?,?,?,?,?,?,?) ');
$nbr=$req->execute(array($id_courrier, $numero, $date_courrier, $type_courrier, $intitule, $description, $expediteur, $destinataire, $doc, $id_user)) or die(print_r($req->errorInfo()));


if ($nbr>0) {
?>
<script type="text/javascript">
	alert('Courrier enregistré');
	window.location="l_courrier.php";
</script>
<?php
}
else
{
?>
<script type="text/javascript">
	alert('Erreur courrier non enregistrée');
	window.history.go(-1);
</script>
<?php
}
?>