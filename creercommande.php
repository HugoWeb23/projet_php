<?php

session_start();

require('config.php');
verif_connexion();
$permissions = verif_permissions($personnel['id_personnel'], array('c_commande'));
if($permissions[0] == 0) {
header('location: index');
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
<h1>Créer une commande</h1>
<a href="gestioncommandes">Gestion des commandes</a>
</div>
<div class="contenu">
<div class="infos-menu">
<div id="resultat-commande"></div>
<span class="titre-menu">Informations commande</span>
<form id="creerCommande" class="form">
<div class="loader" style="display: none"><img src="images/loader.gif"></div>
<div class="commande-flex">
<div class="commande-infos">
<label for="client">Associer la commande  à un client : </label><input type="text" name="client" id="nom_client" placeholder="Tapez un nom, une adresse, ...">
<div class="viderclient">
<input type="text" id="user_id" name="client_id" disabled>
<input type="button" id="viderclient" value="Supprimer">
</div>
<div class="type_commande">
<label for="type_commande">Type de commande :</label>
<input type="radio" name="type" value="1" id="1" checked><label for="1">Livraison</label> 
<input type="radio" name="type" value="2" id="2"><label for="2">Sur place</label>
<input type="radio" name="type" value="3" id="3"><label for="3">À emporter</label> 
</div>
<?php
$req = $bdd->prepare('SELECT * FROM tables ORDER BY nb_places');
$req->execute();
?>
<label for="table">Table : </label><select name="table" id="table" disabled>
<option value="0">Aucune</option>
<?php
while ($table = $req->fetch()) {
echo '<option value="'.$table['id_table'].'">Table '.$table['id_table'].' ['.$table['nb_places'].' places]</option>';
}
?>
</select>
</div>
<div class="commande-contact">
<span class="titre-menu">Moyens de contact</span>
<input type="text" id="tel_fixe" name="tel_fixe" placeholder="Téléphone fixe">
<input type="text" id="gsm" name="gsm" placeholder="Numéro de gsm">
<input type="text" id="e-mail" name="email" placeholder="Adresse e-mail">
</div>
<div class="commande-adresse">
<span class="titre-menu">Adresse de livraison</span>
<input type="text" id="rue" name="rue" placeholder="Rue">
<input type="text" id="numero" name="numéro" placeholder="Numéro">
<input type="text" id="code_postal" name="code_postal" placeholder="Code postal">
<input type="text" id="ville" name="ville" placeholder="Ville">
<input type="text" id="pays" name="pays" placeholder="Pays">
</div>
</div>
<div class="commentaire-commande">
<label for="commentaire">Commentaire(s) :</label>
<textarea id="commentaire"></textarea>
</div>
<input type="submit" class="boutton-rouge" name="creer" id="creer_menu" value="Créer la commande">
</form>
</div>
<div class="flex-menu">
<div class="menu-apercu">
<h3>Détails commande</h3>
<div id="resultat"></div>
<?php
$totaux_menus = 0;
if(isset($_SESSION['menus_commande'])) {
if($_SESSION['menus_commande'] == null) {
unset($_SESSION['menus_commande']);
} else {
$in = str_repeat('?,', count($_SESSION['menus_commande']) - 1) . '?';
$sql = "SELECT * FROM menus WHERE id_menu IN ($in)";
$req = $bdd->prepare($sql);
$i = 1;
foreach($_SESSION['menus_commande'] as $j => $k) {
$req->bindValue($i, $j, PDO::PARAM_INT);
$i++;
}
$req->execute() or die(print_r($req->errorInfo(), TRUE));
while($menu = $req->fetch()) {
foreach($_SESSION['menus_commande'] as $j => $k) {
if($menu['id_menu'] == $j) {
$quantite = $k;
}
}
$totaux_menus += $menu['prix'] * $quantite;
?>
<div class="apercu-menus">
<div class="libelle"><?= $menu['nom']; ?>
<div class="quantite">Quantité : 
<input type="text" class="quantite_saisie" value="<?= $quantite ?>">
</div>
</div>
<div class="prix" data-prix="<?= $menu['prix']; ?>"><?= number_format($menu['prix'], 2); ?> €</div>
<input type="button" class="boutton-quantite menuCommandeQuantite" data-produit="<?= $menu['id_menu']; ?>" value="Valider quantité">
<input type="button" class="boutton-supprimer-produit supprimerMenuCommande" data-produit="<?= $menu['id_menu']; ?>" value="Supprimer">
</div>
<?php
}
}
}
$totaux_produits = 0;
if(isset($_SESSION['produits_commande'])) {
if($_SESSION['produits_commande'] == null) {
unset($_SESSION['produits_commande']);
} else {
$in = str_repeat('?,', count($_SESSION['produits_commande']) - 1) . '?';
$sql = "SELECT id_produit, libelle, prix FROM produits WHERE id_produit IN ($in)";
$req = $bdd->prepare($sql);
$i = 1;
foreach($_SESSION['produits_commande'] as $j => $k) {
$req->bindValue($i, $j, PDO::PARAM_INT);
$i++;
}
$req->execute() or die(print_r($req->errorInfo(), TRUE));
while($afficher = $req->fetch()) {
foreach($_SESSION['produits_commande'] as $j => $k) {
if($afficher['id_produit'] == $j) {
$quantite = $k;
}
}
$totaux_produits += $afficher['prix'] * $quantite;
?>
<div class="apercu-produits">
<div class="libelle"><?= $afficher['libelle']; ?>
<div class="quantite">Quantité : 
<input type="text" class="quantite_saisie" value="<?= $quantite ?>">
</div>
</div>
<div class="prix" data-prix="<?= $afficher['prix']; ?>"><?= number_format($afficher['prix'], 2); ?> €</div>
<input type="button" class="boutton-quantite commandeProduitQuantite" data-produit="<?= $afficher['id_produit']; ?>" value="Valider quantité">
<input type="button" class="boutton-supprimer-produit commandeSupprimerProduit" data-produit="<?= $afficher['id_produit']; ?>" value="Supprimer">
</div>
<?php
}
}
}
?>
<div class="afficher-total">
<div class="total">Total commande : <span><?= number_format($totaux_menus + $totaux_produits, 2) ?></span> €</div>
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
<div class="apercumenu">
<div class="details-produit">
<p>Nom : <span><?= $menu['nom']; ?></span></p>
<p>Prix : <span><?= number_format($menu['prix'], 2); ?></span> €</p>
<div class="ajouter-menu">
<button data-menu_id="<?= $menu['id_menu']; ?>" class="boutton-produits commandeAjouterMenu">Ajouter</button>
</div>
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
<p>Prix : <span><?= number_format($produit['prix'], 2); ?></span> €</p>
<button data-produit_id="<?= $produit['id_produit']; ?>" class="boutton-produits commandeAjouterProduit">Ajouter</button>
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