<?php

session_start();

require('config.php');

?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<script src="js/jquery.js"></script>
<script src="js/jquery-ui/jquery-ui.min.js"></script>
<script src="js/functions.js"></script>
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
<div class="loader" style="display: none"><img src="images/loader.gif"></div>
<span class="titre-menu">Informations commande</span>
<form id="creerCommande" action="" method="post">
<div class="commande-flex">
<div class="commande-infos">
<label for="client">Associer la commande  à un client : </label><input type="text" name="client" id="nom_client" placeholder="Tapez un nom, une adresse, ...">
<input type="text" id="user_id" name="client_id" disabled>
<input type="button" id="viderclient" value="Supprimer">
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
<input type="text" id="email" name="email" placeholder="Adresse e-mail">
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
$req->execute($_SESSION['menus_commande']) or die(print_r($req->errorInfo(), TRUE));
while($menu = $req->fetch()) {
$count = array_count_values($_SESSION['menus_commande']);
foreach($count as $j => $k) {
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
<input type="button" class="menuCommandeQuantite" data-produit="<?= $menu['id_menu']; ?>" value="Valider quantité">
</div>
<div class="prix" data-prix="<?= $menu['prix']; ?>"><?= $menu['prix']; ?> €</div>
<input type="button" class="supprimerMenuCommande" data-produit="<?= $menu['id_menu']; ?>" value="supprimer">
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
$req->execute($_SESSION['produits_commande']) or die(print_r($req->errorInfo(), TRUE));
while($afficher = $req->fetch()) {
$count = array_count_values($_SESSION['produits_commande']);
foreach($count as $j => $k) {
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
<input type="button" class="commandeProduitQuantite" data-produit="<?= $afficher['id_produit']; ?>" value="Valider quantité">
</div>
<div class="prix" data-prix="<?= $afficher['prix']; ?>"><?= $afficher['prix']; ?> €</div>
<input type="button" class="commandeSupprimerProduit" data-produit="<?= $afficher['id_produit']; ?>" value="supprimer">
</div>
<?php
}
}
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
<p>Prix : <span><?= $menu['prix']; ?></span></p>
<button data-menu_id="<?= $menu['id_menu']; ?>" class="commandeAjouterMenu">Ajouter</button>
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
<p>Prix : <span><?= $produit['prix']; ?></span></p>
<button data-produit_id="<?= $produit['id_produit']; ?>" class="commandeAjouterProduit">Ajouter</button>
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