<?php
if (session_status() == PHP_SESSION_NONE) {
session_start();
}
?>

<!--Dropdown structure banque -->
<ul id="banque" class="dropdown-content">
  <!--<li><a href="banque.php">Nouvelle opération CMS</a></li>-->
  <li><a href="etat_banque.php">CMS</a></li>
  <!--<li><a href="banque_2.php">Nouvelle opération BAS</a></li>-->
  <li><a href="etat_banque_2.php">BAS</a></li>
  <li><a href="etat_banque_btp.php">BTP</a></li>
</ul>

<!--Dropdown structure caisse -->
<ul id="caisse" class="dropdown-content">
  <li><a href="etat_caisse_cm.php">Caisse </a></li>
  <li><a href="ventillation_caisse_cm.php">Ventillation caisse</a></li>
</ul>

<!--Dropdown structure article -->
<ul id="article" class="dropdown-content">
  <li><a href="article.php">Article </a></li>
  <li><a href="l_ravitaillement_article.php">Ravitaillements</a></li>
</ul>

<!--Dropdown structure vente -->
<ul id="vente" class="dropdown-content">
  <li><a href="e_vente_client.php">Nouvelle + </a></li>
  <li><a href="l_vente.php">Liste ventes</a></li>
</ul>

<!-- Dropdown Structure deconnexion -->
<ul id="deconnexion" class="dropdown-content">
  <li><a href="deconnexion.php">Déconnexion</a></li>
  <li><a href="pointage_personnel_immo.php">Pointage</a></li>
  <li><a href="pointage_personnel_individuel.php?id=<?=$_SESSION['id']?>">Liste Pointage</a></li>
  <?php
  
  if ($_SESSION['service1']=="service general")
  {
  ?>
  <?php
  }
  if ($_SESSION['fonction']=="administrateur")
  {
  ?>
  <li><a href="l_compte_rendu.php">Compte rendu</a></li>
  <?php
  }
  else
  {
  ?>
  <li><a href="l_compte_rendu.php?id=<?=$_SESSION['id']?>">Compte rendu</a></li>
  <?php
  }
  if ($_SESSION['service1']=='service general')
  {
  ?>
  <li><a href="sante.php">Santé</a></li>
  <li><a href="immobilier.php">Immobilier</a></li>
  <?php
  }
  ?>
</ul>

<!--Image d'entete-->
<div class="white center entete_img">
  <img style="" src="../css/images/entete_cm.jpg" width="55%" >
</div>
<nav>
  <div class="nav-wrapper">
    <a href="commerce.php" class="brand-logo">Dabakh</a>
    <a href="#" data-target="mobile-demo" class="sidenav-trigger"><i class="material-icons">menu</i></a>
    <ul class="right hide-on-med-and-down">
    <?php
  if ($_SESSION['fonction']=="administrateur")
  {
  ?>
    <li><a class="dropdown-trigger" href="etat_banque.php" data-beloworigin="true" data-target="banque">&nbspBanque&nbsp</a></li>
  <?php
  }
  ?>
    <li><a class="dropdown-trigger" href="etat_caisse_cm.php" data-beloworigin="true" data-target="caisse">&nbspCaisse&nbsp</a></li>
    <li><a class="dropdown-trigger" href="e_vente_client.php" data-beloworigin="true" data-target="vente">&nbspVentes&nbsp</a></li>
    <li><a class="dropdown-trigger" href="article.php" data-beloworigin="true" data-target="article">&nbspArticles&nbsp</a></li>
        <li>
            <a class="dropdown-trigger ab"  href="deconnexion.php" data-beloworigin="true"  data-target="deconnexion"><i class="material-icons right" style="font-size: 40px">person_pin</i><?=substr($_SESSION['prenom'], 0,1).".".$_SESSION['nom'] ?></a>
        </li>
        <li ><a  ></a></li>
      </ul>
    </div>
  </nav>

  <!-- Menu mobile -->
  <ul class="sidenav" id="mobile-demo">
    <li><b>Vente</b></li>
    <li><a href="e_vente_client.php">Nouvelle + </a></li>
    <li><a href="l_vente.php">Liste ventes</a></li>
    <li><b>Article</b></li>
    <li><a href="article.php">Article </a></li>
    <li><a href="l_ravitaillement_article.php">Ravitaillements</a></li>

    <li><b>Caisse</b></li>
    <li><a href="etat_caisse_cm.php">Caisse </a></li>
    <li><a href="ventillation_caisse_cm.php">Ventillation caisse</a></li>
    <?php
    if ($_SESSION['fonction']=="administrateur")
    {
    ?>
    <li><b>Banque</b></li>
    <!--<li><a href="banque.php">Nouvelle opération CMS</a></li>-->
    <li><a href="etat_banque.php">CMS</a></li>
    <!--<li><a href="banque_2.php">Nouvelle opération BAS</a></li>-->
    <li><a href="etat_banque_2.php">BAS</a></li>
    <li><a href="etat_banque_btp.php">BTP</a></li>
    <?php
    }
    ?>
    <li><i class="material-icons right" style="font-size: 40px">person_pin</i><?=substr($_SESSION['prenom'], 0,1).".".$_SESSION['nom'] ?></li>
    <?php
    if ($_SESSION['service1']=='service general')
    {
    ?>
    <li><a href="sante.php">Santé</a></li>
    <li><a href="immobilier.php">Immobilier</a></li>
    <?php
    }
    ?>
    <li><a href="pointage_personnel_immo.php">Pointage</a></li>
    <li><a href="pointage_personnel_individuel.php?id=<?=$_SESSION['id']?>">Liste Pointage</a></li>
    <li><a href="deconnexion.php">Déconnexion<i class="material-icons">power_settings_new</i></a></li>
    <li ><a  ></a></li>
  </ul>
  <style type="text/css">
  nav {
  <?php
  if ($_SESSION['fonction']=="administrateur")
  {
  echo "background-color: #42a5f5 ;";
  }
  else
  {
  echo "background-color: #42a5f5 ;";
  }
  ?>
  
  }
  ul.dropdown-content>li>a{/*Sélection des liens qui sont dans les listes déroulentes de la barre de navigation que l'on met en bleu*/
  background-color : white   ;
  <?php
  if ($_SESSION['fonction']=="administrateur")
  {
  echo "color: #42a5f5 ;";
  }
  else
  {
  echo "color: #42a5f5 ;";
  }
  ?>
  }
  ul.dropdown-content>li>a:hover{
  <?php
  if ($_SESSION['fonction']=="administrateur")
  {
  echo "background-color: #42a5f5 ;";
  }
  else
  {
  echo "background-color: #42a5f5 ;";
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
  .btn{
    background-color: #42a5f5;
  }
  .btn:hover{
    color: #42a5f5;
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