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
<link href="css/styles.css" rel="stylesheet">
<link rel="stylesheet" href="js/jquery-ui/jquery-ui.min.css">
    <title><?= $nom_site ?></title>
</head>
<body>
<?php include('header.php'); ?>
<div class="conteneur">
<div class="titre-page">
<h1>Gestion des catégories des produits</h1>
<a id="creer_categorie" href="#">Créer une catégorie</a>
</div>
<table>
<tr>
<th>Nom</th>
<th>Description</th>
<th>Actions</th>
</tr>
<?php
$req = $bdd->prepare('SELECT * FROM categories ORDER BY nom');
$req->execute();
while($categ = $req->fetch()) {
?>
<tr>
<td><?= $categ['nom']; ?></td>
<td><?= $categ['description']; ?></td>   
<td><a class="editer" data-id="<?= $categ['id_categorie']; ?>" href="#">Éditer</a> - <a data-id="<?= $categ['id_categorie']; ?>" class="supprimer_categorie" href="#">Supprimer</a></td>  
</tr>     
<?php } ?>
</table>
</div>
</body>
</html>