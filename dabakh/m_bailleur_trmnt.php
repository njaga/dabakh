<?php
include 'connexion.php';
include 'supprim_accents.php';
$pourcentage = htmlspecialchars($_POST['pourcentage']);
$annee_inscription = htmlspecialchars($_POST['annee_inscription']);
$num_dossier = htmlspecialchars($_POST['num_dossier']);
$prenom = htmlspecialchars(strtolower(suppr_accents($_POST['prenom'])));
$nom = htmlspecialchars(strtoupper(suppr_accents($_POST['nom'])));
$telephone = htmlspecialchars($_POST['telephone']);
$cni = htmlspecialchars($_POST['cni']);
$duree_contrat = htmlspecialchars($_POST['duree_contrat']);
$date_debut = htmlspecialchars($_POST['date_debut']);
$adresse = htmlspecialchars(strtoupper(suppr_accents($_POST['adresse_bailleur'])));
$req = $db->prepare('UPDATE bailleur SET prenom=?, nom=?, tel=?, adresse=?, num_dossier=?,annee_inscription=?, pourcentage=?, cni=?, date_debut=?, duree_contrat=? WHERE id=? ');
$req->execute(array($prenom, $nom, $telephone, $adresse, $num_dossier, $annee_inscription, $pourcentage, $cni, $date_debut, $duree_contrat, $_GET['id'])) or die(print_r($req->errorInfo()));
$nbr = $req->rowCount();
$id_bailleur = $_GET['id'];
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
?>
<script type="text/javascript">
	alert('bailleur modifié');
	window.location = "l_bailleur.php";
</script>