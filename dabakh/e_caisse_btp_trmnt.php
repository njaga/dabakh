<?php
session_start();
include 'connexion.php';
include 'supprim_accents.php';

$type=htmlspecialchars($_POST['type']);
$section=htmlspecialchars($_POST['section']);
$motif=htmlspecialchars(ucfirst(strtolower(suppr_accents($_POST['motif']))));
$montant=htmlspecialchars($_POST['montant']);
$date_operation=htmlspecialchars($_POST['date_operation']);
$pj=htmlspecialchars($_POST['pj']);
$id_user=$_SESSION['prenom']." ".$_SESSION['nom'];
$structure="btp";
$req=$db->prepare('INSERT INTO caisse_btp(type, section, motif, montant,date_operation, id_user, pj) values (?,?,?,?,?,?,?) ');
$nbr=$req->execute(array($type, $section, $motif, $montant, $date_operation, $id_user, $pj)) or die(print_r($req->errorInfo()));
$id_caisse=$db->lastInsertId();
if ($nbr>0) 
{
	if ($section=="Approvisionnement banque par caisse") 
	{
		$num_cheque="";
		$type="entree";
		$section="Approvisionnement banque par caisse";
		$motif=strtoupper("Approvisionnement de la banque par la caisse");
		$req=$db->prepare('INSERT INTO banque(type, section, motif, num_cheque, montant,date_operation, structure, pj, id_user) values (?,?,?,?,?,?,?,?,?) ');
	$nbr=$req->execute(array($type, $section, $motif, $num_cheque, $montant, $date_operation, $structure, $pj,  $id_user)) or die(print_r($req->errorInfo()));
	}
	if ($section=="Versement") 
	{
		$num_cheque="";
		$type="entree";
		$section="Versement";
		$motif=strtoupper("Versement banque / caisse BTP");
		$req=$db->prepare('INSERT INTO banque(type, section, motif, num_cheque, montant,date_operation, structure, pj, id_user) values (?,?,?,?,?,?,?,?,?) ');
	$nbr=$req->execute(array($type, $section, $motif, $num_cheque, $montant, $date_operation, $structure, $pj, $id_user)) or die(print_r($req->errorInfo()));
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
	$extensions_autorisees = array('.pdf', '.jpg', '.jpeg');
	if (!in_array($extension, $extensions_autorisees))
	{
		$error='Seul les fichiers d\'extension pdf et jpeg sont autorisé, contacter votre administrateur pour plus de renseignements';
	}
	else
	{
		$repertoire='../pj/caisse_btp/'.$id_caisse.'/';

		if (is_dir($repertoire))
		{
			
		}
		else
		{
			mkdir($repertoire);
		}
		
		$nom_fichier=str_replace("'", "", (suppr_accents($_FILES['fichier']['name'][$i])));
		move_uploaded_file($_FILES['fichier']['tmp_name'][$i], $repertoire.$nom_fichier);
		$doc='../pj/caisse_btp/'.$id_caisse.'/'.$nom_fichier;
		$req=$db->prepare('INSERT INTO pj_caisse(nom, chemin, id_caisse_btp) VALUES (?,?,?)');
		$req->execute(array($nom_fichier, $doc, $id_caisse)) or die(print_r($req->errorInfo()));
	}
}
}

}
//Fin
?>
<script type="text/javascript">
	alert('Opération enregistrée');
	window.location="etat_caisse_btp.php";
</script>
<?php
}
else
{
?>
<script type="text/javascript">
	alert('Erreur operation non enregistrée');
	window.location="e_caisse_btp.php";
</script>
<?php
}
?>