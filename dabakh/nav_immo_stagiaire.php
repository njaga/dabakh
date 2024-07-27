<?php
if (session_status() == PHP_SESSION_NONE) {
session_start();
}
?>
<!--Dropdown courrier -->
<ul id="courrier" class="dropdown-content">
  <li><a href="e_courrier.php">Courrier</a></li>
  <li><a href="l_courrier.php">Liste</a></li>
</ul>

<!--Dropdown structure caisse immo-->
<ul id="caisse" class="dropdown-content">
  <li><a href="e_caisse_immo.php">Caisse immo</a></li>
  <li><a href="ventillation_caisse_immo.php">Ventillation caisse</a></li>
  
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
  <?php
  }
  ?>
</ul>
<!--Dropdown déconnexion femme de charge -->
<ul id="deconnexion_femme_charge" class="dropdown-content">
  <li><a href="deconnexion.php">Déconnexion</a></li>
</ul>

<!--Dropdown structure demande -->
<ul id="logement" class="dropdown-content">
  <li><a href="type_logement.php">Type logement</a></li>
  <li><a href="l_logement_libre.php">Logements libre</a></li>
  <li><a href="l_logement_occupe.php">Logements occupé</a></li>
</ul>
<!--Dropdown structure bailleur -->
<ul id="bailleur" class="dropdown-content">
  <li><a href="l_bailleur.php">Liste des bailleurs</a></li>
  <li><a href="l_mensualite_bailleur.php">Mensualités</a></li>
  <li><a href="l_mensualite_bailleur_non_verser.php">Mensualités non versés</a></li>
  <li><a href="l_depenses_bailleur.php">Dépense bailleur</a></li>
  <?php
  if ($_SESSION['fonction']=='administrateur')
  {
  ?>
  <li><a href="contrat_convention.php?id=1">Contrat</a></li>
  <?php
  }
  ?>
</ul>
<!--Dropdown structure locataire -->
<ul id="locataire" class="dropdown-content">
  <!--<li><a href="e_locataire.php">Nouveau</a></li>-->
  <li><a href="l_locataire_actif.php">Locataires actif</a></li>
  <li><a href="l_locataire_inactif.php">Locataires inactif</a></li>
  <li><a href="l_injonction.php">Injonction(s) locataires</a></li>
  <li><a href="l_cotisation_locataire.php">Cotisation locataires</a></li>
  <li><a href="l_cotisation_locataire_depense.php">Dépenses sur Cotisation locataires</a></li>
</ul>
<!--Dropdown structure location -->
<ul id="location" class="dropdown-content">
  <li><a href="l_location.php">Liste des locations</a></li>
  <?php
  if ($_SESSION['fonction']=='administrateur')
  {
  ?>
  <li><a href="contrat_location.php?id=2">Contrat</a></li>
  <?php
  }
  ?>
</ul>
<!--Dropdown structure mensualite -->
<ul id="mensualite" class="dropdown-content">
  
  <li><a href="l_mensualite_paye.php">Payée(s)</a></li>
  <li><a href="l_mensualite_impaye.php">Impayée(s)</a></li>
  
  <li><a href="l_planning_recouv.php">Planning recouvrement</a></li>
</ul>


<!--Dropdown structure contact -->
<ul id="contact" class="dropdown-content">
  <li><a href="e_contact.php">Nouveau </a></li>
  <li><a href="l_contact.php">Liste des contacts</a></li>
</ul>
<!--Dropdown structure consommable -->
<ul id="consommable" class="dropdown-content">
  <li><a href="consommable.php">Consommables </a></li>
  <li><a href="l_ravitaillement_consommable.php">Ravitaillements</a></li>
  <li><a href="l_consommable_user.php">Dotations</a></li>
</ul>
<!--Image d'entete-->
<div class="white center entete_img">
  <img style="" src="../css/images/banniere_immo.png" width="35%" >
