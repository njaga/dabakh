<?php
session_start();	
include 'connexion.php';
$search=$_POST['search'];
if ($search=="")  
{
	$reponse=$db->query("SELECT * FROM article WHERE etat='1' order by article ");
}
else
{
	$reponse=$db->prepare("SELECT * FROM article WHERE etat='1' AND article like CONCAT('%',?,'%') order by article");
	$reponse->execute(array($search));	
}
$i=0;
while ($donnees= $reponse->fetch()) 
{
	$i++;
	$id=$donnees['0'];
	$article=$donnees['1'];
	$pu=$donnees['2'];
	$qt=$donnees['3'];
	
	if ($qt<1) 
	{
		echo "<tr class='red lighten-3'>";
	}
	elseif ($qt<11) 
	{
		echo "<tr class='yellow lighten-3'>";
	}
	else
	{
		echo "<tr>";
	}
	echo "<td class='grey lighten-3'><b>".$i. "</b></td>";
	
      if ($_SESSION['fonction']=='administrateur')
      {
		echo "<td> 
		<a class='tooltipped red-text' data-position='top' data-delay='50' data-tooltip='supprimer' href='s_article.php?id=$id' onclick='return(confirm(\"Voulez-vous supprimer cette article  ?\"))'><i class='material-icons left'>close</i></a> 
		<a class='tooltipped' data-position='top' data-delay='50' data-tooltip='cliquez ici pour modifier' href='m_article.php?id=$id'><i class='material-icons left'>edit</i></a>
		</td>";
		
      }
      else
      {
      	echo "<td></td>";
      }
     
        
	echo "<td>".$article."</td>";
	echo "<td>".number_format($pu,0,'.',' ')." Fcfa</td>";
	echo "<td>".$qt."</td>";
	echo "<td><a class='tooltipped modal-trigger' data-position='top' data-delay='50' data-tooltip='cliquez ici pour ravitailler' href='#modal".$id."'>ravitailler</a></td>";
    

	echo"<td>";
	?>
	<!-- Modal Structure ajout article-->
	<div id="modal<?=$id ?>" class="modal">
		<div class="modal-content ">
			<form method="POST" action="e_ravitaillement_article_trmnt.php"   >
				<p class="center-align">Ravitaillement</p>
				<div class="row">
					<h5 class="col s12 center">
						<b><?=$article ?></b>
						<br>
						Quantité restante : <b><?=$qt ?> </b>
					</h5>
					<input type="number" name="id" id="" value="<?=$id ?>" hidden="">
					<input type="number" name="qt_restante" id="" value="<?=$qt ?>" hidden="">
					<div class="input-field  col s3" >
						<input id="qt" type="number" class="validate " name="qt" required>
						<label for="qt">Quantité</label>
					</div>
					<div class="input-field  col s3" >
						<input id="montant" type="number" class="validate" name="montant" required>
						<label for="montant">Prix d'achat</label>
					</div>
					<div class="input-field  col s4" >
						<input id="date_ravitaillement" type="date" class="validate  " value="<?= date('Y-m-d') ?>" name="date_ravitaillement" required>
						<label for="date_ravitaillement" class="active">Date ravitaillement</label>
					</div>
				</div>
				<div class="input-field  center-align">
					<button class="btn  waves-light blue darken-4" type="submit" name="envoyer">Ajouter
					<i class="material-icons right">send</i>
					</button>
				</div>
			</form>
		</div>
		<div class="modal-footer">
		<a href="#!" class="modal-close waves-effect waves-green btn btn-flat red">Annuler</a>
		</div>
	</div>
<?php
	echo"</td>";
	echo "<tr>";
	
}
if (!isset($id)) 
{
	echo "<tr><td colspan='4'><h3 class='center'>Aucun article enregistré</h3></td></tr>";
}
?>
<script type="text/javascript">
	$('.modal').modal();
	$('.tooltipped').tooltip();
	$('.datepicker').datepicker({
				autoClose: true,
				yearRange:[<?=(date('Y')-1) ?>,<?=(date('Y')+1) ?>],
				showClearBtn: true,
				i18n:{
					nextMonth: 'Mois suivant',
					previousMonth: 'Mois précédent',
					labelMonthSelect: 'Selectionner le mois',
					labelYearSelect: 'Selectionner une année',
					months: [ 'Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre' ],
					monthsShort: [ 'Jan', 'Fev', 'Mar', 'Avr', 'Mai', 'Jun', 'Jul', 'Aou', 'Sep', 'Oct', 'Nov', 'Dec' ],
					weekdays: [ 'Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi' ],
					weekdaysShort: [ 'Dim', 'Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam' ],
					weekdaysAbbrev: [ 'D', 'L', 'M', 'M', 'J', 'V', 'S' ],
					today: 'Aujourd\'hui',
					clear: 'Réinitialiser',
					cancel: 'Annuler',
					done: 'OK'
						
					},
					format: 'yyyy-mm-dd'
	});
</script>