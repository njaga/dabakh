<?php
session_start();
include 'connexion.php';
/*
$frais_reparation=0;
$frais_judiciaire=0;
$autres_frais=0;
$type_versement=htmlspecialchars(strtoupper($_POST['type_versement']));
*/
$montant = htmlspecialchars($_POST['montant']);
$mois = htmlspecialchars($_POST['mois']);
$annee = htmlspecialchars($_POST['annee']);
$date_versement = htmlspecialchars($_POST['date_versement']);
$non_recouvrer = htmlspecialchars($_POST['non_recouvrer']);
$commission = htmlspecialchars($_POST['commission']);
$type_paiement = htmlspecialchars($_POST['type_paiement']);
$num_cheque = htmlspecialchars($_POST['num_cheque']);
$mois_a_payer = $_POST['mois_a_payer'];
$montant_caisse = $_POST['montant_caisse'];
$montant_banque = $_POST['montant_banque'];
//Vérification des montants caisse_banque par rapport au montant net pour un paiement à la banque & à la caisse

/*
if ($type_paiement == "caisse_banque") {
	if (($montant_caisse + $montant_banque) < $montant) {
?>
		<script type="text/javascript">
			alert("Erreur\nLa somme des montants saisies ne correspond pas au net à payer");
			window.history.go(-1);
		</script>
	<?php
		exit();
	}
}
*/

$id_user1 = substr($_SESSION['prenom'], 0, 1) . "." . substr($_SESSION['nom'], 0, 1);
$id_user = $_SESSION['prenom'] . "." . $_SESSION['nom'];

$reponse = $db->query('SELECT MAX(id) FROM mensualite_bailleur');
$donnee = $reponse->fetch();
$id_mensualite_bailleur = $donnee['0'] + 1;

//insertion de la mensualité dans la table mensualite_bailleur
$req = $db->prepare('INSERT INTO mensualite_bailleur(id,montant, mois, annee, date_versement, non_recouvrer, commission ,id_bailleur, id_user, type_versement) values (?,?,?,?,?,?,?,?,?,?) ');
$req->execute(array($id_mensualite_bailleur, $montant, $mois, $annee, $date_versement, $non_recouvrer, $commission, $_GET['id'], $id_user1, $type_paiement)) or die(print_r($req->errorInfo()));
$nbr = $req->rowCount();
$id_mensualite_bailleur = $db->lastInsertId();
$req->closeCursor();

$req = $db->prepare("SELECT CONCAT(bailleur.prenom,' ', bailleur.nom)
	FROM  bailleur 
	WHERE id=?");
$req->execute(array($_GET['id']));
$donnees = $req->fetch();
$motif = "Payement " . $mois . " bailleur " . $donnees['0'];
$req->closeCursor();

//sélection des mensualités et update des id_mensualite_bailleur
if ($mois_a_payer == "tous") {
	$req = $db->prepare("SELECT  mensualite.id
			FROM mensualite, logement, bailleur, location, type_logement
			WHERE logement.id_type=type_logement.id AND mensualite.id_location=location.id AND location.id_logement=logement.id AND logement.id_bailleur=bailleur.id AND bailleur.id=? AND mensualite.id_mensualite_bailleur=0");
	$req->execute(array($_GET['id']));
	while ($donnees = $req->fetch()) {
		$req_update = $db->prepare('UPDATE mensualite SET id_mensualite_bailleur=? WHERE id=?');
		$req_update->execute(array($id_mensualite_bailleur, $donnees['0']));
	}
} else {
	$req = $db->prepare("SELECT mensualite.id
		FROM mensualite, logement, bailleur, location, type_logement
		WHERE logement.id_type=type_logement.id AND mensualite.id_location=location.id AND location.id_logement=logement.id AND logement.id_bailleur=bailleur.id AND mensualite.mois=? AND mensualite.annee=? AND bailleur.id=? AND mensualite.id_mensualite_bailleur=0");
	$req->execute(array($mois, $annee, $_GET['id']));
	while ($donnees = $req->fetch()) {
		$req_update = $db->prepare('UPDATE mensualite SET id_mensualite_bailleur=? WHERE id=?');
		$req_update->execute(array($id_mensualite_bailleur, $donnees['0']));
	}
}


//sélection des dépenses et update des id_mensualite_bailleur
if ($mois_a_payer == "tous") {
	$req = $db->prepare("SELECT depense_bailleur.id 
		FROM `depense_bailleur`
		WHERE  depense_bailleur.id_bailleur=? AND depense_bailleur.id_mensualite_bailleur=0");
	$req->execute(array($_GET['id']));
} else {
	$req = $db->prepare("SELECT depense_bailleur.id 
		FROM `depense_bailleur`
		WHERE depense_bailleur.mois=? AND depense_bailleur.annee=? AND depense_bailleur.id_bailleur=? AND depense_bailleur.id_mensualite_bailleur=0");
	$req->execute(array($mois, $annee, $_GET['id']));
}

while ($donnees = $req->fetch()) {
	$req_update = $db->prepare('UPDATE depense_bailleur SET id_mensualite_bailleur=? WHERE id=?');
	$req_update->execute(array($id_mensualite_bailleur, $donnees['0']));
}

if ($type_paiement == "caisse") {
	//insertiondans la caisse
	$req = $db->prepare('INSERT INTO caisse_immo(type, section, motif, montant,date_operation,id_mensualite_bailleur, id_user) values ("sortie","Payement bailleur",?,?,?,?,?) ');
	$req->execute(array($motif, $montant, $date_versement, $id_mensualite_bailleur, $id_user)) or die(print_r($req->errorInfo()));
	$nbr = $req->rowCount();
} elseif ($type_paiement == "banque") {
	//insertiondans la banque
	$req = $db->prepare('INSERT INTO banque(type, section, motif, montant,date_operation,id_mensualite_bailleur, structure, num_cheque, id_user) values ("sortie","Payement bailleur",?,?,?,?,"immobilier",?,?) ');
	$req->execute(array($motif, $montant, $date_versement, $id_mensualite_bailleur, $num_cheque, $id_user)) or die(print_r($req->errorInfo()));
	$nbr = $req->rowCount();
} else {
	//insertiondans la caisse
	$req = $db->prepare('INSERT INTO caisse_immo(type, section, motif, montant,date_operation,id_mensualite_bailleur, id_user) values ("sortie","Payement bailleur",?,?,?,?,?) ');
	$req->execute(array($motif, $montant_caisse, $date_versement, $id_mensualite_bailleur, $id_user)) or die(print_r($req->errorInfo()));
	$nbr = $req->rowCount();
	//insertiondans la banque
	$req = $db->prepare('INSERT INTO banque(type, section, motif, montant,date_operation,id_mensualite_bailleur, structure, num_cheque, id_user) values ("sortie","Payement bailleur",?,?,?,?,"immobilier",?,?) ');
	$req->execute(array($motif, $montant_banque, $date_versement, $id_mensualite_bailleur, $num_cheque, $id_user)) or die(print_r($req->errorInfo()));
	$nbr = $req->rowCount();
}

if ($nbr > 0) {
?>
	<script type="text/javascript">
		alert('Mensualité du bailleur enregistrée');
		//window.location="l_bailleur.php";
	</script>
<?php
	header('location:i_mensualite_bailleur.php?id=' . $id_mensualite_bailleur);
	//header('location:l_bailleur.php');
} else {
?>
	<script type="text/javascript">
		alert('Erreur mensualité non enregistré');
		window.history.go(-1);
	</script>
<?php
}
