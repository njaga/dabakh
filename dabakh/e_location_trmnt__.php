<?php
include 'connexion.php';
include 'supprim_accents.php';
$prenom=htmlspecialchars(strtoupper(suppr_accents($_POST['prenom'])));
$nom=htmlspecialchars(strtoupper(suppr_accents($_POST['nom'])));
$telephone=htmlspecialchars($_POST['telephone']);
$num_dossier=$_POST['num_dossier'];
$annee_inscription=$_POST['annee_inscription'];
$logement=htmlspecialchars($_GET['id']);
$date_debut=htmlspecialchars($_POST['date_debut']);
$caution=htmlspecialchars($_POST['caution']);
$prix_location=htmlspecialchars($_POST['prix_location']);
$commision=htmlspecialchars($_POST['commision']);
$cni=htmlspecialchars($_POST['cni']);

$verif=0;
$db->beginTransaction();
//insertion du locataire
$req=$db->prepare('INSERT INTO locataire(prenom, nom, tel, cni, annee_inscription, num_dossier, statut ) values (?,?,?,?,?,?,?) ');
$req->execute(array($prenom, $nom, $telephone, $cni, $annee_inscription, $num_dossier, "actif")) or die(print_r($req->errorInfo()));
$locataire=$db->lastInsertId();
$result=$req->rowCount();
if ($result<1) {$verif=1;}
$req->closeCursor();

//insertion de la location
$req=$db->prepare('INSERT INTO location(date_debut, caution, commission, prix_location, id_logement, id_locataire, etat) values (?,?,?,?,?,?,"active") ');
$req->execute(array($date_debut, $caution, $commision, $prix_location, $logement, $locataire)) or die(print_r($req->errorInfo()));
$id_location=$db->lastInsertId();
$result=$req->rowCount();
if ($result<1) {$verif=2;}
$req->closeCursor();

//Selection du logement choisi et décrémentation
$req=$db->prepare('SELECT * FROM logement WHERE id=?');
$req->execute(array($_GET['id'])) or die($req->errorInfo());
$donnees=$req->fetch();
$nbr=$donnees['4']-1;
$nbr_occupe=$donnees['5']+1;
if ($result<1) {$verif=3;}
$req->closeCursor();
$req=$db->prepare('UPDATE logement SET nbr=?, nbr_occupe=? WHERE id=?');
$req->execute(array($nbr, $nbr_occupe, $_GET['id'])) or die($req->errorInfo());
if ($result<1) {$verif=4;}
$req->closeCursor();
//FIN


$motif="Commision du locataire ".$prenom." ".$nom;
$req=$db->prepare('INSERT INTO caisse_immo (type, section, motif, date_operation, montant, id_location) VALUES ("entree","Commision",?,?,?,?)');
$req->execute(array($motif, $date_debut, $commision, $id_location)) or die($req->errorInfo());
if ($result<1) {$verif=5;}
$req->closeCursor();

$motif="Caution du locataire ".$prenom." ".$nom;
$req=$db->prepare('INSERT INTO caisse_caution (type, section, motif, date_operation, montant, id_location) VALUES ("entree","Caution",?,?,?,?)');
$req->execute(array($motif, $date_debut, $caution, $id_location)) or die($req->errorInfo());

//premier mensualite
$reponse=$db->query('SELECT MAX(id) FROM mensualite');
$donnee=$reponse->fetch();
$id_mensualite=$donnee['0']+1;
//mois
$reponse=$db->prepare('SELECT monthname(?)');
$reponse->execute(array($date_debut)) or die(print_r($req->errorInfo()));
$donnee=$reponse->fetch();
$mois=$donnee['0'];

$id_mensualite=$donnee['0']+1;
$req=$db->prepare('INSERT INTO mensualite(id, montant, mois, annee, date_versement, type, id_location) values (?,?,?,?,?,"complet",?) ');
$req->execute(array($id_mensualite,$caution, $mois, $annee, $date_debut, $id_location)) or die(print_r($req->errorInfo()));
$nbr=$req->rowCount();
$req->closeCursor();

$motif="Avance premier mois loyer du locataire ".$prenom." ".$nom;
$req=$db->prepare('INSERT INTO caisse_immo (type, section, motif, date_operation, montant, id_location) VALUES ("entree","Reglement facture",?,?,?,?)');
$req->execute(array($motif, $date_debut, $caution, $id_location)) or die($req->errorInfo());
if ($result<1) {$verif=6;}

if ($verif==0) 
{
	$db->commit();
	?>
	<script type="text/javascript">
		alert('Dossier enregistré');
		//window.location="l_location.php";
	</script>
	<?php
	header('location:i_fac_ins_loc.php?id='.$id_location);
}
else
{
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
