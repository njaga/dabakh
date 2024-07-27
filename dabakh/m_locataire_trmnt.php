<?php
include 'connexion.php';
include 'supprim_accents.php';
$prenom = htmlspecialchars(ucwords(strtolower(suppr_accents($_POST['prenom']))));
$nom = htmlspecialchars(strtoupper(suppr_accents($_POST['nom'])));
$telephone = htmlspecialchars($_POST['telephone']);
$cni = htmlspecialchars($_POST['cni']);
$annee_inscription = htmlspecialchars($_POST['annee_inscription']);
$num_dossier = htmlspecialchars($_POST['num_dossier']);
$email = htmlspecialchars($_POST['email']);
$statut = htmlspecialchars($_POST['statut']);
$req = $db->prepare('UPDATE locataire SET prenom=?, nom=?, tel=?, statut=?, num_dossier=?, annee_inscription=?, cni=?, email=? WHERE id=?');
$req->execute(array($prenom, $nom, $telephone, $statut, $num_dossier, $annee_inscription, $cni, $email, $_GET['id'])) or die(print_r($req->errorInfo()));
$nbr = 1;
$id_locataire = $_GET['id'];
if ($nbr > 0) {
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
						$error = 'Seul les fichiers d\'extension pdf sont autorisé, contacter votre administrateur pour plus de renseignements';
					} else {
						$repertoire = 'pj/locataire/' . $id_locataire . '/';

						if (is_dir($repertoire)) {
						} else {
							mkdir($repertoire);
						}
						$nom_fichier = suppr_accents($_FILES['fichier']['name'][$i]);
						move_uploaded_file($_FILES['fichier']['tmp_name'][$i], $repertoire . $nom_fichier . $extension);
						$doc = $repertoire = 'pj/locataire/' . $id_locataire . '/' . $nom_fichier . $extension;
						$req = $db->prepare('INSERT INTO pj_locataire(nom, chemin, id_locataire) VALUES (?,?,?)');
						$req->execute(array($nom_fichier, $doc, $id_locataire)) or die(print_r($req->errorInfo()));
					}
				}
		}
	}
	//Fin
?>
	<script type="text/javascript">
		alert('Locataire modifié');
		window.location = "l_locataire_actif.php";
	</script>
<?php
} else {
?>
	<script type="text/javascript">
		alert('Erreur locataire non modifié');
		window.history.go(-1);
	</script>
<?php
}
?>