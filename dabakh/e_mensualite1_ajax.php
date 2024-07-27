<?php
include 'connexion.php';

//Recupération des informations sur les mensualités 
$req=$db->prepare('SELECT CONCAT(day(mensualite.date_versement)," ", monthname(mensualite.date_versement)," ", year(mensualite.date_versement)), CONCAT(mensualite.mois," ", mensualite.annee), mensualite.montant, mensualite.type, mois
FROM `mensualite` 
WHERE id_location=? AND mensualite.annee=? ORDER BY date_versement ASC');
$req->execute(array($_POST['id'], $_POST['annee']));
$montant_mensuel=$_POST['montant'];

$total_verse=0;
$total_reliquat=0;
$mois_precedent="";
$avance_precedent="";
	while ($donnees=$req->fetch()) 
	{
		echo "<tr>";
			echo "<td>".$donnees['1']."</td>";
			echo "<td>".$donnees['0']."</td>";
			echo "<td>".number_format($donnees['2'],0,'.',' ')."</td>";
			if (($donnees['3'])=="avance") 
			{	
				if($mois_precedent==$donnees['4'])
				{
					$avance_precedent=$avance_precedent + $donnees['2'];
					echo "<td>".number_format(($montant_mensuel-$avance_precedent),0,'.',' ')."</td>";
				}
				else
				{
					$avance_precedent=$donnees['2'];
					$mois_precedent=$donnees['4'];
					echo "<td>".number_format(($montant_mensuel-$donnees['2']),0,'.',' ')."</td>";
				}
				$total_reliquat=$total_reliquat+($montant_mensuel-$donnees['2']);
			}
			else
			{
				echo "<td>0 Fcfa</td>";
			}
			
		echo "</tr>";
		$total_verse=$total_verse+$donnees['2'];
	}
	echo "<tr class='grey white-text bold'>";
		echo"<td colspan='2'><b>TOTAL</b></td>";
		echo "<td><b>".number_format($total_verse,0,'.',' ')." Fcfa</b></td>";
		echo "<td><b>".number_format($total_reliquat,0,'.',' ')." Fcfa</b></td>";

	echo "</tr>";
?>


