<?php
if (session_status() == PHP_SESSION_NONE) {
  session_start();
}
//calcul du nombre de personne sur la liste d'attente
include 'connexion.php';
$nbr;
if ($_SESSION['fonction'] == 'medecin' or $_SESSION['fonction'] == 'administrateur') {
  $req = $db->query("SELECT COUNT(*) FROM `consultation` WHERE etat='infirmier' OR etat='secretaire'");
} else {
  $req = $db->query("SELECT COUNT(*) FROM consultation WHERE etat='secretaire'");
}
$donnee_attente = $req->fetch();
$nbr = $donnee_attente['0'];
$req->closeCursor();
//calcul du nombre de prescription en attente
$req = $db->query('SELECT COUNT(*) FROM `prescription` WHERE etat="non"');
$donnee_attente = $req->fetch();
$nbr_prescription = $donnee_attente['0'];
$req->closeCursor();
//calcul du nombre de rendez-vous pour la date actuelle
$req = $db->query('SELECT COUNT(*) FROM `rdv` WHERE date_rdv=CURRENT_DATE()');
$donnee_rdv = $req->fetch();
$nbr_rdv = $donnee_rdv['0'];
$req->closeCursor();
?>
<!--Dropdown structure  soins -->
<ul id="soins" class="dropdown-content">
  <li><a href="l_analyse.php">Autres soins dispensés</a></li>
  <li><a href="l_consultation_d.php">Soins à domicile</a></li>
  <?php
  if (($_SESSION['fonction'] == 'administrateur') or ($_SESSION['fonction'] == 'medecin') or ($_SESSION['fonction'] == 'daf')) {
  ?>
    <li><a href="l_rapport_assis.php">Rapport d'assistance</a></li>
  <?php
  }
  ?>

</ul>
<!-- Dropdown Structure constante -->
<ul id="constante" class="dropdown-content">
  <li><a href="l_patient_constante.php">Enregistrer</a></li>
  <li><a href="l_constante.php">Liste des constantes</a></li>
</ul>
<!-- Dropdown Structure prescription -->
<ul id="prescription" class="dropdown-content">
  <?php
  if (($_SESSION['fonction'] == 'administrateur') or ($_SESSION['fonction'] == 'medecin')) {
  ?>
    <li><a href="e_prescription1.php">Nouvelle Prescription</a></li>
  <?php
  }
  ?>
  <li><a href="l_prescription.php">Liste prescription</a></li>
</ul>
<!--Dropdown structure caisse -->
<ul id="caisse" class="dropdown-content">
  <?php
  if ($_SESSION['fonction'] == 'secretaire' or $_SESSION['fonction'] == 'daf' or $_SESSION['fonction'] == 'stagiaire') {
  ?>
    <li><a href="e_caisse_sante.php">Nouvelle opération</a></li>
  <?php
  }
  if ($_SESSION['fonction'] != 'stagiaire') {
  ?>
    <li><a href="etat_caisse_sante.php">Etat caisse</a></li>
  <?php
  }
  ?>
  <li><a href="ventillation_caisse_sante.php">Ventillation caisse</a></li>
  <?php
  if ($_SESSION['fonction'] == 'administrateur') {
  ?>

    <li><a href="etat_banque.php">Etat banque</a></li>
  <?php
  }

  ?>
</ul>
<!-- Dropdown Structure services et produits -->
<ul id="s_p" class="dropdown-content">
  <li><a href="analyse.php">Analyse</a></li>
  <li><a href="consultation.php">Consultations</a></li>
  <li><a href="hospitalisation.php">Hospitalisation</a></li>
  <li><a href="produit.php">Produits</a></li>
  <li><a href="l_ravitaillement_produit.php">Ravitaillement produit</a></li>
  <li><a href="soins_externes.php">Soins externes</a></li>
  <li><a href="soins_domicile.php">Soins à domicile</a></li>
