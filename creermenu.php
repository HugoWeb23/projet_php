<?php

session_start();

require('config.php');


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
<input type="text" name="nom" placeholder="Nom du menu">
<div class="flex-produits">
<?php 
$req = $bdd->prepare('SELECT * FROM produits');
$req->execute();
while($produit = $req->fetch()) {
?>
<div class="apercuproduit">
<input type="checkbox" name="produit[]" id="produit<?= $produit['id_produit']; ?>">
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
</div>
</body>
</html>