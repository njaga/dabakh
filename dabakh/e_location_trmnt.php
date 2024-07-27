<?php
session_start();
include 'connexion.php';
include 'supprim_accents.php';
$db->query("SET lc_time_names = 'fr_FR';");
$type_contrat = htmlspecialchars($_POST['type_contrat']);
if ($type_contrat == 'habitation') {
	$prenom = htmlspecialchars(ucfirst(strtolower(suppr_accents($_POST['prenom']))));
	$nom = htmlspecialchars(strtoupper(suppr_accents($_POST['nom'])));
	$telephone = htmlspecialchars($_POST['telephone']);
	$cni = htmlspecialchars($_POST['cni']);
} else {
	$prenom = htmlspecialchars(ucfirst(strtolower(suppr_accents($_POST['societe']))));
	$nom = htmlspecialchars($_POST['representant']);
	$cni = htmlspecialchars($_POST['rc']);
	$telephone = htmlspecialchars($_POST['tel']);
}
$email = $_POST['email'];
$num_dossier = $_POST['num_dossier'];
$annee_inscription = $_POST['annee_inscription'];
$logement = htmlspecialchars($_GET['id']);
$date_debut = htmlspecialchars($_POST['date_debut']);
$caution = htmlspecialchars($_POST['caution']);
$prix_location = htmlspecialchars($_POST['prix_location']);
$commision = htmlspecialchars($_POST['commision']);
$mensualite = htmlspecialchars($_POST['mensualite']);
$depot_garantie = htmlspecialchars($_POST['depot_garantie']);
$id_user = $_SESSION['prenom'] . " " . $_SESSION['nom'];
$locataire = $_POST['id_locataire'];

$verif = 0;
$db->beginTransaction();
//insertion du locataire
if (isset($locataire)) {
	$id_locataire = $_POST['id_locataire'];
	$req = $db->prepare('UPDATE locataire SET statut="actif" WHERE id=? ');
	$req->execute(array($locataire)) or die(print_r($req->errorInfo()));
	$req = $db->prepare('SELECT prenom, nom FROM locataire  WHERE id=? ');
	$req->execute(array($locataire)) or die(print_r($req->errorInfo()));
	$donnees_loc = $req->fetch();
	$prenom = $donnees_loc['0'];
	$nom = $donnees_loc['1'];
} else {
	$req = $db->prepare('INSERT INTO locataire(prenom, nom, tel, cni, annee_inscription, num_dossier, statut, email ) values (?,?,?,?,?,?,?,?) ');
	$req->execute(array($prenom, $nom, $telephone, $cni, $annee_inscription, $num_dossier, "actif", $email)) or die(print_r($req->errorInfo()));
	$locataire = $db->lastInsertId();
}
$result = $req->rowCount();
if ($result < 1) {
	$verif = 1;
}
$req->closeCursor();

//insertion de la location
$req = $db->prepare('INSERT INTO location(date_debut, caution, commission, prix_location, id_logement, id_locataire, etat, type_contrat, id_user) values (?,?,?,?,?,?,"active",?,?) ');
$req->execute(array($date_debut, $caution, $commision, $prix_location, $logement, $locataire, $type_contrat, $id_user)) or die(print_r($req->errorInfo()));
$id_location = $db->lastInsertId();
$result = $req->rowCount();
if ($result < 1) {
	$verif = 2;
}
$req->closeCursor();

//Selection du logement choisi et décrémentation
$req = $db->prepare('SELECT * FROM logement WHERE id=?');
$req->execute(array($_GET['id'])) or die($req->errorInfo());
$donnees = $req->fetch();
$nbr = $donnees['4'] - 1;
$nbr_occupe = $donnees['5'] + 1;
if ($result < 1) {
	$verif = 3;
}
$req->closeCursor();
$req = $db->prepare('UPDATE logement SET nbr=?, nbr_occupe=? WHERE id=?');
$req->execute(array($nbr, $nbr_occupe, $_GET['id'])) or die($req->errorInfo());
if ($result < 1) {
	$verif = 4;
}
$req->closeCursor();
//FIN