</ul>
<!-- Dropdown Structure deconnexion -->
<ul id="deconnexion" class="dropdown-content">
  <li><a href="deconnexion.php">Déconnexion</a></li>
  <?php
  if ($_SESSION['fonction'] != 'femme de charge') {
  ?>
    <li><a href="pointage_personnel_sante.php">Pointage</a></li>
    <li><a href="pointage_personnel_individuel.php?id=<?= $_SESSION['id'] ?>">Liste Pointage</a></li>
  <?php
  }
  if ($_SESSION['service1'] == "service general") {
  ?>
  <?php
  }
  if ($_SESSION['fonction'] == "administrateur" or $_SESSION['fonction'] == "daf") {
  ?>
    <li><a href="l_compte_rendu.php">Compte rendu</a></li>
  <?php
  } else {
  ?>
    <li><a href="l_compte_rendu.php?id=<?= $_SESSION['id'] ?>">Compte rendu</a></li>
  <?php
  }
  if ($_SESSION['service1'] == 'service general') {
  ?>
    <li><a href="immobilier.php">Immobilier</a></li>
    <li><a href="commerce.php">Commerce</a></li>
  <?php
  }
  ?>

</ul>
<!--Dropdown structure personnel -->
<ul id="personnel" class="dropdown-content">
  <?php
  if ($_SESSION['fonction'] == "administrateur") {
  ?>
    <li><a href="e_personnel.php">Ajouter</a></li>
  <?php
  }
  ?>
  <?php
  if ($_SESSION['fonction'] == "administrateur" or $_SESSION['fonction'] == "daf") {
  ?>
    <li><a href="l_personnel.php">Liste personnel</a></li>
    <li><a href="list_pointage_personnel.php">Pointages du personnel</a></li>
  <?php
  }
  ?>
  <li><a href="l_demandes_personnel.php">Permissions<br>/ Absences</a></li>
  <li><a href="l_demande_emploi.php">Demandes d'emplois</a></li>
</ul>
<!--Dropdown structure patient -->
<ul id="patient" class="dropdown-content">
  <?php
  if ($_SESSION['fonction'] == 'secretaire' or $_SESSION['fonction'] == 'infirmier' or $_SESSION['fonction'] == 'administrateur' or $_SESSION['fonction'] == 'caissier') {
    echo '<li><a href="e_patient.php">Nouveau dossier</a></li>';
  }
  if ($_SESSION['fonction'] == 'secretaire' or $_SESSION['fonction'] == 'administrateur' or $_SESSION['fonction'] == 'caissier') {
  ?>
    <li><a href="l_ordonnance.php">Ordonnance</a></li>
    <li><a href="l_d_regularisation.php">Demandes Régularisation</a></li>
    <li><a href="l_d_consultation.php">Demandes Consultation</a></li>
    <li><a href="l_certificat_r_m.php">Certificat de repos</a></li>
    <li><a href="l_certificat_med.php">Certificat médical</a></li>
    <li><a href="l_certificat_hos.php">Certificat d'hospitalisation</a></li>
  <?php
  }
  ?>
  <li><a href="l_patient.php">Liste des patients</a></li>
</ul>
<!--Dropdown structure consultation -->
<ul id="consultation" class="dropdown-content">
  <?php
  if ($_SESSION['fonction'] == 'secretaire' or $_SESSION['fonction'] == 'infirmier' or $_SESSION['fonction'] == 'caissier') {
    echo '<li><a href="l_patient_cons.php">Nouvelle consultation</a></li>';
  }
  ?>
  <li><a href="l_consultation.php">Liste des consultations</a></li>
  <?php
  if (($_SESSION['fonction'] == 'medecin') or ($_SESSION['fonction'] == 'administrateur') or ($_SESSION['fonction'] == 'infirmier') or ($_SESSION['fonction'] == 'secretaire') or ($_SESSION['fonction'] == 'caissier')) {
  ?>
    <li>
      <a href="l_attente.php">
        Liste d'attente
        <?php
        if ($nbr > 0) {
          echo '<span class="new badge red">' . $nbr . '</span>';
        }
        ?>
      </a>
    </li>
  <?php
  }
  if ($_SESSION['fonction'] == 'administrateur') {
    echo '<li><a href="statistiques_sante.php">Statistiques</a></li>';
  }
  ?>
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
<!--Dropdown structure produit-ravitaillement pour AD et infirmier -->
<ul id="ravitaillement-produit" class="dropdown-content">
  <li><a href="produit.php">Produit</a></li>
  <li><a href="l_ravitaillement_produit.php">Ravitaillement produit</a></li>
  <li><a href="statistique_produit.php">Statistique
    </a></li>
