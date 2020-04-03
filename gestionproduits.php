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
<h1>Gestion des produits</h1>
<a href="creerproduit">Créer un nouveau produit</a>
</div>
<div class="contenu">
<div class="flex-produits">
<?php 
$req = $bdd->prepare('SELECT * FROM produits');
$req->execute();
while($produit = $req->fetch()) {
?>
<div class="apercuproduit">
<img src="<?= $produit['photo']; ?>">
<div class="details-produit">
<p>Nom : <?= $produit['libelle']; ?></p>
<p>Prix : <?= $produit['prix']; ?></p>
</div>
<div class="modifier-produit">
<a href="modifierproduit?id=<?= $produit['id_produit']; ?>"><img src="images/edit.png"></a>
</div>
</div>
<?php } ?>
</div>
</div>