//Insertion de la commission
$motif = "Commision locataire " . $prenom . " " . $nom;
$req = $db->prepare('INSERT INTO caisse_immo (type, section, motif, date_operation, montant, id_location, id_user, pj) VALUES ("entree","Commision",?,?,?,?,?, ?)');
$req->execute(array($motif, $date_debut, $commision, $id_location, $id_user, $id_location)) or die($req->errorInfo());
if ($result < 1) {
	$verif = 5;
}
$req->closeCursor();

//Insertion de la caution
$motif = "Caution locataire " . $prenom . " " . $nom;
$req = $db->prepare('INSERT INTO caisse_caution (type, section, motif, date_operation, montant, id_location, id_user) VALUES ("entree","Caution",?,?,?,?,?)');
$req->execute(array($motif, $date_debut, $caution, $id_location, $id_user)) or die($req->errorInfo());
$req = $db->prepare('INSERT INTO caisse_immo (type, section, motif, date_operation, montant, id_location, id_user, pj) VALUES ("entree","Caution",?,?,?,?,?,?)');
$req->execute(array($motif, $date_debut, $caution, $id_location, $id_user, $id_location)) or die($req->errorInfo());

/*Insertion du depot de garantie
$motif="Depot de garantie de ".$prenom." ".$nom;
$req=$db->prepare('INSERT INTO caisse_depot (type, section, motif, date_operation, montant, id_location, id_user) VALUES ("entree","Depot de garantie",?,?,?,?,?)');
$req->execute(array($motif, $date_debut, $depot_garantie, $id_location, $id_user)) or die($req->errorInfo());
$req=$db->prepare('INSERT INTO caisse_immo (type, section, motif, date_operation, montant, id_location, id_user, pj) VALUES ("entree","Depot de garantie",?,?,?,?,?,?)');
$req->execute(array($motif, $date_debut, $depot_garantie, $id_location, $id_user, $id_location)) or die($req->errorInfo());
*/

//premier mensualite
$reponse = $db->query('SELECT MAX(id) FROM mensualite');
$donnee = $reponse->fetch();
$id_mensualite = $donnee['0'] + 1;
//mois
$reponse = $db->prepare('SELECT monthname(?)');
$reponse->execute(array($date_debut)) or die(print_r($req->errorInfo()));
$donnee = $reponse->fetch();
$mois = ucfirst($donnee['0']);
//année
$reponse = $db->prepare('SELECT year(?)');
$reponse->execute(array($date_debut)) or die(print_r($req->errorInfo()));
$donnee = $reponse->fetch();
$annee = $donnee['0'];

$req = $db->prepare('INSERT INTO mensualite(id, montant, mois, annee, date_versement, type, id_location, id_user) values (?,?,?,?,?,"complet",?,?) ');
$req->execute(array($id_mensualite, $mensualite, $mois, $annee, $date_debut, $id_location, $id_user)) or die(print_r($req->errorInfo()));
$nbr = $req->rowCount();
$req->closeCursor();

$motif = "Avance premier mois loyer " . $prenom . " " . $nom;
$req = $db->prepare('INSERT INTO caisse_immo (type, section, motif, date_operation, montant, id_location, id_user, pj) VALUES ("entree","Reglement facture",?,?,?,?,?,?)');
$req->execute(array($motif, $date_debut, $mensualite, $id_location, $id_user, $id_location)) or die($req->errorInfo());
if ($result < 1) {
	$verif = 6;
}

if ($verif == 0) {
	$db->commit();
?>
	<script type="text/javascript">
		alert('Dossier enregistré');
		//window.location="l_location.php";
	</script>
	<?php
	if ($type_contrat == "habitation") {
		$parametres = $id_location . '&m=' . $mensualite;
		header('location:i_fac_ins_loc.php?id=' . $parametres);
	} else {
		$parametres = $id_location . '&m=' . $mensualite;
		header('location:i_fac_ins_loc.php?id=' . $parametres);
	}
	$parametres = $id_location . '&m=' . $mensualite;
	header('location:i_fac_ins_loc.php?id=' . $parametres);
} else {
	$db->rollback();
	echo $verif;
	?>
	<script type="text/javascript">
		alert('Erreur location non enregistrée');
		window.history.go(-1);
	</script>
<?php
}

?>