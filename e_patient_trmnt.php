<?php
session_start();
include 'connexion.php';
include 'supprim_accents.php';
$prenom=htmlspecialchars(ucfirst(strtolower(suppr_accents($_POST['prenom']))));
$nom=htmlspecialchars(strtoupper(suppr_accents($_POST['nom'])));
$num_dossier=htmlspecialchars($_POST['num_dossier']);
$annee_inscription=htmlspecialchars($_POST['annee_inscription']);
$date_naissance=htmlspecialchars($_POST['date_naissance']);
$telephone=htmlspecialchars($_POST['telephone']);
$situation_matrimoniale=htmlspecialchars($_POST['situation_matrimoniale']);
$sexe=htmlspecialchars($_POST['sexe']);
$lieu_naissance=htmlspecialchars(strtoupper(suppr_accents($_POST['lieu_naissance'])));
$domicile=htmlspecialchars(strtoupper(suppr_accents($_POST['domicile'])));
$profession=htmlspecialchars(strtoupper(suppr_accents($_POST['profession'])));


$req=$db->prepare('INSERT INTO patient(prenom, nom, date_naissance, lieu_naissance, telephone, domicile, profession, sexe, situation_matrimoniale, num_dossier, annee_inscription) values (?,?,?,?,?,?,?,?,?,?,?) ');
$nbr=$req->execute(array($prenom, $nom, $date_naissance, $lieu_naissance, $telephone, $domicile, $profession, $sexe, $situation_matrimoniale, $num_dossier, $annee_inscription)) or die(print_r($req->errorInfo()));
if ($nbr>0) {
?>
<script type="text/javascript">
	alert('Dossier patient crée');
	<?php
	if ($_SESSION['fonction']=="infirmier") 
	{
		?>
		window.location="l_patient_cons_d.php";
		<?php
	}
	else 
	{
		?>
		window.location="l_patient.php";
		<?php
	}
	?>	
</script>
<?php
}
else
{
?>
<script type="text/javascript">
	alert('Erreur dossier non crée');
	window.location="list_patient.php";
</script>
<?php
}
?>