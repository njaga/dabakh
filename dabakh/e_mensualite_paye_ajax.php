<?php
session_start();
include 'connexion.php';
$annee=$_POST['annee'];
$id=$_POST['id'];

$db->query("SET lc_time_names = 'fr_FR';");
	$req=$db->prepare('SELECT mensualite.mois, CONCAT(day(mensualite.date_versement), " " , monthname(mensualite.date_versement), year(mensualite.date_versement)), mensualite.montant, logement.designation, logement.adresse, type_logement.type_logement, CONCAT(bailleur.prenom," ", bailleur.nom),  mensualite.id
FROM locataire, location, logement, type_logement, mensualite, bailleur
WHERE locataire.id=location.id_locataire AND location.id_logement=logement.id AND logement.id_type=type_logement.id AND location.id=mensualite.id_location AND logement.id_bailleur=bailleur.id  
AND locataire.id=? AND mensualite.annee=?');
	$req->execute(array($id, $annee));
	$nbr=$req->rowCount();
	if ($nbr>0) 
	{
		while ($donnees=$req->fetch())
		{
			
			$mois=$donnees['0'];
			$date_versement=$donnees['1'];
			$montant=$donnees['2'];
			$designation=$donnees['3'];
			$adresse=$donnees['4'];
			$type_logement=$donnees['5'];
			$bailleur=$donnees['6'];
			$id=$donnees['7'];
			echo "<tr>\n";
				echo "<td>".$date_versement."</td>\n";
				echo "<td>".$mois."</td>\n";
				echo "<td>".number_format($montant,0,'.',' ')." Fcfa</td>\n";
				echo "<td>".$type_logement." : ".$designation." à ".$adresse."</td>\n";
				echo "<td>".$bailleur."</td>\n";
				echo "<td> <a target='_blank' class='btn' href='i_mensualite.php?id=$id'><i class='material-icons left'></i> Facture</a> </td>";
			echo "</tr>\n";
		}
	}
	else
	{
		echo "<tr>";
		echo "<td colspan='3'><h5>Aucune mensualité enregistrée</h5></td>";
		echo "</tr>";
	}
?>