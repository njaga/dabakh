<?php
session_start();
include 'connexion.php';
$type=$_POST['type'];
$montant=htmlspecialchars($_POST['montant']);
$mois=htmlspecialchars($_POST['mois']);
$annee=htmlspecialchars($_POST['annee']);
$date_versement=$_POST['date_versement'];
$id_user1=substr($_SESSION['prenom'], 0,1).".".substr($_SESSION['nom'], 0,1);
$id_user=$_SESSION['prenom']." ".$_SESSION['nom'];

//début condition reliquat
if ($type=="reliquat") 
{
	//vérification de doublons
	$req=$db->prepare('SELECT mensualite.id, mensualite.montant, location.prix_location  
FROM `mensualite`, location 
WHERE mensualite.id_location=location.id AND 
location.prix_location>=mensualite.montant AND annee=? AND mois=? AND id_location=?');
	$req->execute(array($annee, $mois, $_GET['id'])) or die(print_r($req->errorInfo()));
	$donnee=$req->fetch();
	$id_mensualite=$donnee['0'];
	$montant_mensuel=$donnee['1']+$montant;
	$prix_location=$donnee['2'];
	if (isset($id_mensualite))
	{
		if ($prix_location<$montant_mensuel) 
		{
			?>
			<script type="text/javascript">
				alert("Erreur le montant du reliquat est inférieur au montant saisie");
				window.history.go(-1);
			</script>
		<?php	
		}
		/*
		elseif ($prix_location==$montant_mensuel) 
		{
			$type="complet";
		}
		else
		{
			$type="avance";
		}
		$req=$db->prepare('UPDATE mensualite SET montant=?, type=? 
		WHERE id=?');
		$req->execute(array($montant_mensuel, $tye, $id_mensualite)) or die(print_r($req->errorInfo()));
		$nbr=$req->rowCount();
		*/
	}
	else
	{
		?>
			<script type="text/javascript">
				alert("Erreur aucun reliquat pour cette mensualité n'est à payer mois ci");
				window.history.go(-1);
			</script>
		<?php	
	}
}
// fin condition reliquat

//début condition si non reliquat (avance ou complet)
elseif ($type!="reliquat") 
{
	//vérification de doublons
	$req=$db->prepare('SELECT mensualite.id, mensualite.montant FROM `mensualite` 
	WHERE annee=? AND mois=? AND id_location=?');
	$req->execute(array($annee, $mois, $_GET['id'])) or die(print_r($req->errorInfo()));
	$donnee=$req->fetch();
	$id_mensualite=$donnee['0'];
	if (isset($id_mensualite)) 
	{
		?>
			<script type="text/javascript">
				alert('Erreur mensualité déjà enregistré pour cette location');
				window.history.go(-1);
			</script>
		<?php
	}
	
	
}

//Fin condition si non reliquat (avance ou complet)

//vérification de l'établissement de la facture

$reponse=$db->query('SELECT MAX(id) FROM mensualite');
$donnee=$reponse->fetch();
$id_mensualite=$donnee['0']+1;
$req=$db->prepare('INSERT INTO mensualite(id, montant, mois, annee, date_versement, type, id_location, id_user) values (?,?,?,?,?,?,?,?) ');
$req->execute(array($id_mensualite,$montant, $mois, $annee, $date_versement, $type, $_GET['id'], $id_user1)) or die(print_r($req->errorInfo()));
$nbr=$req->rowCount();
$req->closeCursor();
	
//recupération des infos du locataire
$req=$db->prepare("SELECT CONCAT(locataire.prenom,' ', locataire.nom), CONCAT(logement.designation,' situé à ',logement.adresse)
FROM logement, location, locataire 
WHERE logement.id=location.id_logement AND location.id_locataire=locataire.id AND location.id=?");
$req->execute(array($_GET['id']));
$donnees=$req->fetch();
$req->closeCursor();
if ($type=="avance") 
{
	$motif="Avance loyer ".$mois." de ".$donnees['0'];
}
elseif($type=="reliquat")
{

$motif="Reliquat loyer ".$mois." de ".$donnees['0'];
}
else
{
$motif="Loyer ".$mois." de ".$donnees['0'];

}
// insertion dans la caisse
$req=$db->prepare('INSERT INTO caisse_immo(type, section, motif, montant,date_operation, id_mensualite, id_user) values ("entree","Reglement mensualite",?,?,?,?,?) ');
$req->execute(array($motif, $montant, $date_versement, $id_mensualite, $id_user)) or die(print_r($req->errorInfo()));
$nbr=$req->rowCount();
if ($nbr>0)
{
	header('location:i_mensualite.php?id='.$id_mensualite);
}
else
{	
	?>
	<script type="text/javascript">
		alert('Erreur mensualité non enregistré');
		window.location="e_mensualite.php";
	</script>
	<?php
}

