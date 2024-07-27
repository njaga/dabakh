<?php
$db->query("SET lc_time_names = 'fr_FR';");
$req=$db->prepare("SELECT CONCAT(day(mensualite_bailleur.date_versement), ' ', monthname(mensualite_bailleur.date_versement), ' ', year(mensualite_bailleur.date_versement)), mensualite_bailleur.mois, mensualite_bailleur.montant, mensualite_bailleur.id, bailleur.pourcentage
FROM `mensualite_bailleur`, bailleur
WHERE bailleur.id=mensualite_bailleur.id_bailleur AND bailleur.id=?");
$req->execute(array($_GET['id']));
while ($donnees=$req->fetch())
{
	
	$date_versement_bailleur=$donnees['0'];
	$mois_bailleur=$donnees['1'];
	$montant_bailleur=$donnees['2'];
	$id=$donnees['3'];
	$pourcentage=$donnees['4'];
	echo "<tr>";
		echo "<td>".$date_versement_bailleur."</td>";
		echo "<td>".$mois_bailleur."</td>";
		echo "<td>".number_format($montant_bailleur,0,'.',' ')." Fcfa</td>";
	echo "<td> <a target='_blank' class='btn' href='i_mensualite_bailleur.php?id=$id'><i class='material-icons left'>print</i> Reçu <b>N°".str_pad($id, 3, "0", STR_PAD_LEFT)."</b></a> </td>";
	echo "<td> <a class='btn red' href='s_mensualite_bailleur.php?id=$id' onclick='return(confirm(\"Voulez-vous supprimer cette mensualité ?\"))'><i class='material-icons left'>close</i></a> </td>";
	echo "</tr>";
}
