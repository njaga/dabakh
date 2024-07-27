<?php
$type_contrat = $_POST['type_contrat'];
$pu = $_POST['pu'];
$tlv = ($pu * 2)/100;
$tom = ($pu * 3.6)/100;
$tva = ($pu * 18)/100;
if ($type_contrat == 'habitation') {
?>
    <div class="row">
        <div class="col s12 m6 input-field">
            <input type="text" name="prenom" id="prenom" required>
            <label for="prenom">Prénom</label>
        </div>
        <div class="col s5 input-field">
            <input type="text" name="nom" id="nom" required>
            <label for="nom">Nom</label>
        </div>
    </div>
    <div class="row">
        <div class="col s12 m4 input-field">
            <input type="text" name="telephone" id="telephone" required>
            <label for="telephone">Téléphone</label>
        </div>
        <div class="col s4 input-field">
            <input type="text" name="cni" id="cni" required>
            <label for="cni">N° CNI</label>
        </div>
        <div class="col s4 input-field">
            <input type="text" name="email" id="email">
            <label for="email">Email</label>
        </div>
    </div>
    <div class="row">
            <div class="col s12 m3 input-field hide">
                <input type="number" value="<?= $pu?>" name="prix_location" id="prix_location" required>
                <label for="prix_location">Prix location HT</label>
            </div>
            <div class="col s12 m3 input-field">
                <input type="number" value="<?= $pu + $tlv + $tom ?>" name="caution" id="caution" required>
                <label for="caution">Caution</label>
            </div>
            <div class="col s12 m3 input-field">
                <input type="number" value="<?= $pu + $tlv + $tom ?>" name="commision" id="commision" required>
                <label for="commision">Commission</label>
            </div>
            <div class="col s12 m3 input-field">
                <input type="number" value="<?= $pu + $tlv + $tom ?>" name="mensualite" id="mensualite" required>
                <label for="mensualite">Premier mois</label>
            </div>
            <div class="col s12 m3 input-field hide">
                <input type="number" value="<?= $pu + $tlv + $tom ?>" name="depot_garantie" id="depot_garantie" required>
                <label for="depot_garantie">Dépôt de garantie</label>
            </div>
        </div>
<?php
} else {
?>
    <div class="row">
        <div class="col s12 m6 input-field">
            <input type="text" name="societe" id="societe" required>
            <label for="societe">Société</label>
        </div>
        <div class="col s5 input-field">
            <input type="text" name="representant" id="representant" required>
            <label for="representant">Représentant</label>
        </div>
    </div>
    <div class="row">
        <div class="col s12 m4 input-field">
            <input type="text" name="rc" id="rc" required>
            <label for="rc">RC</label>
        </div>
        <div class="col s4 input-field">
            <input type="text" name="tel" id="tel" required>
            <label for="tel">Téléphone</label>
        </div>
        <div class="col s4 input-field">
            <input type="text" name="email" id="email">
            <label for="email">Email</label>
        </div>
    </div>
    <div class="row">
            <div class="col s12 m3 input-field hide">
                <input type="number" value="<?= $pu?>" name="prix_location" id="prix_location" required>
                <label for="prix_location">Prix location HT</label>
            </div>
            <div class="col s12 m3 input-field">
                <input type="number" value="<?= $pu + $tom + $tva ?>" name="caution" id="caution" required>
                <label for="caution">Caution</label>
            </div>
            <div class="col s12 m3 input-field">
                <input type="number" value="<?= $pu + $tom + $tva ?>" name="commision" id="commision" required>
                <label for="commision">Commission</label>
            </div>
            <div class="col s12 m3 input-field">
                <input type="number" value="<?= $pu + $tom + $tva ?>" name="mensualite" id="mensualite" required>
                <label for="mensualite">Premier mois</label>
            </div>
            <div class="col s12 m3 input-field hide">
                <input type="number" class="validate " value="<?= $pu + $tom + $tva ?>" name="depot_garantie" id="depot_garantie" required>
                <label for="depot_garantie" >Dépôt de garantie</label>
            </div>
        </div>
<?php
}
