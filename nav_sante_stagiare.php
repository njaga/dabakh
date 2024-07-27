<?php
if (session_status() == PHP_SESSION_NONE) {
session_start();
}
//calcul du nombre de personne sur la liste d'attente
include 'connexion.php';
$nbr;
if ($_SESSION['fonction']=='medecin' OR $_SESSION['fonction']=='administrateur')
{
$req=$db->query("SELECT COUNT(*) FROM `consultation` WHERE etat='infirmier' OR etat='secretaire'");
}
else
{
$req=$db->query("SELECT COUNT(*) FROM consultation WHERE etat='secretaire'");
}
$donnee_attente=$req->fetch();
$nbr=$donnee_attente['0'];
$req->closeCursor();
//calcul du nombre de prescription en attente
$req=$db->query('SELECT COUNT(*) FROM `prescription` WHERE etat="non"');
$donnee_attente=$req->fetch();
$nbr_prescription=$donnee_attente['0'];
$req->closeCursor();
//calcul du nombre de rendez-vous pour la date actuelle
$req=$db->query('SELECT COUNT(*) FROM `rdv` WHERE date_rdv=CURRENT_DATE()');
$donnee_rdv=$req->fetch();
$nbr_rdv=$donnee_rdv['0'];
$req->closeCursor();
?>

<!--Dropdown structure caisse -->
<ul id="caisse" class="dropdown-content">
  <li><a href="e_caisse_sante.php">Nouvelle opération</a></li>
  <li><a href="ventillation_caisse_sante.php">Ventillation caisse</a></li>
</ul>

<!--Dropdown structure produit-ravitaillement pour AD et infirmier -->
<ul id="ravitaillement-produit" class="dropdown-content">
  <li><a href="produit.php">Produit</a></li>
  <li><a href="l_ravitaillement_produit.php">Ravitaillement produit</a></li>
  <li><a href="statistique_produit.php">Statistique
  </a></li>
</ul>

<!--Dropdown structure rendez-vous -->
<ul id="rdv" class="dropdown-content">
  <li><a href="n_patient_rdv.php">Nouveau rendez-vous</a></li>
  <li><a href="l_rdv">Liste des rendez-vous</a></li>
</ul>
<!--Dropdown structure contact -->
<ul id="contact" class="dropdown-content">
  <li><a href="e_contact.php">Nouveau </a></li>
  <li><a href="l_contact.php">Liste des contacts</a></li>
</ul>

<!--Dropdown déconnexion femme de charge -->
<ul id="deconnexion_femme_charge" class="dropdown-content">
  <li><a href="deconnexion.php">Déconnexion</a></li>
</ul>


<!--Menu -->
<!--Image d'entete-->
<div class="white center entete_img responsive-img">
  <img style="" src="../css/images/entete.jpg" width="50%" >
</div>
<nav>
  <div class="nav-wrapper">
    <a href="sante.php" class="brand-logo">Dabakh</a>
    <a href="sante.php" data-target="mobile-demo" class="sidenav-trigger"><i class="material-icons">menu</i></a>
    <ul class="right hide-on-med-and-down">
    <li>
        <a class="dropdown-trigger" date-beloworigin="true" data-target="ravitaillement-produit">Produit</a>
        </li>
      <li>
        <li>
        <a class="dropdown-trigger" date-beloworigin="true" data-target="caisse">Caisse</a>
        </li>
      <li>
        <a class="dropdown-trigger" date-beloworigin="true" data-target="contact">Contact</a>
      </li>
      <li>
        <a class="dropdown-trigger ab"  href="deconnexion.php" data-beloworigin="true"  data-target="deconnexion"><i class="material-icons right" style="font-size: 40px">person_pin</i><?=substr($_SESSION['prenom'], 0,1).".".$_SESSION['nom'] ?></a>
      </li>
      
    </ul>
  </div>
</nav>

<!-- Mobile -->
<ul class="sidenav" id="mobile-demo">
</ul>
<style type="text/css">
nav
{
background-color: #2e7d32   ;
<?php
if ($_SESSION['fonction']=="infirmier")
{
?>
background-color: #4db6ac   ;
<?php
}
?>
<?php
if ($_SESSION['fonction']=="medecin" OR $_SESSION['fonction']=="administrateur")
{
?>
background-color: #4a148c   ;
<?php
}
?>
<?php
if ($_SESSION['fonction']=="secretaire")
{
?>
background-color: #01579b    ;
<?php
}
?>
}
ul.dropdown-content>li>a{/*Sélection des liens qui sont dans les listes déroulentes de la barre de navigation que l'on met en bleu*/
background-color : white   ;
color: #2e7d32  ;
<?php
if ($_SESSION['fonction']=="infirmier")
{
?>
color: #4db6ac   ;
<?php
}
?>
<?php
if ($_SESSION['fonction']=="medecin" OR $_SESSION['fonction']=="administrateur")
{
?>
color: #4a148c   ;
<?php
}
?>
<?php
if ($_SESSION['fonction']=="secretaire")
{
?>
color: #01579b    ;
<?php
}
?>
}
ul.dropdown-content>li>a:hover{
background-color: #2e7d32  ;
<?php
if ($_SESSION['fonction']=="infirmier")
{
?>
background-color: #4db6ac   ;
<?php
}
?>
<?php
if ($_SESSION['fonction']=="medecin" OR $_SESSION['fonction']=="administrateur")
{
?>
background-color: #4a148c;
<?php
}
?>
<?php
if ($_SESSION['fonction']=="secretaire")
{
?>
background-color: #01579b    ;
<?php
}
?>
color: white;
}
.dropdown-trigger{
padding-left: 50px;
}
/*Code nécessaire pour la barre de navigation */
.dropdown-content {
overflow-y: visible;
}
/* Permet de décaler la liste déroulante vers la droite*/
.dropdown-content .dropdown-content {
margin-left: 100%;
}
body{
font: 12pt 'times new roman';
}
</style>
<script type="text/javascript">
$(document).ready(function(){
$('.sidenav').sidenav();
$(".dropdown-trigger").dropdown({
hover: true, //déroulement de la liste au survol de l'élément
inDuration : 400,
outDuration : 300,
coverTrigger:false,//la liste déroulante apparaîtra sous le déclencheur.
belowOrigin: false,
alignment: 'right'
});
});
</script>