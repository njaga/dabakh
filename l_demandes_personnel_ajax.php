<?php	
session_start();				
include 'connexion.php';
$mois=$_POST['mois'];
$annee=$_POST['annee'];
$search=$_POST['search'];
$somme=0;
$db->query("SET lc_time_names = 'fr_FR';");
if ($search=="") 
{
	$reponse=$db->prepare("SELECT demandes_p_a.id, CONCAT(personnel.prenom,' ', personnel.nom), CONCAT(DATE_FORMAT(demandes_p_a.date_demande, '%d'), '/', DATE_FORMAT(demandes_p_a.date_demande, '%m'),'/', DATE_FORMAT(demandes_p_a.date_demande, '%Y')), demandes_p_a.type_demande, CONCAT(DATE_FORMAT(demandes_p_a.date_debut, '%d'), '/', DATE_FORMAT(demandes_p_a.date_fin, '%m'),'/', DATE_FORMAT(demandes_p_a.date_debut, '%Y')), CONCAT(DATE_FORMAT(demandes_p_a.date_debut, '%d'), '/', DATE_FORMAT(demandes_p_a.date_fin, '%m'),'/', DATE_FORMAT(demandes_p_a.date_fin, '%Y')), CONCAT(DATE_FORMAT(demandes_p_a.heure_debut,'%H'),'h:', DATE_FORMAT(demandes_p_a.heure_debut,'%i'), ' - ', DATE_FORMAT(demandes_p_a.heure_fin,'%H'),'h:', DATE_FORMAT(demandes_p_a.heure_fin,'%i')), demandes_p_a.id_user   
FROM `demandes_p_a`, personnel 
WHERE personnel.id=demandes_p_a.id_personnel AND month(demandes_p_a.date_demande)=? AND year(demandes_p_a.date_demande)=?");
	$reponse->execute(array($mois, $annee));
}
else
{
	$reponse=$db->prepare("SELECT demandes_p_a.id, CONCAT(personnel.prenom,' ', personnel.nom), CONCAT(DATE_FORMAT(demandes_p_a.date_demande, '%d'), '/', DATE_FORMAT(demandes_p_a.date_demande, '%m'),'/', DATE_FORMAT(demandes_p_a.date_demande, '%Y')), demandes_p_a.type_demande, CONCAT(DATE_FORMAT(demandes_p_a.date_debut, '%d'), '/', DATE_FORMAT(demandes_p_a.date_fin, '%m'),'/', DATE_FORMAT(demandes_p_a.date_debut, '%Y')), CONCAT(DATE_FORMAT(demandes_p_a.date_debut, '%d'), '/', DATE_FORMAT(demandes_p_a.date_fin, '%m'),'/', DATE_FORMAT(demandes_p_a.date_fin, '%Y')), CONCAT(DATE_FORMAT(demandes_p_a.heure_debut,'%H'),'h:', DATE_FORMAT(demandes_p_a.heure_debut,'%i'), ' - ', DATE_FORMAT(demandes_p_a.heure_fin,'%H'),'h:', DATE_FORMAT(demandes_p_a.heure_fin,'%i')), demandes_p_a.id_user  
FROM `demandes_p_a`, personnel 
WHERE personnel.id=demandes_p_a.id_personnel AND month(demandes_p_a.date_demande)=? AND year(demandes_p_a.date_demande)=? AND CONCAT(personnel.prenom,' ',personnel.nom) like CONCAT('%', ?, '%')");
	$reponse->execute(array($mois, $annee, $search));
}

$nbr=$reponse->rowCount();
if ($nbr>0) 
{
	while ($donnees= $reponse->fetch())
	{
		$id=$donnees['0'];
		$personnel=$donnees['1'];
		$date_demande=$donnees['2'];
		$type_demande=$donnees['3'];
		$date_debut=$donnees['4'];
		$date_fin=$donnees['5'];
		$heure=$donnees['6'];
		$id_user=$donnees['7'];

		if ($type_demande=="Demande d'explication") 
		{
			if ($_SESSION['fonction']=="administrateur" OR $_SESSION['fonction']=="daf") 
			{
				echo "<tr>";	
					echo "<td><a class='tooltipped' data-position='top' data-delay='50' data-tooltip='cliquez ici pour modifier' href='m_personnel_demandes.php?s=$id'>".$date_demande."</a></td>";
					
					echo "<td>".$personnel. "</td>";
					echo "<td>".ucfirst($type_demande)."</td>";
					if($type_demande=="Autorisation d'absence")
					{	
						echo "<td>".$heure."</td>";
					}
					else 
					{
						echo "<td>".$date_debut." au ".$date_fin."</td>";
					}
					
					if ($_SESSION['fonction']=="administrateur")
					{
						echo "<br><br> <a class='btn red' href='s_demandes_p_a.php?id=$id' onclick='return(confirm(\"Voulez-vous supprimer cette demande ?\"))'><i class='material-icons left'>close</i></a>";
					} 
					echo "</td>";
					echo "<td>";
					$req_pj=$db->prepare('SELECT * FROM pj_demandes WHERE id_demande=?');
						$req_pj->execute(array($id));
						$i=0;
						while ($donnees_pj=$req_pj->fetch()) 
						{
								echo "<a class='tooltipped' target='_blank' data-position='top' data-tooltip='".$donnees_pj['1']."' href='".$donnees_pj['2']."'>P.J ".$i."</a>";
								$i++;
								echo "<br>";
						}
					echo "</td>";
					if ($_SESSION['fonction']=="administrateur")
					{
						echo "<td>".$id_user. "</td>";
					}
					

				echo "</tr>";
			}
		}
		else
		{
			echo "<tr>";	
				echo "<td><a class='tooltipped' data-position='top' data-delay='50' data-tooltip='cliquez ici pour modifier' href='m_personnel_demandes.php?s=$id'>".$date_demande."</a></td>";
				
				echo "<td>".$personnel. "</td>";
				echo "<td>".ucfirst($type_demande)."</td>";
				if ($type_demande=="Demande de permission") 
				{
					echo "<td>".$date_debut." au ".$date_fin."</td>";
				}
				elseif($type_demande=="Autorisation d'absence")
				{	
					echo "<td>".$heure."</td>";
				}
				
				if ($_SESSION['fonction']=="administrateur")
				{
					echo "<br><br> <a class='btn red' href='s_demandes_p_a.php?id=$id' onclick='return(confirm(\"Voulez-vous supprimer cette demande ?\"))'><i class='material-icons left'>close</i></a>";
				} 
				echo "</td>";
				echo "<td>";
				$req_pj=$db->prepare('SELECT * FROM pj_demandes WHERE id_demande=?');
					$req_pj->execute(array($id));
					$i=0;
					while ($donnees_pj=$req_pj->fetch()) 
					{
							echo "<a class='tooltipped' target='_blank' data-position='top' data-tooltip='".$donnees_pj['1']."' href='".$donnees_pj['2']."'>P.J ".$i."</a>";
							$i++;
							echo "<br>";
					}
				echo "</td>";
				if ($_SESSION['fonction']=="administrateur")
				{
					echo "<td>".$id_user. "</td>";
				}
			echo "</tr>";

		}
	}
	
}
else
{
	echo "<tr><td></td><td></td><td></td><td><h3>Aucune demande ce mois ci </td></tr>";
}			
?>
<script type="text/javascript">
	$('.tooltipped').tooltip();
</script>