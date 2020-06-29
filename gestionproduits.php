<?php

session_start();

require('config.php');
verif_connexion();
$permissions = verif_permissions($personnel['id_personnel'], array('g_produits'));
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
<p>Prix : <?= number_format($produit['prix'], 2); ?> €</p>
</div>
<div class="modifier-produit">
<a href="modifierproduit?id=<?= $produit['id_produit']; ?>"><img src="images/edit.png"></a>
</div>
</div>
<?php } ?>
</div>
</div>