</ul>
<!--Dropdown déconnexion femme de charge -->
<ul id="deconnexion_femme_charge" class="dropdown-content">
  <li><a href="deconnexion.php">Déconnexion</a></li>
</ul>
<!--Menu -->
<!--Image d'entete-->
<div class="white center entete_img responsive-img">
  <img style="" src="../css/images/entete.jpg" width="50%">
</div>
<nav>
  <div class="nav-wrapper">
    <a href="sante.php" class="brand-logo">Dabakh</a>
    <a href="sante.php" data-target="mobile-demo" class="sidenav-trigger"><i class="material-icons">menu</i></a>
    <ul class="right hide-on-med-and-down">
      <?php
      if (($_SESSION['fonction'] == 'medecin') or ($_SESSION['fonction'] == 'administrateur') or ($_SESSION['fonction'] == 'infirmier') or ($_SESSION['fonction'] == 'medecin')) {
      ?>
        <li>
          <a class="dropdown-trigger" date-beloworigin="true" data-target="ravitaillement-produit">Produit</a>
        </li>
      <?php

      }

      ?>
      <?php
      if (($_SESSION['fonction'] == 'medecin') or ($_SESSION['fonction'] == 'administrateur') or ($_SESSION['fonction'] == 'secretaire') or ($_SESSION['fonction'] == 'stagiaire') or ($_SESSION['fonction'] == 'caissier')) {
      ?>
        <li>
          <a class="dropdown-trigger" date-beloworigin="true" data-target="contact">Contact</a>
        </li>
      <?php

      }

      if ($_SESSION['fonction'] != 'caissier' or $_SESSION['fonction'] != 'femme de charge') {
      ?>
        <li>
          <a class="dropdown-trigger" date-beloworigin="true" data-target="soins">Soins</a>
        </li>
      <?php
      }
      if (($_SESSION['fonction'] == 'medecin') or ($_SESSION['fonction'] == 'administrateur') or ($_SESSION['fonction'] == 'infirmier')) {
      ?>
        <li>
          <a class="dropdown-trigger" date-beloworigin="true" data-target="constante">Constante</a>
        </li>
        <li>
          <a class="dropdown-trigger" date-beloworigin="true" data-target="prescription">
            Prescription
            <?php
            if ($nbr_prescription > 0) {
              echo '<span class="new badge red">' . $nbr_prescription . '</span>';
            }
            ?>
          </a>
        </li>
      <?php
      }
      if (($_SESSION['fonction'] == 'secretaire') or ($_SESSION['fonction'] == 'administrateur') or ($_SESSION['fonction'] == 'daf') or ($_SESSION['fonction'] == 'stagiaire') or ($_SESSION['fonction'] == 'caissier')) {
      ?>
        <li>
          <a href="etat_caisse_sante.php" class="dropdown-trigger" date-beloworigin="true" data-target="caisse">Finances</a>
        </li>
      <?php
      }
      if ($_SESSION['fonction'] == 'secretaire' or $_SESSION['fonction'] == 'caissier') {
        echo '<li ><a  href="consultation.php" class="dropdown-trigger" data-beloworigin="true" data-target="s_p">Services et produits</a></li>';
      }
      ?>

      <?php
      if ($_SESSION['fonction'] == 'secretaire' or ($_SESSION['fonction'] == 'stagiaire') or ($_SESSION['fonction'] == 'caissier')) {
      ?>
        <li>
          <a href="l_rdv.php" class="dropdown-trigger" data-beloworigin="true" data-target="rdv">
            Rendez-vous
            <?php
            if ($nbr_rdv > 0) {
              echo '<span class="new badge red">' . $nbr_rdv . '</span>';
            }
            ?>
          </a>
        </li>
      <?php
      }
      if (($_SESSION['fonction'] == 'medecin') or ($_SESSION['fonction'] == 'administrateur') or ($_SESSION['fonction'] == 'infirmier' or ($_SESSION['fonction'] == 'secretaire') or ($_SESSION['fonction'] == 'caissier'))) {
      ?>
        <li>
          <a href="l_consultation.php" class="dropdown-trigger" data-beloworigin="true" data-target="consultation">
            Consultation
            <?php
            if ($nbr > 0) {
              echo '<span class="new badge red">' . $nbr . '</span>';
            }
            ?>
          </a>
        </li>
      <?php
      }
      if (($_SESSION['fonction'] == 'secretaire') or ($_SESSION['fonction'] == 'administrateur') or ($_SESSION['fonction'] == 'infirmier') or ($_SESSION['fonction'] == 'stagiaire') or ($_SESSION['fonction'] == 'caissier')) {
        echo '<li ><a  href="l_patient.php" class="dropdown-trigger" data-beloworigin="true" data-target="patient">Patient</a></li>';
      }

      ?>
      <?php
      if ($_SESSION['fonction'] == 'administrateur' or $_SESSION['fonction'] == 'daf' or $_SESSION['fonction'] == 'secretaire') {
        echo '<li ><a  href="l_personnel.php" class="dropdown-trigger" data-beloworigin="true" data-target="personnel">Personnel</a></li>';
      }
      if ($_SESSION['fonction'] == 'femme de charge') {
      ?>
        <li><a href="l_compte_rendu.php">Compte rendu</a></li>
        <li><a href="consommable.php">Consommables</a></li>
        <li><a href="pointage_personnel_individuel.php?id=<?= $_SESSION['id'] ?>">Liste Pointage</a></li>
        <li><a href="pointage_personnel_sante.php">Pointage</a></li>
        <li>
        </li>
      <?php
      }
      ?>
      <li>
        <a class="dropdown-trigger ab" href="deconnexion.php" data-beloworigin="true" data-target="deconnexion"><i class="material-icons right" style="font-size: 40px">person_pin</i><?= substr($_SESSION['prenom'], 0, 1) . "." . $_SESSION['nom'] ?></a>
      </li>

    </ul>
  </div>
