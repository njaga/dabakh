<?php
include 'connexion.php';
$req=$db->prepare('SELECT pu FROM produit WHERE id=?');
$req->execute(array($_POST['produit']));
$donnees=$req->fetch(); 
$pu=$donnees['0'];
$req->closeCursor();

if (isset($_POST['p'])) 
{
	//consultation externe
	$req=$db->prepare('SELECT count(*) FROM vente_produit WHERE id_consultation_domicile=? AND id_produit=?');
	$req->execute(array($_POST['consultation'],$_POST['produit']));
	$donnees1=$req->fetch(); 
	$nbr=$donnees1['0'];
	$req->closeCursor();

	if ($nbr>0) 
	{
		$req=$db->prepare("SELECT  produit.produit, produit.pu, vente_produit.quantite,vente_produit.montant, produit.id
		FROM consultation_domicile, vente_produit, produit 
		WHERE consultation_domicile.id_consultation=vente_produit.id_consultation_domicile AND vente_produit.id_produit=produit.id AND consultation_domicile.id_consultation=?");
		$req->execute(array($_POST['consultation']));
		$nbr=$req->rowCount();

		while ($donnees_l_produits=$req->fetch()) 
		{
			echo "<tr>";
				echo "<td>".$donnees_l_produits['0']."</td>";
				echo "<td>".$donnees_l_produits['1']."</td>";
				echo "<td>".$donnees_l_produits['2']."</td>";
				echo "<td>".$donnees_l_produits['3']."</td>";
				if ($_POST['p']=="sd") 
				{
					echo "<td></td>";
				}
				else
				{	
					echo "<td><a href='supprimer_produit.php?id_cons=".$_POST['consultation']."&amp;id_prod=".$donnees_l_produits['4']."&amp;qt=".$donnees_l_produits['2']."'><i class='material-icons red-text'>clear</i></a></td>";
				}
			echo "</tr>";		
		}	

		$req=$db->prepare("SELECT SUM(vente_produit.montant) FROM vente_produit WHERE vente_produit.id_consultation_domicile=?");
		$req->execute(array($_POST['consultation']));
		$donnees=$req->fetch();
			echo '<input type="hidden" name="cout_produit" class="cout" id="cout" value="'.$donnees['0'].'">';
		echo "<tr class='grey'>";
			echo "<td>TOTAL</td>";			
			echo "<td></td>";			
			echo "<td></td>";			
			echo "<td><b>".number_format($donnees['0'],0,'.',' ')." Fcfa</b></td>";			
		echo "</tr>";
			?>
		<script type="text/javascript">
			alert("Produit déja enregistré");
		</script>
		<?php
	}
	else
	{
		$req=$db->prepare('INSERT INTO vente_produit (id_consultation_domicile, id_produit, quantite, montant) VALUES (?,?,?, ?)');
		$req->execute(array($_POST['consultation'], $_POST['produit'], $_POST['qt'], $_POST['qt'] * $pu)) or die($req->errorInfo());
		$req->closeCursor();
		//dimunition de la quantité du produit
		$req=$db->prepare('UPDATE produit SET qt=qt-? WHERE id=?');
		$req->execute(array($_POST['qt'], $_POST['produit'])) or die($req->errorInfo());
		$req->closeCursor();
		$req=$db->prepare("SELECT  produit.produit, produit.pu, vente_produit.quantite,vente_produit.montant, produit.id
			FROM consultation_domicile, vente_produit, produit
			WHERE consultation_domicile.id_consultation=vente_produit.id_consultation_domicile AND vente_produit.id_produit=produit.id AND consultation_domicile.id_consultation=?");
		$req->execute(array($_POST['consultation']));
		$nbr=$req->rowCount();

		while ($donnees_l_produits=$req->fetch()) 
		{
			echo "<tr>";
				echo "<td>".$donnees_l_produits['0']."</td>";
				echo "<td>".$donnees_l_produits['1']."</td>";
				echo "<td>".$donnees_l_produits['2']."</td>";
				echo "<td>".$donnees_l_produits['3']."</td>";
				if (isset($_POST['p']) AND $_POST['p']=="sd") 
					{
						echo "<td></td>";
					}
					else
					{	
						echo "<td><a href='supprimer_produit.php?id_cons=".$_POST['consultation']."&amp;id_prod=".$donnees_l_produits['4']."&amp;qt=".$donnees_l_produits['2']."'><i class='material-icons red-text'>clear</i></a></td>";
					}			echo "</tr>";		
		}	

		$req=$db->prepare("SELECT SUM(vente_produit.montant) FROM vente_produit WHERE vente_produit.id_consultation_domicile=?");
		$req->execute(array($_POST['consultation']));
		$donnees=$req->fetch();
			echo '<input type="hidden" name="cout_produit" class="cout" id="cout" value="'.$donnees['0'].'">';
		echo "<tr class='grey'>";
			echo "<td>TOTAL</td>";			
			echo "<td></td>";			
			echo "<td></td>";			
			echo "<td><b>".number_format($donnees['0'],0,'.',' ')." Fcfa</b></td>";			
		echo "</tr>";
	}
}
else
{
	//Consultation en interne
	$req=$db->prepare('SELECT count(*) FROM vente_produit WHERE id_consultation=? AND id_produit=?');
	$req->execute(array($_POST['consultation'],$_POST['produit']));
	$donnees1=$req->fetch(); 
	$nbr=$donnees1['0'];
	$req->closeCursor();
	if ($nbr>0) 
	{
		$req=$db->prepare("SELECT  produit.produit, produit.pu, vente_produit.quantite,vente_produit.montant, produit.id
		FROM consultation, vente_produit, produit 
		WHERE consultation.id_consultation=vente_produit.id_consultation AND vente_produit.id_produit=produit.id AND consultation.id_consultation=?");
		$req->execute(array($_POST['consultation']));
		$nbr=$req->rowCount();

		while ($donnees_l_produits=$req->fetch()) 
		{
			echo "<tr>";
				echo "<td>".$donnees_l_produits['0']."</td>";
				echo "<td>".$donnees_l_produits['1']."</td>";
				echo "<td>".$donnees_l_produits['2']."</td>";
				echo "<td>".$donnees_l_produits['3']."</td>";
				echo "<td><a href='supprimer_produit.php?id_cons=".$_POST['consultation']."&amp;id_prod=".$donnees_l_produits['4']."&amp;qt=".$donnees_l_produits['2']."'><i class='material-icons red-text'>clear</i></a></td>";
			echo "</tr>";		
		}	

		$req=$db->prepare("SELECT SUM(vente_produit.montant) FROM vente_produit WHERE vente_produit.id_consultation=?");
		$req->execute(array($_POST['consultation']));
		$donnees=$req->fetch();
			echo '<input type="hidden" name="cout_produit" class="cout" id="cout" value="'.$donnees['0'].'">';
		echo "<tr class='grey'>";
			echo "<td>TOTAL</td>";			
			echo "<td></td>";			
			echo "<td></td>";			
			echo "<td><b>".number_format($donnees['0'],0,'.',' ')." Fcfa</b></td>";			
		echo "</tr>";
			?>
		<script type="text/javascript">
			alert("Produit déja enregistré");
		</script>
		<?php
	}
	else
	{
		$req=$db->prepare('INSERT INTO vente_produit (id_consultation, id_produit, quantite, montant) VALUES (?,?,?, ?)');
		$req->execute(array($_POST['consultation'], $_POST['produit'], $_POST['qt'], $_POST['qt'] * $pu)) or die($req->errorInfo());
		$req->closeCursor();
		//dimunition de la quantité du produit
		$req=$db->prepare('UPDATE produit SET qt=qt-? WHERE id=?');
		$req->execute(array($_POST['qt'], $_POST['produit'])) or die($req->errorInfo());

		$req=$db->prepare("SELECT  produit.produit, produit.pu, vente_produit.quantite,vente_produit.montant, produit.id
		FROM consultation, vente_produit, produit 
		WHERE consultation.id_consultation=vente_produit.id_consultation AND vente_produit.id_produit=produit.id AND consultation.id_consultation=?");
		$req->execute(array($_POST['consultation']));
		$nbr=$req->rowCount();

		while ($donnees_l_produits=$req->fetch()) 
		{
			echo "<tr>";
				echo "<td>".$donnees_l_produits['0']."</td>";
				echo "<td>".$donnees_l_produits['1']."</td>";
				echo "<td>".$donnees_l_produits['2']."</td>";
				echo "<td>".$donnees_l_produits['3']."</td>";
				echo "<td><a href='supprimer_produit.php?id_cons=".$_POST['consultation']."&amp;id_prod=".$donnees_l_produits['4']."&amp;qt=".$donnees_l_produits['2']."'><i class='material-icons red-text'>clear</i></a></td>";
			echo "</tr>";		
		}	

		$req=$db->prepare("SELECT SUM(vente_produit.montant) FROM vente_produit WHERE vente_produit.id_consultation=?");
		$req->execute(array($_POST['consultation']));
		$donnees=$req->fetch();
			echo '<input type="hidden" name="cout" class="cout" id="cout" value="'.$donnees['0'].'">';
		echo "<tr class='grey'>";
			echo "<td>TOTAL</td>";			
			echo "<td></td>";			
			echo "<td></td>";			
			echo "<td><b>".number_format($donnees['0'],0,'.',' ')." Fcfa</b></td>";			
		echo "</tr>";
	}	
}




?>
