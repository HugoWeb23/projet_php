<?php

session_start();

require('config.php');

if(isset($_POST['creer'])) {
$nom = isset($_POST['nom']) ? $_POST['nom'] : '';
$prix = isset($_POST['prix']) ? $_POST['prix'] : '';
$etat = isset($_POST['etat']) ? $_POST['etat'] : '';

if(empty($nom) || empty($prix) || empty($etat)) {
$type = 1;
} elseif($prix < 1) {
$type = 2;
} elseif(isset($_SESSION['menu']) == null || !isset($_SESSION['menu'])) {
$type = 3;
} else {
switch($etat) {
case 1:
$etat = 1;
break;
case 2:
$etat = 2;
break;
default:
$etat = '';
break;
}
$req = $bdd->prepare('INSERT INTO menus (nom, prix, etat) VALUES (:nom, :prix, :etat)');
$req->bindValue('nom', $nom, PDO::PARAM_STR);
$req->bindValue('prix', $prix, PDO::PARAM_INT);
$req->bindValue('etat', $etat, PDO::PARAM_INT);
$req->execute();
$lastId = $bdd->lastInsertId();
$req = $bdd->prepare('INSERT INTO menus_produits (id_menu, id_produit) VALUES (:id_menu, :id_produit)');
foreach($_SESSION['menu'] as $menu) {
$req->bindValue('id_menu', $lastId, PDO::PARAM_INT);
$req->bindValue('id_produit', $menu, PDO::PARAM_INT);
$req->execute();
}
$type = 4;
}
switch($type) {
case 1:
$message = '<h2 class="message-erreur">Merci de remplir tous les champs</h2>';
break;
case 2:
$message = '<h2 class="message-erreur">Le prix ne peut pas être inférieur à 1</h2>';
break;
case 3:
$message = '<h2 class="message-erreur">Sélectionnez au moins un produit</h2>';    
break;
case 4:
$message = '<h2 class="message-confirmation">Le menu a été créé</h2>';
break;
}
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
<script src="js/functions.js"></script>
<link href="css/styles.css" rel="stylesheet">
<title><?= $nom_site ?></title>
</head>
<body>
<?php include('header.php'); ?>
<div class="conteneur">
<div class="titre-page">
<h1>Créer un menu</h1>
<a href="creerproduit">Gestion des menus</a>
</div>
<div class="contenu">
<?php
if(isset($_GET['ajouter'])) {
$id_produit = $_GET['ajouter'];
if(!isset($_SESSION['menu'])) {
$_SESSION['menu'] = array();
}
$_SESSION['menu'][] = $id_produit;
}
?>
<div class="infos-menu">
<?php if(isset($type)) { echo $message; } ?>
<form action="" method="post">
<label for="nom">Nom : </label><input type="text" name="nom" id="nom"<?php if(isset($nom) && $type != 4) { echo ' value='.$nom.''; } ?>>
<label for="prix">Prix : </label><input type="number" name="prix" id="prix"<?php if(isset($prix) && $type != 4) { echo ' value='.$prix.''; } ?>>
<input type="radio" name="etat" id="etat-1" value="1" checked><label for="etat-1">Actif<label><input type="radio" name="etat" id="etat-2" value="1" checked><label for="etat-2">Inactif<label>
<input type="submit" class="boutton-rouge" name="creer" value="Créer le menu">
</form>
</div>
<div class="flex-menu">
<div class="menu-apercu">
<h3>Composition du menu</h3>
<div id="resultat"></div>
<?php
if(isset($_SESSION['menu'])) {
$in = str_repeat('?,', count($_SESSION['menu']) - 1) . '?';
$sql = "SELECT id_produit, libelle, prix FROM produits WHERE id_produit IN ($in)";
$req = $bdd->prepare($sql);
$req->execute($_SESSION['menu']) or die(print_r($req->errorInfo(), TRUE));
while($afficher = $req->fetch()) {
$count = array_count_values($_SESSION['menu']);
foreach($count as $j => $k) {
if($afficher['id_produit'] == $j) {
$quantite = $k;
}
}
?>
<div class="apercu-produits">
<div class="libelle"><?= $afficher['libelle']; ?>
<div class="quantite">Quantité : 
<input type="text" class="quantite_saisie" value="<?= $quantite ?>">
</div>
<input type="button" class="ok" data-produit="<?= $afficher['id_produit']; ?>" data-quantite="<?= $quantite ?>" value="Valider quantité">
</div>
<div class="prix"><?= $afficher['prix']; ?> €</div>
<input type="button" class="supprimer" data-produit="<?= $afficher['id_produit']; ?>" value="supprimer">
</div>
<?php
}
}
?>

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
<p>Nom : <?= $produit['libelle']; ?></p>
<p>Prix : <?= $produit['prix']; ?></p>
<a href="creermenu2?ajouter=<?= $produit['id_produit']; ?>">Ajouter</a>
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