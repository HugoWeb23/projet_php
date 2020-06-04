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
<script src="js/functions.js"></script>
<link rel="icon" href="images/favicon.ico"/>
<link href="css/styles.css" rel="stylesheet">
<title><?= $nom_site ?></title>
</head>
<body>
<?php include('header.php'); ?>
<div class="conteneur">
<div class="titre-page">
<h1>Gestion des permissions du personnel</h1>
</div>
<div class="permissions">
<div class="aa">
<span>#</span>
<span>Gestion des produits</span>
<span>Création des menus</span>
<span>Gestion des menus</span>
<span>Gestion des catégories de produits</span>
<span>Créer des employés</span>
<span>Gestion des employés</span>
<span>Gestion des fonctions</span>
<span>Gestion des permissions</span>
<span>Créer des clients</span>
<span>Gestion des clients</span>
<span>Créer des commandes</span>
<span>Gestion des commandes</span>
<span>Gestion des livraisons</span>
</div>
<?php
$req = $bdd->prepare('SELECT * FROM fonctions as a LEFT JOIN permissions as b ON a.id_fonction = b.id_fonction');
$req->execute();
while($afficher = $req->fetch()) {
?>
<div class="aa">
<span><?= $afficher['nom']; ?></span><input type="checkbox" name="text"<?php if($afficher['c_produit'] == 1) { echo ' checked'; } ?>>
<input type="checkbox" name="text"<?php if($afficher['g_produits'] == 1) { echo ' checked'; } ?>>
<input type="checkbox" name="text"<?php if($afficher['c_menu'] == 1) { echo ' checked'; } ?>>
<input type="checkbox" name="text"<?php if($afficher['g_menus'] == 1) { echo ' checked'; } ?>>
<input type="checkbox" name="text"<?php if($afficher['g_categ'] == 1) { echo ' checked'; } ?>>
<input type="checkbox" name="text"<?php if($afficher['c_employe'] == 1) { echo ' checked'; } ?>>
<input type="checkbox" name="text"<?php if($afficher['g_employe'] == 1) { echo ' checked'; } ?>>
<input type="checkbox" name="text"<?php if($afficher['g_fonctions'] == 1) { echo ' checked'; } ?>>
<input type="checkbox" name="text"<?php if($afficher['g_permissions'] == 1) { echo ' checked'; } ?>>
<input type="checkbox" name="text"<?php if($afficher['c_client'] == 1) { echo ' checked'; } ?>>
<input type="checkbox" name="text"<?php if($afficher['g_clients'] == 1) { echo ' checked'; } ?>>
<input type="checkbox" name="text"<?php if($afficher['c_commande'] == 1) { echo ' checked'; } ?>>
<input type="checkbox" name="text"<?php if($afficher['g_commandes'] == 1) { echo ' checked'; } ?>>
<input type="checkbox" name="text"<?php if($afficher['g_livraisons'] == 1) { echo ' checked'; } ?>>
</div>
<?php } ?>
</div>
</div>