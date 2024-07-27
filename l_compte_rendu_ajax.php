<?php	
session_start();				
include 'connexion.php';
$mois=$_POST['mois'];
$annee=$_POST['annee'];
$id_personnel=$_POST['id_personnel'];
$db->query("SET lc_time_names = 'fr_FR';");

if ($_SESSION['fonction']=="administrateur") 
{
	$reponse=$db->prepare("SELECT compte_rendu.id, CONCAT(day(compte_rendu.date_redaction),' ',monthname(compte_rendu.date_redaction),' ',year(compte_rendu.date_redaction)), CONCAT(personnel.prenom,' ', personnel.nom), compte_rendu.compte_rendu
	FROM `compte_rendu`, personnel 
	WHERE compte_rendu.id_personnel=personnel.id AND month(compte_rendu.date_redaction)=? AND YEAR(compte_rendu.date_redaction)=?
	ORDER BY compte_rendu.date_redaction DESC");
	$reponse->execute(array($mois, $annee));
}
else
{
	$reponse=$db->prepare("SELECT compte_rendu.id, CONCAT(day(compte_rendu.date_redaction),' ',monthname(compte_rendu.date_redaction),' ',year(compte_rendu.date_redaction)), CONCAT(personnel.prenom,' ', personnel.nom), compte_rendu.compte_rendu
	FROM `compte_rendu`, personnel 
	WHERE compte_rendu.id_personnel=personnel.id AND month(compte_rendu.date_redaction)=? AND YEAR(compte_rendu.date_redaction)=? AND personnel.id=?
	ORDER BY compte_rendu.date_redaction DESC");
	$reponse->execute(array($mois, $annee, $_SESSION['id']));
}

$nbr=$reponse->rowCount();
if ($nbr>0) 
{
	while ($donnees= $reponse->fetch())
	{
		$id=$donnees['0'];
		$date_redaction=$donnees['1'];
		$personnel=$donnees['2'];
		$compte_rendu=$donnees['3'];
		
		echo "<tr>";
		if ($_SESSION['fonction']=="administrateur") 
		{
			echo "<td><a class='tooltipped' data-position='top' data-delay='50' data-tooltip='cliquez ici pour modifier' href='m_compte_rendu.php?id=$id'>".$date_redaction."</a></td>>";
		}
		else
		{
			echo "<td>".$date_redaction."</td>";
		}
		echo "<td>".$personnel."</td>";
		echo "<td><a class='tooltipped waves-effect waves-light  modal-trigger' data-position='top' data-delay='50' data-tooltip='Consulter' href='#modal_".$id."'>Consulter</a>";            
		if ($_SESSION['fonction']=="administrateur") 
		{
			echo "<td><a class='tooltipped btn red' data-position='top' data-delay='50' data-tooltip='supprimer' href='s_compte_rendu.php?id=$id' onclick='return(confirm(\"Voulez-vous supprimer ce compte rendu ?\"))'><i class='material-icons left'>close</i></a></td>";
		}
		echo"<td>";
		?>
		<!-- Modal Structure compte rendu-->
		<div id="modal_<?= $id ?>" class="modal">
				<div class="modal-content ">
					<h4>Compte rendu du <?=$date_redaction ?></h4>
					<?php echo nl2br($compte_rendu) ?>
				</div>
				<div class="modal-footer">
				<a href="#!" class="modal-close waves-effect waves-green btn btn-flat">Fermer</a>
				</div>
			</div>
		<?php
		echo"</td>";
		echo "</tr>";
	}
	
}
else
{
	echo "<tr><td></td><td></td><td><h3>Aucun compte rendu ce mois ci </td></tr>";
}			
?>
<script type="text/javascript">
	$('.tooltipped').tooltip();
    $('.modal').modal();
</script>