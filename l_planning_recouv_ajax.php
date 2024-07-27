<?php
session_start();
include 'connexion.php';
$db->query("SET lc_time_names = 'fr_FR';");

$mois=$_POST['mois'];
$annee=$_POST['annee'];
$total=0;
if($_SESSION['fonction']=="administrateur")
{
    $reponse=$db->prepare("SELECT id, CONCAT(day(date_debut),' ',monthname(date_debut),' ',YEAR(date_debut)), CONCAT(day(date_fin),' ',monthname(date_fin),' ',YEAR(date_fin)), compte_rendu FROM `planning_recouvrement` WHERE month(date_debut)=? AND YEAR(date_debut)=? ORDER BY date_debut ASC");
    $reponse->execute(array($mois, $annee));	
}
else
{
    $reponse=$db->prepare("SELECT id, CONCAT(day(date_debut),' ',monthname(date_debut),' ',YEAR(date_debut)), CONCAT(day(date_fin),' ',monthname(date_fin),' ',YEAR(date_fin)), compte_rendu FROM `planning_recouvrement` WHERE month(date_debut)=? AND YEAR(date_debut)=? AND id_agent=? ORDER BY date_debut ASC");
    $reponse->execute(array($mois, $annee, $_SESSION['id']));
}

$resultat=$reponse->rowCount();
if ($resultat<1)
{
	echo "<tr><td colspan='7'><h3 class='center'>Aucun résultat</h3></td></tr>";
}
$i=0;
while ($donnees= $reponse->fetch())
{
$id=$donnees['0'];
$date_debut=$donnees['1'];
$date_fin=$donnees['2'];
$compte_rendu=$donnees['3'];
$i++;
echo "<tr>";
	if ($_SESSION['fonction']=="administrateur") 
	{										
		echo "<td class='grey lighten-3'> <a class='tooltipped ' data-position='top' data-delay='50' data-tooltip='cliquez ici pour modifier' href='m_planning.php?id=$id'><b>".$i."</a></td>";
	}
	else
	{
		echo "<td class='grey lighten-3'><b>".$i."</td>";		
	}
    echo "<td>Du <b>".$date_debut."</b> au <b>".$date_fin."</b></td>";
    echo "<td><a class='tooltipped waves-effect waves-light  modal-trigger' data-position='top' data-delay='50' data-tooltip='Afficher la liste de recouvrement' href='#modal_".$id."'>Détail(s)</a>";
    echo "<td><a class='tooltipped waves-effect waves-light  modal-trigger' data-position='top' data-delay='50' data-tooltip='Afficher le compte rendu' href='#modal__".$id."'>Compte rendu</a>";
    if ($_SESSION['fonction']=="administrateur") 
	{
		echo "<td> <a onclick='return(confirm(\"Voulez-vous supprimer ce planning ?\"))' class='red btn' href='s_loc_planning.php?s=$id'>Supprimer</a></td>";
    }
    ?>
        <td>
        <!-- Modal Structure détails-->
        <div id="modal_<?=$id ?>" class="modal">
            <div class="modal-content ">
                <h4>Liste des recouvrements à faire</h4>
                <?php
                    $req_loc=$db->prepare("SELECT CONCAT(locataire.prenom,' ', locataire.nom), CONCAT(bailleur.prenom,' ', bailleur.nom), type_logement.type_logement, logement.adresse
                    FROM `planning_recouvrement`
                    INNER JOIN planning_recouv_locataire ON planning_recouv_locataire.id_planning=planning_recouvrement.id
                    INNER JOIN locataire ON planning_recouv_locataire.id_locataire=locataire.id
                    INNER JOIN location ON location.id_locataire=locataire.id
                    INNER JOIN logement ON location.id_logement=logement.id
                    INNER JOIN type_logement on logement.id_type=type_logement.id
                    INNER JOIN bailleur on logement.id_bailleur=bailleur.id
                    WHERE planning_recouvrement.id=?");
                    $req_loc->execute(array($id));    
                    echo"<h6 class='left-align'>";
                        $j=0;
                        while($donnees_loc=$req_loc->fetch())
                        {
                            $locataire=$donnees_loc['0'];
                            $bailleur=$donnees_loc['1'];
                            $type_logement=$donnees_loc['2'];
                            $adresse=$donnees_loc['3'];
                            $j++;
                            echo "<b>".$j."-</b>".$locataire." : ".$type_logement." à ".$adresse."<br>";
                        }
                    echo"</h6>";
                ?>
            </div>
            <div class="modal-footer">
            <a href="#!" class="modal-close waves-effect waves-green btn btn-flat">Fermer</a>
            </div>
        </div>
        </td>
        <td>
        <!-- Modal Structure compte rendu-->
        <div id="modal__<?=$id ?>" class="modal">
            <div class="modal-content ">
                <form action="planning_compte_rendu.php?id=<?=$id ?>" method="post">
                    <h4 class='center'>Compte rendu</h4>
                    <div class="row">
                        <div class="input-field col s12">
                            <textarea id="compte_rendu" class="materialize-textarea" placeholder="Placeholder" name="compte_rendu"><?=$compte_rendu ?></textarea>
                            <label for="compte_rendu">Compte rendue</label>
                        </div>
                        <div class="col s2 offset-s8 input-field">
							<input class="btn" type="submit" name="enregistrer" value="Enregistrer" >
						</div>
                    </div>
                </form>
               
            </div>
            <div class="modal-footer">
            <a href="#!" class="modal-close waves-effect waves-green btn btn-flat">Fermer</a>
            </div>
        </div>
        </td>
    <?php
echo "</tr>";
}

?>
<script type="text/javascript">
	$('.tooltipped').tooltip();
    $('.modal').modal();
    
</script>