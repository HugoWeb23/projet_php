<?php

session_start();

require('config.php');

if(isset($_GET['id'])) {
$id_commande = $_GET['id'];
$req = $bdd->prepare('SELECT *, commandes.id_commande as id_commande FROM commandes LEFT JOIN livraisons ON commandes.id_livraison = livraisons.id_livraison LEFT JOIN adresses ON livraisons.id_adresse = adresses.id_adresse LEFT JOIN commandes_contact ON commandes.id_commande = commandes_contact.id_commande WHERE commandes.id_commande = :id_commande AND commandes.etat = 0');
$req->bindValue('id_commande', $id_commande, PDO::PARAM_INT);
$req->execute();
$commande = $req->fetch(PDO::FETCH_ASSOC);
if($req->rowCount() == 0) {
header('location: gestioncommandes');
}
} else {
header('location: gestioncommandes');
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<script src="js/jquery.js"></script>
<script src="js/jquery-ui/jquery-ui.min.js"></script>
<script src="js/functions.js"></script>
<link rel="icon" href="images/favicon.ico"/>
<link href="css/styles.css" rel="stylesheet">
<link rel="stylesheet" href="js/jquery-ui/jquery-ui.min.css">
<title><?= $nom_site ?></title>
</head>
<body>
<?php include('header.php'); ?>
<div class="conteneur">
<div class="titre-page">
<h1>Modifier une commande</h1>
<a href="gestioncommandes">Gestion des commandes</a>
</div>
<div class="contenu">
<div class="infos-menu">
<div id="resultat-commande"></div>
<div class="loader" style="display: none"><img src="images/loader.gif"></div>
<span class="titre-menu">Informations commande</span>
<form id="modifierCommande" data-id_commande="<?= $commande['id_commande']; ?>" action="" method="post">
<div class="commande-flex">
<div class="commande-infos">
<label for="client">Associer la commande  à un client : </label><input type="text" name="client" id="nom_client" placeholder="Tapez un nom, une adresse, ...">
<input type="text" id="user_id" name="client_id" value="<?= $commande['id_client']; ?>" disabled>
<input type="button" id="viderclient" value="Supprimer">
<div class="type_commande">
<label for="type_commande">Type de commande :</label>

<input type="radio" name="type" value="1" id="1" <?php if($commande['type'] == 1) { echo 'checked'; } ?>><label for="1">Livraison</label> 
<input type="radio" name="type" value="2" id="2" <?php if($commande['type'] == 2) { echo 'checked'; } ?>><label for="2">Sur place</label>
<input type="radio" name="type" value="3" id="3" <?php if($commande['type'] == 3) { echo 'checked'; } ?>><label for="3">À emporter</label> 
</div>
<?php
$req = $bdd->prepare('SELECT * FROM tables ORDER BY nb_places');
$req->execute();
?>
<label for="table">Table : </label><select name="table" id="table" disabled>
<option value="0">Aucune</option>
<?php
while ($table = $req->fetch()) {
if($commande['id_table'] == $table['id_table']) {
$selected = 'selected';
} else {
$selected = null;
}
echo '<option value="'.$table['id_table'].'" '.$selected.'>Table '.$table['id_table'].' ['.$table['nb_places'].' places]</option>';
}
?>
</select>
</div>
<div class="commande-contact">
<span class="titre-menu">Moyens de contact</span>
<input type="text" id="tel_fixe" name="tel_fixe" value="<?= $commande['tel_fixe']; ?>" placeholder="Téléphone fixe">
<input type="text" id="gsm" name="gsm" value="<?= $commande['gsm']; ?>" placeholder="Numéro de gsm">
<input type="text" id="email" name="email" value="<?= $commande['email']; ?>" placeholder="Adresse e-mail">
</div>
<div class="commande-adresse">
<span class="titre-menu">Adresse de livraison</span>
<input type="text" id="rue" name="rue" value="<?= $commande['rue']; ?>" placeholder="Rue">
<input type="text" id="numero" name="numéro" value="<?= $commande['numero']; ?>" placeholder="Numéro">
<input type="text" id="code_postal" name="code_postal" value="<?= $commande['code_postal']; ?>" placeholder="Code postal">
<input type="text" id="ville" name="ville" value="<?= $commande['ville']; ?>" placeholder="Ville">
<input type="text" id="pays" name="pays" value="<?= $commande['pays']; ?>" placeholder="Pays">
</div>
</div>
<div class="commentaire-commande">
<label for="commentaire">Commentaire(s) :</label>
<textarea id="commentaire"><?= $commande['commentaire']; ?></textarea>
</div>
<input type="submit" class="boutton-rouge" name="creer" id="creer_menu" value="Modifier les informations">
</form>
</div>
<div class="flex-menu">
<div class="menu-apercu">
<h3>Détails commande</h3>
<div id="resultat"></div>
<?php
$req = $bdd->prepare('SELECT b.quantite, b.id_menu as id_menu, a.nom, a.prix as prix FROM menus as a INNER JOIN commandes_menus as b ON a.id_menu = b.id_menu WHERE b.id_commande = :id GROUP BY id_menu ORDER BY id_menu ASC');
$req->bindValue('id', $id_commande, PDO::PARAM_INT);
$req->execute();
$totaux_menus = 0;
while($menu = $req->fetch()) {
$totaux_menus += $menu['prix'] * $menu['quantite'];
?>
<div class="apercu-menus">
    <div class="libelle"><?= $menu['nom']; ?>
    <div class="quantite">Quantité : 
    <input type="text" class="quantite_saisie" value="<?= $menu['quantite']; ?>">
    </div>
    </div>
    <div class="prix" data-prix="<?= $menu['prix']; ?>"><?= $menu['prix']; ?> €</div>
    <input type="button" class="boutton-quantite commandeMenuQuantite" data-menu="<?= $menu['id_menu']; ?>" data-commande="<?= $commande['id_commande']; ?>" value="Valider quantité">
    <input type="button" class="boutton-supprimer-produit commandeSupprimerMenu" data-menu="<?= $menu['id_menu']; ?>" data-commande="<?= $commande['id_commande']; ?>" value="supprimer">
    </div>

<?php } ?>
<?php
$req = $bdd->prepare('SELECT b.id_commande, b.quantite, b.id_produit, a.libelle as libelle, a.prix as prix FROM produits as a INNER JOIN commandes_produits as b ON a.id_produit = b.id_produit WHERE b.id_commande = :id GROUP BY b.id_produit ORDER BY id ASC');
$req->bindValue('id', $id_commande, PDO::PARAM_INT);
$req->execute();
$totaux_produits = 0;
while($afficher = $req->fetch()) {
$totaux_produits += $afficher['prix'] * $afficher['quantite'];
?>
<div class="apercu-produits">
<div class="libelle"><?= $afficher['libelle']; ?>
<div class="quantite">Quantité : 
<input type="text" class="quantite_saisie" value="<?= $afficher['quantite']; ?>">
</div>
<div class="prix" data-prix="<?= $afficher['prix']; ?>"><?= $afficher['prix']; ?> €</div>
<input type="button" class="boutton-quantite modifProduitQuantite" data-produit="<?= $afficher['id_produit']; ?>" data-commande="<?= $afficher['id_commande']; ?>" value="Valider quantité">
</div>
<input type="button" class="boutton-supprimer-produit supprimerProduit" data-produit="<?= $afficher['id_produit']; ?>" data-commande="<?= $commande['id_commande']; ?>" value="supprimer">
</div>
<?php 
}
?>
<div class="afficher-total">
<div class="total">Total commande : <span><?= $totaux_menus + $totaux_produits ?> €</span></div>
</div>
</div>
<div class="menu-categories">
<div class="nom-categorie">
<h2>Menus</h2>
</div>
<div class="flex-produits">
<?php
$req = $bdd->prepare('SELECT * FROM menus WHERE etat = 1');
$req->execute();
while($menu = $req->fetch()) {
?>
<div class="apercuproduit">
<div class="details-produit">
<p>Nom : <span><?= $menu['nom']; ?></span></p>
<p>Prix : <span><?= $menu['prix']; ?></span> €</p>
<button data-menu="<?= $menu['id_menu']; ?>" data-commande="<?= $commande['id_commande']; ?>" class="boutton-produits modifCommandeAjouterMenu">Ajouter</button>
</div>
</div>
<?php } ?>
</div>
<?php 
$req = $bdd->prepare('SELECT * FROM categories');
$req->execute();
while($categ = $req->fetch()) {
?>
<div class="nom-categorie">
<h2><?= $categ['nom']; ?></h2>
</div>
<div class="flex-produits">

<?php
$req2 = $bdd->prepare('SELECT a.id_produit as id_produit, a.photo as photo, a.libelle as libelle, a.prix as prix FROM produits as a INNER JOIN produits_categories as b ON a.id_produit = b.id_produit WHERE b.id_categorie = :id');
$req2->bindValue('id', $categ['id_categorie'], PDO::PARAM_INT);
$req2->execute() or die(print_r($req->errorInfo(), TRUE));
if($req2->rowCount() == 0) {
echo 'Aucun produit';    
}
while($produit = $req2->fetch()) {
?>
<div class="apercuproduit">
<label for="produit<?= $produit['id_produit']; ?>">
<img src="<?= $produit['photo']; ?>">
<div class="details-produit">
<p>Nom : <span><?= $produit['libelle']; ?></span></p>
<p>Prix : <span><?= $produit['prix']; ?> €</span></p>
<button data-produit="<?= $produit['id_produit']; ?>" data-commande="<?= $commande['id_commande']; ?>" class="boutton-produits modifCommandeAjouterProduit">Ajouter</button>
</div>
</div>
<?php } ?>
</div>
<?php } ?>
</div>
</div>
</div>
</div>
</body>
</html>