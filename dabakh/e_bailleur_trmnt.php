<?php
session_start();

include 'connexion.php';
include 'supprim_accents.php';
$annee_inscription = htmlspecialchars($_POST['annee_inscription']);
$num_dossier = htmlspecialchars($_POST['num_dossier']);
$prenom = htmlspecialchars(ucwords(strtolower(suppr_accents($_POST['prenom']))));
$nom = htmlspecialchars(strtoupper(suppr_accents($_POST['nom'])));
$telephone = htmlspecialchars($_POST['telephone']);
$pourcentage = htmlspecialchars($_POST['pourcentage']);
$cni = htmlspecialchars($_POST['cni']);
$date_debut = htmlspecialchars($_POST['date_debut']);
$adresse = htmlspecialchars(strtoupper(suppr_accents($_POST['adresse'])));
$duree_contrat = htmlspecialchars($_POST['duree_contrat']);

$req = $db->prepare('INSERT INTO bailleur(prenom, nom, tel, adresse, num_dossier,annee_inscription, pourcentage, etat, cni, date_debut, duree_contrat, id_user) values (?,?,?,?,?,?,?,"activer",?, ?,?,?) ');
$req->execute(array($prenom, $nom, $telephone, $adresse, $num_dossier, $annee_inscription, $pourcentage, $cni, $date_debut, $duree_contrat, $_SESSION['id'])) or die(print_r($req->errorInfo()));
$nbr = $req->rowCount();
$id = $db->lastInsertId();
$id_bailleur = $id;

//upload des pièces jointes
$nbr_pj = count($_FILES['fichier']['name']);
for ($i = 0; $i < $nbr_pj; $i++) {
	switch ($_FILES['fichier']['error'][$i]) {
		case 1: // UPLOAD_ERR_INI_SIZE
			$error = "Le fichier dépasse la limite autorisée par le serveur (fichier php.ini) !";
			break;
		case 2: // UPLOAD_ERR_FORM_SIZE
			$error = "Le fichier dépasse la limite autorisée dans le formulaire HTML !";
			break;
		case 3: // UPLOAD_ERR_PARTIAL
			$error = "L'envoi du fichier a été interrompu pendant le transfert !";
			break;
		case 4: // UPLOAD_ERR_NO_FILE
			$doc = '';
			break;
		default: {
				// Testons si l'extension est autorisée
				$extension = strrchr($_FILES['fichier']['name'][$i], '.');
				$extensions_autorisees = array('.pdf', '.jpg', '.jpeg');
				if (!in_array($extension, $extensions_autorisees)) {
					$error = 'Seul les fichiers d\'extension pdf et jpeg sont autorisé, contacter votre administrateur pour plus de renseignements';
				} else {
					$repertoire = 'pj/bailleur/' . $id_bailleur . '/';

					if (is_dir($repertoire)) {
					} else {
						mkdir($repertoire);
					}
					$nom_fichier = suppr_accents($_FILES['fichier']['name'][$i]);
					move_uploaded_file($_FILES['fichier']['tmp_name'][$i], $repertoire . $nom_fichier . $extension);
					$doc = 'pj/bailleur/' . $id_bailleur . '/' . $nom_fichier . $extension;
					$req = $db->prepare('INSERT INTO pj_bailleur(nom, chemin, id_bailleur) VALUES (?,?,?)');
					$req->execute(array($nom_fichier, $doc, $id_bailleur)) or die(print_r($req->errorInfo()));
				}
			}
	}
}
//Fin
if ($nbr > 0) {
	header("location:e_logement.php?id=$id");
} else {
?>
	<script type="text/javascript">
		alert('Erreur bailleur non enregistré');
		window.history.go(-1);
	</script>
<?php
}
?>