</div>
<nav>
  <div class="nav-wrapper">
    <a href="immobilier.php" class="brand-logo">Dabakh</a>
    <a href="#" data-target="mobile-demo" class="sidenav-trigger"><i class="material-icons">menu</i></a>
    <ul class="right hide-on-med-and-down">
      <?php
      if ($_SESSION['fonction']!='femme de charge')
      {
      ?>
      
      <li ><a   class="dropdown-trigger" data-beloworigin="true" data-target="courrier">Courrier</a></li>
      <li ><a   class="dropdown-trigger" data-beloworigin="true" data-target="consommable">Consommables</a></li>
      <li ><a   class="dropdown-trigger" data-beloworigin="true" data-target="contact">Contact</a></li>
      <li ><a   class="dropdown-trigger" data-beloworigin="true" data-target="caisse">Finances</a></li>
      <li ><a   class="dropdown-trigger" data-beloworigin="true" data-target="mensualite">Mensualité</a></li>
      <li ><a   class="dropdown-trigger" href="l_location.php" data-beloworigin="true" data-target="location">Location</a></li>
      <li ><a   class="dropdown-trigger" data-beloworigin="true" data-target="logement">Logement</a></li>
      <li ><a   class="dropdown-trigger" data-beloworigin="true" data-target="bailleur">bailleur</a></li>
      <li ><a   class="dropdown-trigger" href="l_locataire_actif.php" data-beloworigin="true" data-target="locataire">Locataire</a></li>
      <li>
        <li>
          <a class="dropdown-trigger ab"  href="deconnexion.php" data-beloworigin="true"  data-target="deconnexion"><i class="material-icons right" style="font-size: 40px">person_pin</i><?=substr($_SESSION['prenom'], 0,1).".".$_SESSION['nom'] ?></a>
        </li>
        <?php
        }
        if ($_SESSION['fonction']=='femme de charge')
        {
        ?>
        <li><a href="l_compte_rendu.php">Compte rendu</a></li>
        <li><a  href="consommable.php" >Consommables</a></li>
        <li><a href="pointage_personnel_individuel.php?id=<?=$_SESSION['id']?>">Liste Pointage</a></li>
        <li ><a  href="pointage_personnel_immo.php" >Pointage</a></li>
        <li>
          <a class="dropdown-trigger ab"  href="deconnexion.php" data-beloworigin="true"  data-target="deconnexion_femme_charge"><i class="material-icons right" style="font-size: 40px">person_pin</i><?=substr($_SESSION['prenom'], 0,1).".".$_SESSION['nom'] ?></a>
        </li>
        <?php
        }
        ?>
        
        <li ><a  ></a></li>
      </ul>
    </div>
  </nav>

  <!-- Menu mobile 
  <ul class="sidenav" id="mobile-demo">
    <li><a href="immobilier.php"><b>Accueil</b></a></li>
    <?php
    if ($_SESSION['fonction']!='femme de charge')
    {
    ?>
    <li><b>Finance</b></li>
    <li><a href="etat_caisse_immo.php">Caisse immo</a></li>
    <li><a href="etat_caisse_btp.php">Caisse BTP</a></li>
    <li><a href="banque_btp.php">Banque BTP</a></li>
    <li><a href="ventillation_caisse_immo.php">Ventillation caisse</a></li>
    <li><a href="etat_caisse_caution.php">Caisse caution</a></li>
    <li><a href="etat_caisse_depot.php">Caisse dépôt</a></li>
    <?php
    if ($_SESSION['fonction']=='administrateur' OR $_SESSION['fonction']=='daf')
    {
    ?>
    <li><a href="etat_banque.php">Banque</a></li>
    <?php
    }
    
    ?>
    <?php
    if ($_SESSION['fonction']=='administrateur')
    {
    ?>
    <li><a href="commission.php">Commission gérence</a></li>
    <li><a href="commission_locative.php">Commission locative</a></li>
    <?php
    }
    ?>
    <li><b>Logement</b></li>
    <li><a href="type_logement.php">Type logement</a></li>
    <li><a href="l_logement_libre.php">Logements libre</a></li>
    <li><a href="l_logement_occupe.php">Logements occupé</a></li>
    <li><b>Bailleur</b></li>
    <li><a href="e_bailleur.php">Nouveau +</a></li>
    <li><a href="l_bailleur.php">Liste des bailleurs</a></li>
    <li><a href="l_mensualite_bailleur.php">Mensualités</a></li>
    <li><a href="l_mensualite_bailleur_non_verser.php">Mensualités non reversés</a></li>
    <li><a href="l_depenses_bailleur.php">Dépense bailleur</a></li>
    <?php
    if ($_SESSION['fonction']=='administrateur')
    {
    ?>
    <li><a href="contrat_convention.php?id=1">Contrat bailleur</a></li>
    <?php
    }
    ?>
    <li><b>Locataire</b></li>
    <li><a href="l_locataire_actif.php">Locataires actif</a></li>
    <li><a href="l_locataire_inactif.php">Locataires inactif</a></li>
    <li><a href="l_injonction.php">Injonction(s) locataire</a></li>
    <li><a href="l_cotisation_locataire.php">Cotisation locataires</a></li>
    <li><a href="l_cotisation_locataire_depense.php">Dépenses sur Cotisation locataires</a></li>
    <li><b>Location</b></li>
    <li><a href="e_location.php">Nouveau dossier</a></li>
    <li><a href="l_location.php">Liste des locations</a></li>
    <?php
    if ($_SESSION['fonction']=='administrateur')
    {
    ?>
    <li><a href="contrat_location.php?id=2">Contrat</a></li>
    <?php
    }
    ?>
    <li><b>Mensualité</b></li>
    <li><a href="e_mensualite.php">Enregistrer</a></li>
    <li><a href="l_mensualite_paye.php">Payée(s)</a></li>
    <li><a href="l_mensualite_impaye.php">Impayée(s)</a></li>
    <?php
    if ($_SESSION['fonction']=='administrateur') 
    {
      echo '<li><a href="l_mensualite_s.php">Mensualités supprimée(s)</a></li>';
    }
    ?>
    <li><a href="statistique_recouvrement.php">Statistiques</a></li>
    <li><b>Contact</b></li>
    <li><a href="e_contact.php">Nouveau </a></li>
    <li><a href="l_contact.php">Liste des contacts</a></li>
    <li><b>Consomables</b></li>
    <li><a href="consommable.php">Consommables </a></li>
    <li><a href="l_ravitaillement_consommable.php">Ravitaillements</a></li>
    <li><a href="l_consommable_user.php">Dotations</a></li>
    <?php
    if ($_SESSION['service1']=='service general')
    {
    ?>
    <li><a href="sante.php">Santé</a></li>
    <li><a href="commerce.php">Commerce</a></li>
    <?php
    }
    
    }
    if ($_SESSION['fonction']=='femme de charge')
    {
    ?>
    <li ><a  href="pointage_personnel_immo.php" >Pointage</a></li>
    <?php
    }
    ?>
    <li><a href="deconnexion.php">Déconnexion<i class="material-icons">power_settings_new</i></a></li>
    <li ><a  ></a></li>
  </ul>
  -->
  <style type="text/css">
  nav {
  <?php
  if ($_SESSION['fonction']=="administrateur")
  {
  echo "background-color: #546e7a ;";
  }
  else
  {
  echo "background-color: #795548 ;";
  }
  ?>
  
  }
  ul.dropdown-content>li>a{/*Sélection des liens qui sont dans les listes déroulentes de la barre de navigation que l'on met en bleu*/
  background-color : white   ;
  <?php
  if ($_SESSION['fonction']=="administrateur")
  {
  echo "color: #546e7a ;";
  }

  else
  {
  echo "color: #795548 ;";
  }
  ?>
  }
  ul.dropdown-content>li>a:hover{
  <?php
  if ($_SESSION['fonction']=="administrateur")
  {
  echo "background-color: #546e7a ;";
  }
  else
  {
  echo "background-color: #795548 ;";
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