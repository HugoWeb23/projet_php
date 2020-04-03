<?php

session_start();

require('config.php');

$id=0;
if(isset($_GET['modif'])) {
$id = $_GET['modif'];

if(isset($_POST['valider'])) {
$nom = isset($_POST['nom']) ? $_POST['nom'] : '';
$description = isset($_POST['description']) ? $_POST['description'] : '';   

$req = $bdd->prepare('UPDATE categories SET nom = :nom, description = :description WHERE id_categorie = :id');
$req->bindValue('nom', $nom, PDO::PARAM_STR);
$req->bindValue('description', $description, PDO::PARAM_STR);
$req->bindValue('id', $id, PDO::PARAM_INT);
$req->execute();
header('location: gestioncategories');     
}
}

if(isset($_POST['creer'])) {
$nom = isset($_POST['nom']) ? $_POST['nom'] : '';
$description = isset($_POST['description']) ? $_POST['description'] : '';
$req = $bdd->prepare('INSERT INTO categories (nom, description) VALUES (:nom, :description)');
$req->bindValue('nom', $nom, PDO::PARAM_STR);  
$req->bindValue('description', $description, PDO::PARAM_STR);
$req->execute();
header('location: gestioncategories');              
}

if(isset($_GET['supprimer'])) {
$id = $_GET['supprimer'];   
$req = $bdd->prepare('DELETE FROM categories WHERE id_categorie = :id');
$req->bindValue('id', $id, PDO::PARAM_INT);
$req->execute() or die(print_r($req->errorInfo(), TRUE));
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
<h1>Gestion des catégories des produits</h1>
<a href="?creer">Créer une catégorie</a>
</div>
<table>
<tr>
<th>Nom</th>
<th>Description</th>
<th>Actions</th>
</tr>
<form action="" method="post">
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
<td><?php if($categ['id_categorie'] == $id) { ?><input type="text" name="nom" value="<?= $categ['nom']; ?>"><?php } else { echo $categ['nom']; }  ?></td>
<td><?php if($categ['id_categorie'] == $id) { ?><textarea name="description"><?= $categ['description']; ?></textarea><?php } else { echo $categ['description']; }  ?></td>   
<td><?php if($categ['id_categorie'] == $id) { ?><input type="submit" name="valider" value="Valider"><?php } else { ?> <a href="?modif=<?= $categ['id_categorie']?>">Éditer</a> - <a href="?supprimer=<?= $categ['id_categorie']?>">Supprimer</a> <?php }  ?></td>  
</tr>     
<?php } ?>
</form>
</table>
</div>
</body>
</html>