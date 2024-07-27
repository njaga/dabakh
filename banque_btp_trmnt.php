<?php
session_start();
include 'connexion.php';
include 'supprim_accents.php';
$type=htmlspecialchars($_POST['type']);
$section=htmlspecialchars($_POST['section']);
$motif=htmlspecialchars(ucfirst(strtolower(suppr_accents($_POST['motif']))));
$num_cheque=htmlspecialchars(strtoupper($_POST['num_cheque']));
$montant=htmlspecialchars($_POST['montant']);
$date_operation=htmlspecialchars($_POST['date_operation']);
$pj=htmlspecialchars($_POST['pj']);
$id_user=$_SESSION['prenom']." ".$_SESSION['nom'];
$structure="btp";

$req=$db->prepare('INSERT INTO banque(type, section, motif, num_cheque, montant,date_operation, structure, id_user, pj) values (?,?,?,?,?,?,?,?,?) ');
$nbr=$req->execute(array($type, $section, $motif, $num_cheque, $montant, $date_operation, $structure, $id_user, $pj )) or die(print_r($req->errorInfo()));
if ($nbr>0) 
{
	if ($section=="Approvisionnement caisse par banque") 
	{
		$type="entree";
		$section="Approvisionnement caisse par banque";
		$motif="Approvisionnement de la caisse par la banque";
	
		$req=$db->prepare('INSERT INTO caisse_btp(type, section, motif, montant,date_operation, id_user) values (?,?,?,?,?,?) ');
		$nbr=$req->execute(array($type, $section, $motif, $montant, $date_operation, $id_user)) or die(print_r($req->errorInfo()));
	}
	?>
	<script type="text/javascript">
		alert('Opération enregistrée');
		window.history.go(-2);
	</script>
	<?php
}
else
{
?>
<script type="text/javascript">
	alert('Erreur operation non enregistrée');
	window.history.go(-1);
</script>
<?php
}
?>