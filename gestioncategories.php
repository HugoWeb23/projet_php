<?php

session_start();

require('config.php');

?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<!-- Script -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

<!-- jQuery UI -->
<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
<script src="js/functions.js"></script>
<link href="css/styles.css" rel="stylesheet">
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
if($req->rowCount() < 1) {
    echo 'Aucune catégorie';
}
if(isset($_GET['creer'])) {
?>
<tr>
    <td><input type="text" name="nom"></td>
    <td><textarea name="description"></textarea></td>
    <td><input type="submit" name="creer" value="Créer"></td>
</tr>
<?php } ?>
<?php
while($categ = $req->fetch()) {
?>
<tr>
<td><?= $categ['nom']; ?></td>
<td><?= $categ['description']; ?></td>   
<td><a class="editer" data-id="<?= $categ['id_categorie']; ?>" href="#">Éditer</a> - <a data-id="<?= $categ['id_categorie']; ?>" href="#">Supprimer</a></td>  
</tr>     
<?php } ?>
</table>
</div>
</body>
</html>