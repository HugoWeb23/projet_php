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
<script src="js/functions.js"></script>
<link href="css/styles.css" rel="stylesheet">
<title><?= $nom_site ?></title>
</head>
<body>
<?php include('header.php'); ?>
<div class="conteneur">
<div class="titre-page">
<h1>Créer un menu</h1>
<a href="gestionmenus">Gestion des menus</a>
</div>
<div class="contenu">
<div class="infos-menu">
<div id="resultat-menu"></div>
<div class="loader" style="display: none"><img src="images/loader.gif"></div>
<span class="titre-menu">Informations sur le menu</span>
<form id="creerMenu" action="" method="post">
<label for="nom">Nom : </label><input type="text" class="lol" name="nom" id="nom">
<label for="prix">Prix : </label><input type="number" name="prix" id="prix">
<input type="radio" name="etat" id="etat-1" value="1" checked><label for="etat-1">Actif</label><input type="radio" name="etat" id="etat-2" value="2"><label for="etat-2">Inactif</label>
<input type="submit" class="boutton-rouge" name="creer" id="creer_menu" value="Créer le menu">
</form>
</div>
<div class="flex-menu">
<div class="menu-apercu">
<h3>Composition du menu</h3>
<div id="resultat"></div>
<?php
$total_menu = 0;
if(isset($_SESSION['menu'])) {
if($_SESSION['menu'] == null) {
unset($_SESSION['menu']);
} else {
$in = str_repeat('?,', count($_SESSION['menu']) - 1) . '?';
$sql = "SELECT id_produit, libelle, prix FROM produits WHERE id_produit IN ($in)";
$req = $bdd->prepare($sql);
$req->execute($_SESSION['menu']) or die(print_r($req->errorInfo(), TRUE));
while($afficher = $req->fetch()) {
$count = array_count_values($_SESSION['menu']);
foreach($count as $j => $k) {
if($afficher['id_produit'] == $j) {
$quantite = $k;
$total_menu += $afficher['prix'] * $quantite;
}
}
?>
<div class="apercu-produits">
<div class="libelle"><?= $afficher['libelle']; ?>
<div class="quantite">Quantité : 
<input type="text" class="quantite_saisie" value="<?= $quantite ?>">
</div>
<input type="button" class="validerQuantite" data-produit="<?= $afficher['id_produit']; ?>" value="Valider quantité">
</div>
<div class="prix" data-prix="<?= $afficher['prix']; ?>"><?= $afficher['prix']; ?> €</div>
<input type="button" class="supprimer" data-produit="<?= $afficher['id_produit']; ?>" value="supprimer">
</div>
<?php } ?>
<?php
}
}
?>
<div class="afficher-total">
<div class="total">Total des produits : <span><?= $total_menu; ?> €</span></div>
</div>
</div>
<div class="menu-categories">
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
<button data-id="<?= $produit['id_produit']; ?>" class="creermenu-ajouter-produit">Ajouter</button>
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