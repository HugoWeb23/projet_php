<?php

session_start();

require('config.php');

if(isset($_POST['creer'])) {
$nom = $_POST['nom'] ? $_POST['nom'] : '';
$prix = $_POST['prix'] ? $_POST['prix'] : '';

if(empty($nom) || empty($prix)) {
$message = 'Saisissez toutes les informations demandées';
} else {
$req = $bdd->prepare('INSERT INTO menus (nom, prix) VALUES (:nom, :prix)');
$req->bindValue('nom', $nom, PDO::PARAM_STR);
$req->bindValue('prix', $prix, PDO::PARAM_STR);
$req->execute();
$last_id = $bdd->lastInsertId();
header('location: ?id='.$last_id.'');
}
}

if(isset($_POST['valider'])) {
$produit = $_POST['produit'];    
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link href="css/styles.css" rel="stylesheet">
<title><?= $nom_site ?></title>
</head>
<body>
<?php include('header.php'); ?>
<div class="conteneur">
<div class="titre-page">
<h1>Gestion des menus</h1>
<a href="creerproduit">Gestion des menus</a>
</div>
<div class="contenu">
<?php 
if(!isset($_GET['id'])) {
?>
<form action="" method="post">
<?php if(isset($message)) { ?><h2 class="message-erreur"><?= $message; ?></h2> <?php } ?>
<label for="nom">Nom :</label> <input type="text" id="nom" name="nom" placeholder="Nom du menu">
<label for="nom">Prix :</label> <input type="text" id="prix" name="prix" placeholder="Prix du menu">
<input class="boutton-rouge" type="submit" name="creer" value="Sélectionner les produits">
</form>
<?php } ?>
<?php
if(isset($_GET['id'])) {
$id = $_GET['id'];

if(isset($_GET['supprimer'])) {
$req = $bdd->prepare('DELETE FROM menus_produits WHERE id = :id');
$req->bindValue('id', $_GET['supprimer'], PDO::PARAM_INT);
$req->execute();
}

if(isset($_GET['ajouter'])) {
$req = $bdd->prepare('INSERT INTO menus_produits (id_menu, id_produit) VALUES (:id_menu, :id_produit)');
$req->bindValue('id_menu', $id, PDO::PARAM_INT);
$req->bindValue('id_produit', $_GET['ajouter'], PDO::PARAM_INT);
$req->execute();
}
?>
<form action="" method="post">
<div class="flex-menu">
<div class="menu-apercu">
<h3>Composition du menu</h3>
<?php
$id_menu = $_GET['id'];
$req = $bdd->prepare('SELECT id_menu, prix FROM menus WHERE id_menu = :id');
$req->bindValue('id', $id_menu, PDO::PARAM_INT);
$req->execute();
$menu = $req->fetch();
if($req->rowCount() == 0) {
header('location: creermenu');
}
$req = $bdd->prepare('SELECT b.id as id, a.libelle as libelle, a.prix as prix FROM produits as a INNER JOIN menus_produits as b ON a.id_produit = b.id_produit WHERE b.id_menu = :id ORDER BY id ASC');
$req->bindValue('id', $id_menu, PDO::PARAM_INT);
$req->execute();
while($afficher = $req->fetch()) {
?>
<div class="apercu-produits">
<div class="libelle"><?= $afficher['libelle']; ?><a class="supprimer" href="creermenu?id=<?= $id; ?>&supprimer=<?= $afficher['id']; ?>">X</a></div>
<div class="prix"><?= $afficher['prix']; ?> €</div>
</div>
<?php 
}
$req = $bdd->prepare('SELECT SUM(a.prix) as total_produits FROM produits as a INNER JOIN menus_produits as b ON a.id_produit = b.id_produit WHERE b.id_menu = :id');
$req->bindValue('id', $id, PDO::PARAM_INT);
$req->execute();
$total = $req->fetch(PDO::FETCH_ASSOC);
?>
<?php
if($total['total_produits'] > 0) { ?>
<div class="total">Différence : <?php $diff = $total['total_produits'] - $menu['prix']; echo $diff; ?> €</div>
<div class="total">Total produits : <?= $total['total_produits']; ?> €</div>
<?php } ?>
<div class="total">Prix menu : <?= $menu['prix']; ?> €</div>
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
<input type="checkbox" name="produit[]" value="<?= $produit['id_produit']; ?>" id="produit<?= $produit['id_produit']; ?>">
<label for="produit<?= $produit['id_produit']; ?>">
<img src="<?= $produit['photo']; ?>">
<div class="details-produit">
<p>Nom : <?= $produit['libelle']; ?></p>
<p>Prix : <?= $produit['prix']; ?></p>
<a href="creermenu?id=<?= $id; ?>&ajouter=<?= $produit['id_produit']; ?>">Ajouter</a>
</label>
</div>
</div>
<?php } ?>
</div>
<?php } ?>
</div>
</div>
</div>
<?php } ?>
</form>
</div>
</body>
</html>