</nav>

<!-- Mobile -->
<ul class="sidenav" id="mobile-demo">
  <li><a href="sante.php"><b>Accueil</b></a></li>

  <li><b>Soins</b></li>
  <li><a href="l_analyse.php">Autres soins dispensés</a></li>
  <li><a href="l_consultation_d.php">Soins à domicile</a></li>
  <?php
  if (($_SESSION['fonction'] == 'administrateur') or ($_SESSION['fonction'] == 'medecin') or ($_SESSION['fonction'] == 'daf')) {
  ?>
    <li><a href="l_rapport_assis.php">Rapport d'assistance</a></li>
  <?php
  }
  if (($_SESSION['fonction'] == 'medecin') or ($_SESSION['fonction'] == 'administrateur') or ($_SESSION['fonction'] == 'infirmier')) {
  ?>
    <li><b>Constantes</b></li>
    <li><a href="l_patient_constante.php">Enregistrer</a></li>
    <li><a href="l_constante.php">Liste des constantes</a></li>
    <li><b>Prescriptions</b><?php if ($nbr_prescription > 0) {
                              echo '<span class="new badge red">' . $nbr_prescription . '</span>';
                            } ?></li>
    <?php
    if (($_SESSION['fonction'] == 'administrateur') or ($_SESSION['fonction'] == 'medecin')) {
    ?>
      <li><a href="e_prescription1.php">Nouvelle Prescription</a></li>
    <?php
    }
    ?>
    <li><a href="l_prescription.php">Liste prescription</a></li>
  <?php
  }
  if (($_SESSION['fonction'] == 'secretaire') or ($_SESSION['fonction'] == 'administrateur') or ($_SESSION['fonction'] == 'daf') or ($_SESSION['fonction'] == 'stagiaire')) {
  ?>
    <li><b>Finances</b></li>
    <?php
    if ($_SESSION['fonction'] == 'secretaire' or $_SESSION['fonction'] == 'daf' or ($_SESSION['fonction'] == 'stagiaire')) {
    ?>
      <li><a href="e_caisse_sante.php">Nouvelle opération</a></li>
    <?php
    }
    if ($_SESSION['fonction'] != 'stagiaire')
    ?>
    <li><a href="etat_caisse_sante.php">Etat caisse</a></li>
  <?php
}
if ($_SESSION['fonction'] == 'administrateur') {
  ?>
    <li><a href="etat_banque.php">Etat banque</a></li>
  <?php
}

  ?>
  <?php
  if ($_SESSION['fonction'] == 'secretaire' or ($_SESSION['fonction'] == 'stagiaire')) {
  ?>
    <li><b>Services et produits</b></li>
    <li><a href="analyse.php">Analyse</a></li>
    <li><a href="consultation.php">Consultations</a></li>
    <li><a href="hospitalisation.php">Hospitalisation</a></li>
    <li><a href="produit.php">Produits</a></li>
    <li><a href="l_ravitaillement_produit.php">Ravitaillement produit</a></li>
    <li><a href="soins_externes.php">Soins externes</a></li>
    <li><a href="soins_domicile.php">Soins à domicile</a></li>
  <?php
  }
  ?>
  <li><b>Rendez-vous</b></li>
  <li><a href="n_patient_rdv.php">Nouveau rendez-vous</a></li>
  <li><a href="l_rdv">Liste des rendez-vous</a></li>
  <li><b>Consultations</b></li>
  <?php
  if ($_SESSION['fonction'] == 'secretaire' or $_SESSION['fonction'] == 'infirmier') {
    echo '<li><a href="l_patient_cons.php">Nouvelle consultation</a></li>';
  }
  ?>
  <li><a href="l_consultation.php">Liste des consultations</a></li>
  <li>
    <a href="l_attente.php">
      Liste d'attente
      <?php
      if ($nbr > 0) {
        echo '<span class="new badge red">' . $nbr . '</span>';
      }
      ?>
    </a>
  </li>
  <li><a href="statistiques_sante.php">Statistiques</a></li>
  <li><b>Patient</b></li>
  <?php
  if ($_SESSION['fonction'] == 'secretaire' or $_SESSION['fonction'] == 'infirmier' or $_SESSION['fonction'] == 'administrateur') {
    echo '<li><a href="e_patient.php">Nouveau dossier</a></li>';
  }
  if ($_SESSION['fonction'] == 'secretaire' or $_SESSION['fonction'] == 'administrateur') {
  ?>
    <li><a href="l_ordonnance.php">Ordonnance</a></li>
    <li><a href="l_d_regularisation.php">Demandes Régularisation</a></li>
    <li><a href="l_d_consultation.php">Demandes Consultation</a></li>
    <li><a href="l_certificat_r_m.php">Certificat de repos</a></li>
    <li><a href="l_certificat_med.php">Certificat médical</a></li>
    <li><a href="l_certificat_hos.php">Certificat d'hospitalisation</a></li>
  <?php
  }
  ?>
  <li><a href="l_patient.php">Liste des patients</a></li>
  <?php
  if ($_SESSION['fonction'] == 'administrateur') {
  ?>
    <li><b>Personnel</b></li>
    <li><a href="e_personnel.php">Ajouter</a></li>
    <li><a href="l_personnel.php">Liste personnel</a></li>
    <li><a href="list_pointage_personnel.php">Pointages du personnel</a></li>
    <li><a href="l_demandes_personnel.php">Permissions / Absences / Congés</a></li>
    <li><a href="l_demande_emploi.php">Demandes d'emplois</a></li>
  <?php
  }
  if ($_SESSION['fonction'] == "administrateur" or $_SESSION['fonction'] == "daf") {
  ?>
    <li><a href="l_compte_rendu.php">Compte rendu</a></li>
  <?php
  } else {
  ?>
    <li><a href="l_compte_rendu.php?id=<?= $_SESSION['id'] ?>">Compte rendu</a></li>
  <?php
  }
  ?>

  <li><b>Contact</b></li>
  <li><a href="e_contact.php">Nouveau </a></li>
  <li><a href="l_contact.php">Liste des contacts</a></li>
  <li><b>Ravitaillement produit</b></li>
  <?php
  if (($_SESSION['fonction'] == 'administrateur') or ($_SESSION['fonction'] == 'secretaire')) {
  ?>
    <li><a href="produit.php">Produit</a></li>
  <?php
  }
  ?>
  <li><a href="l_ravitaillement_produit.php">Ravitaillement produit</a></li>
  <li><a href="statistique_produit.php">Statistique produit</a></li>
  <li><b>Pointage</b></li>
  <?php
  if ($_SESSION['fonction'] != 'femme de charge') {
  ?>
    <li><a href="pointage_personnel_sante.php">Pointage</a></li>
    <li><a href="pointage_personnel_individuel.php?id=<?= $_SESSION['id'] ?>">Liste Pointage</a></li>
  <?php
  }
  ?>
  <li><b>Dabakh</b></li>
  <?php
  if ($_SESSION['service1'] == 'service general') {
  ?>
    <li><a href="immobilier.php">Immobilier</a></li>
    <li><a href="commerce.php">Commerce</a></li>
  <?php
  }
  ?>
  <li><a href="deconnexion.php">Déconnexion1111</a></li>
