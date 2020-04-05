<?php

session_start();

require('config.php');

if(isset($_POST['valider'])) {
$produit = $_POST['produit'];    
var_dump($produit);    
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
<h1>Créer un menu</h1>
<a href="creerproduit">Gestion des menus</a>
</div>
<div class="contenu">
<form action="" method="post">
<input type="text" name="nom" placeholder="Nom du menu">
<div class="flex-menu">
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
$req2 = $bdd->prepare('SELECT * FROM produits as a INNER JOIN produits_categories as b ON a.id_produit = b.id_produit WHERE b.id_categorie = :id');
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
</label>
</div>
</div>
<?php } ?>
</div>
<?php } ?>
</div>
<div class="menu-apercu">
<h3>Composition du menu</h3>
</div>
</div>
<input class="boutton-rouge" type="submit" name="valider" value="Créer le menu">
</div>
</form>
</div>
</body>
</html>