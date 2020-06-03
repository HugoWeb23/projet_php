<?php

session_start();

require('config.php');

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
<h1>Gestion des produits</h1>
<a href="creerproduit">Créer un nouveau produit</a>
</div>
<div class="contenu">
<div class="flex-produits">
<?php 
$req = $bdd->prepare('SELECT *, COUNT(b.id) as compteur, a.id_produit as id_produit FROM produits as a LEFT JOIN produits_categories as b ON a.id_produit = b.id_produit GROUP BY a.id_produit');
$req->execute();
while($produit = $req->fetch()) {
?>
<div class="apercuproduit">
<?php
if($produit['compteur'] == 0) {
echo '<div class="label-error">Aucune catégorie</div>';
}
?>
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