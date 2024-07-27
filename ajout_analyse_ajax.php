<?php
include 'connexion.php';
$p=$_POST['p'];
$req=$db->prepare('SELECT cout FROM analyse WHERE id=?');
$req->execute(array($_POST['analyse']));
$donnees=$req->fetch(); 
$cout=$donnees['0'];
$req->closeCursor();

$req=$db->prepare('SELECT count(*) FROM analyse_patient WHERE id_analyse=? AND id_patient=?');
$req->execute(array($_POST['analyse'],$_POST['patient']));
$donnees1=$req->fetch(); 
$nbr=$donnees1['0'];
$req->closeCursor();
if ($nbr>0) 
{
	$req=$db->prepare("SELECT  analyse.analyse, analyse.cout, analyse_patient.quantite, analyse_patient.montant, analyse_patient.id
	FROM analyse_patient, analyse 
	WHERE analyse_patient.id_analyse=analyse.id AND analyse_patient.id_patient=?");
	$req->execute(array($_POST['patient']));
	$nbr=$req->rowCount();

	while ($donnees_l_analyse=$req->fetch()) 
	{
		echo "<tr>";
			echo "<td>".$donnees_l_analyse['0']."</td>";
			echo "<td>".$donnees_l_analyse['1']."</td>";
			echo "<td>".$donnees_l_analyse['2']."</td>";
			echo "<td>".$donnees_l_analyse['3']."</td>";
			echo "<td><a href='supprimer_analyse.php?id_pat=".$_POST['patient']."&amp;id=".$donnees_l_analyse['4']."&amp;p=".$p."'><i class='material-icons red-text'>clear</i></a></td>";
		echo "</tr>";		
	}	

	$req=$db->prepare("SELECT SUM(analyse_patient.montant) FROM analyse_patient WHERE analyse_patient.id_patient=?");
	$req->execute(array($_POST['patient']));
	$donnees=$req->fetch();
		echo '<input type="hidden" name="cout" class="cout" id="cout" value="'.$donnees['0'].'">';
	echo "<tr class='grey'>";
		echo "<td>TOTAL</td>";			
		echo "<td></td>";			
		echo "<td></td>";			
		echo "<td><b>".number_format($donnees['0'],0,'.',' ')." Fcfa</b></td>";			
	echo "</tr>";
		?>
	<script type="text/javascript">
		alert("Analyse déja enregistré");
	</script>
	<?php
}
else
{
	$date_analyse=date('Y-m-d');
	$cout=$_POST['qt'] * $cout;
	$req=$db->prepare('INSERT INTO `analyse_patient` (`id_analyse`, `id_patient`, `quantite`, `montant`, `date_analyse`) VALUES (?, ?, ?, ?, ?) ');
	$req->execute(array($_POST['analyse'],$_POST['patient'], $_POST['qt'], $cout, $date_analyse)) or die($req->errorInfo());
	$req->closeCursor();

	$req=$db->prepare("SELECT  analyse.analyse, analyse.cout, analyse_patient.quantite, analyse_patient.montant, analyse_patient.id
	FROM analyse_patient, analyse 
	WHERE analyse_patient.id_analyse=analyse.id AND analyse_patient.id_patient=?");
	$req->execute(array($_POST['patient']));
	$nbr=$req->rowCount();

	while ($donnees_l_analyse=$req->fetch()) 
	{
		echo "<tr>";
			echo "<td>".$donnees_l_analyse['0']."</td>";
			echo "<td>".$donnees_l_analyse['1']."</td>";
			echo "<td>".$donnees_l_analyse['2']."</td>";
			echo "<td>".$donnees_l_analyse['3']."</td>";
			echo "<td><a href='supprimer_analyse.php?id_pat=".$_POST['patient']."&amp;id=".$donnees_l_analyse['4']."&amp;p=".$p."'><i class='material-icons red-text'>clear</i></a></td>";
		echo "</tr>";		
	}	

	$req=$db->prepare("SELECT SUM(analyse_patient.montant) FROM analyse_patient WHERE analyse_patient.id_patient=?");
	$req->execute(array($_POST['patient']));
	$donnees=$req->fetch();
		echo '<input type="hidden" name="cout" class="cout" id="cout" value="'.$donnees['0'].'">';
	echo "<tr class='grey'>";
		echo "<td>TOTAL</td>";			
		echo "<td></td>";			
		echo "<td></td>";			
		echo "<td><b>".number_format($donnees['0'],0,'.',' ')." Fcfa</b></td>";			
	echo "</tr>";
}
?>
