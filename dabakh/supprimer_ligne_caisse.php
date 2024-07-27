<?php
include 'connexion.php';
//santé
if (isset($_GET['id_caisse_sante'])) 
{
	$req=$db->prepare('DELETE FROM caisse_sante  WHERE id_operation=?');
	$req->execute(array($_GET['id_caisse_sante'])) or die(print_r($req->errorInfo()));;
	$req->fetch();
	$req->closeCursor();
	if (isset($_GET['id_consultation'])) 
	{
		$req=$db->prepare('UPDATE  consultation SET reglement="non"  WHERE id_consultation=?');
		$req->execute(array($_GET['id_consultation'])) or die(print_r($req->errorInfo()));;
		$req->fetch();
		$req->closeCursor();
	}
	elseif (isset($_GET['id_consultation_domicile'])) 
	{
		$req=$db->prepare('UPDATE  consultation_domicile SET reglement="non"  WHERE id_consultation=?');
		$req->execute(array($_GET['id_consultation_domicile'])) or die(print_r($req->errorInfo()));;
		$req->fetch();
		$req->closeCursor();
	}
	elseif (isset($_GET['id_patient_externe'])) 
	{
		$req=$db->prepare('UPDATE  patient_externe SET reglement="Non régler"  WHERE id=?');
		$req->execute(array($_GET['id_patient_externe'])) or die(print_r($req->errorInfo()));;
		$req->fetch();
		$req->closeCursor();
	}
	?>
	<script type="text/javascript">
		alert("Opération supprimée !");
		window.location='etat_caisse_sante.php';
	</script>
	<?php
	
}

//Immobilier

if (isset($_GET['id_caisse_immo'])) 
{
	
	$req=$db->prepare('DELETE FROM caisse_immo  WHERE id=?');
	$req->execute(array($_GET['id_caisse_immo'])) or die(print_r($req->errorInfo()));;
	$req->fetch();
	$req->closeCursor();

	?>
	<script type="text/javascript">
		alert("Opération supprimée !");
		window.location='etat_caisse_immo.php';
	</script>
	<?php
}

//COmmerce

if (isset($_GET['id_caisse_cm'])) 
{
	
	$req=$db->prepare('DELETE FROM caisse_commerce  WHERE id=?');
	$req->execute(array($_GET['id_caisse_cm'])) or die(print_r($req->errorInfo()));;
	$req->fetch();
	$req->closeCursor();

	?>
	<script type="text/javascript">
		alert("Opération supprimée !");
		window.history.go(-1);
	</script>
	<?php
}

//caution
if (isset($_GET['id_caisse_caution'])) 
	{
		$req=$db->prepare('DELETE FROM caisse_caution  WHERE id=?');
		$req->execute(array($_GET['id_caisse_caution'])) or die(print_r($req->errorInfo()));;
		$req->fetch();
		$req->closeCursor();
		?>
		<script type="text/javascript">
			alert("Opération supprimée !");
			window.location='etat_caisse_caution.php';
		</script>
		<?php
	}


//BTP


//Immobilier

if (isset($_GET['id_caisse_btp'])) 
{
	
	$req=$db->prepare('DELETE FROM caisse_btp  WHERE id=?');
	$req->execute(array($_GET['id_caisse_btp'])) or die(print_r($req->errorInfo()));;
	$req->fetch();
	$req->closeCursor();

	?>
	<script type="text/javascript">
		alert("Opération supprimée !");
		window.location='etat_caisse_btp.php';
	</script>
	<?php
}




?>

