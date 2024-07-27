<?php
session_start();
	include 'connexion.php';
	$search=$_POST['search'];
	if ($search=="") 
	{
		$reponse=$db->query("SELECT id,prenom, nom, sexe, fonction, telephone, CONCAT(DATE_FORMAT(date_embauche, '%d'), '/', DATE_FORMAT(date_embauche, '%m'),'/', DATE_FORMAT(date_embauche, '%Y')), login, service, matricule, etat FROM personnel  ORDER BY etat,  nom ");
	}
	else
	{
		$reponse=$db->prepare("SELECT id,prenom, nom, sexe, fonction, telephone, CONCAT(DATE_FORMAT(date_embauche, '%d'), '/', DATE_FORMAT(date_embauche, '%m'),'/', DATE_FORMAT(date_embauche, '%Y')), login, service, matricule, etat FROM personnel  CONCAT(personnel.prenom,' ',' ',personnel.nom) like CONCAT('%', ?, '%') ORDER BY etat, nom ");
		$reponse->execute(array($search));
	}
	$resultat=$reponse->rowCount();
	while ($donnees= $reponse->fetch())
	{
	$id=$donnees['0'];
	$prenom=$donnees['1'];
	$nom=$donnees['2'];
	$sexe=$donnees['3'];
	$fonction=$donnees['4'];
	$telephone=$donnees['5'];
	$date_embauche=$donnees['6'];
	$login=$donnees['7'];
	$service=$donnees['8'];
	$matricule=$donnees['9'];
	$etat=$donnees['10'];
	if ($etat=="activer") 
	{
		echo "<tr>";
	}
	else
	{
		echo "<tr class='red lighten-3'>";
	}
			if ($_SESSION['fonction']=="administrateur")
			{
				echo "<td> <a class='tooltipped' data-position='top' data-delay='50' data-tooltip='cliquez ici pour modifier' href='m_personnel.php?id=$id'>".strtoupper($matricule)."</a></td>";
			}
			else
			{
				echo "<td></td>";
			}
			echo "<td>".$prenom."</td>";
			echo "<td>".$nom."</td>";
			echo "<td>".$fonction."</td>";
			echo "<td>".$date_embauche."</td>";
			echo "<td>".$telephone."</td>";
			echo "<td>".$login."</td>";
			echo "<td>".$service."</td>";
			echo "<td><a target='_blank' class='tooltipped btn' data-position='top' data-delay='50' data-tooltip='Consulter le dossier' href='dossier_personnel.php?id=".$id."'>
  <i class='material-icons'>folder_open</i>
            </a></td>";
			if ($_SESSION['fonction']=="administrateur")
			{
				echo "<td> <a class='btn red tooltipped' data-position='top' data-delay='50' data-tooltip='Supprimer' href='supprimer_personnel.php?id=$id'><i class='material-icons'>close</i></a></td>";
			}
	echo "</tr>";}
	
?>
<script type="text/javascript">
	$('.tooltipped').tooltip();
</script>