</ul>
<style type="text/css">
  nav {
    background-color: #2e7d32;
    <?php
    if ($_SESSION['fonction'] == "infirmier") {
    ?>background-color: #4db6ac;
    <?php
    }
    ?><?php
      if ($_SESSION['fonction'] == "medecin" or $_SESSION['fonction'] == "administrateur") {
      ?>background-color: #4a148c;
    <?php
      }
    ?><?php
      if ($_SESSION['fonction'] == "secretaire") {
      ?>background-color: #01579b;
    <?php
      }
    ?>
  }

  ul.dropdown-content>li>a {
    /*Sélection des liens qui sont dans les listes déroulentes de la barre de navigation que l'on met en bleu*/
    background-color: white;
    color: #2e7d32;
    <?php
    if ($_SESSION['fonction'] == "infirmier") {
    ?>color: #4db6ac;
    <?php
    }
    ?><?php
      if ($_SESSION['fonction'] == "medecin" or $_SESSION['fonction'] == "administrateur") {
      ?>color: #4a148c;
    <?php
      }
    ?><?php
      if ($_SESSION['fonction'] == "secretaire") {
      ?>color: #01579b;
    <?php
      }
    ?>
  }

  ul.dropdown-content>li>a:hover {
    background-color: #2e7d32;
    <?php
    if ($_SESSION['fonction'] == "infirmier") {
    ?>background-color: #4db6ac;
    <?php
    }
    ?><?php
      if ($_SESSION['fonction'] == "medecin" or $_SESSION['fonction'] == "administrateur") {
      ?>background-color: #4a148c;
    <?php
      }
    ?><?php
      if ($_SESSION['fonction'] == "secretaire") {
      ?>background-color: #01579b;
    <?php
      }
    ?>color: white;
  }

  .dropdown-trigger {
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

  body {
    font: 12pt 'times new roman';
  }
</style>
<script type="text/javascript">
  $(document).ready(function() {
    $('.sidenav').sidenav();
    $(".dropdown-trigger").dropdown({
      hover: true, //déroulement de la liste au survol de l'élément
      inDuration: 400,
      outDuration: 300,
      coverTrigger: false, //la liste déroulante apparaîtra sous le déclencheur.
      belowOrigin: false,
      alignment: 'right'
    });
  });
</script>