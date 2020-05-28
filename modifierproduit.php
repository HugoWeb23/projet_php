<?php

session_start();

require('config.php');

if(isset($_GET['id'])) {
$id = $_GET['id'];
$req = $bdd->prepare('SELECT * FROM produits WHERE id_produit = :id_produit');
$req->bindValue('id_produit', $id, PDO::PARAM_INT);
$req->execute();
$produit = $req->fetch(PDO::FETCH_ASSOC);
if($req->rowCount() < 1) {
header('location: gestionproduits');    
}

if(isset($_POST["modifier"])){
    $nom = isset($_POST['nom']) ? $_POST['nom'] : '';
    $prix = isset($_POST['prix']) ? $_POST['prix'] : '';
    $categories = isset($_POST['categories']) ? $_POST['categories'] : '';

    if(empty($nom) || empty($prix) || empty($categories)) {
        $message = 1;
    } else {

      
    if(isset($_FILES["photo"]) && $_FILES["photo"]["error"] == 0){
    $allowed = array("jpg" => "image/jpg", "JPG" => "image/jpg", "jpeg" => "image/jpeg", "png" => "image/png");
    $filename = $_FILES["photo"]["name"];
    $filetype = $_FILES["photo"]["type"];
    $filesize = $_FILES["photo"]["size"];
    $ext = pathinfo($filename, PATHINFO_EXTENSION);
    if(!array_key_exists($ext, $allowed)) {
    $message = 2;
    } else {

       
    $maxsize = 5 * 1024 * 1024;
    if($filesize > $maxsize) {
    $message = 3;
    } else {

       
    if(in_array($filetype, $allowed)){

    switch($filetype) {
    case 'image/jpeg':
    $filetype='jpeg';
    break; 
    case 'image/jpg':
    $filetype='jpg';
    break;
    case 'image/png':
    $filetype='png';
    break;   
    }
    $nom_image = nom_aleatoire().'.'.$filetype;
    unlink($produit['photo']);          
    move_uploaded_file($_FILES["photo"]["tmp_name"], "uploads/" . $nom_image);
    $req = $bdd->prepare('UPDATE produits SET photo = :photo WHERE id_produit = :id');
    $req->bindValue('photo','uploads/'.$nom_image, PDO::PARAM_STR);
    $req->bindValue('id', $id, PDO::PARAM_INT);
    $req->execute();
    } else {
    $message = 4;
    }
    }
}
    } else {
    
    }

    if(!isset($message) == 2 || !isset($message) == 3 || !isset($message) == 4) {
    $req = $bdd->prepare('UPDATE produits SET libelle = :nom, prix = :prix WHERE id_produit = :id');
    $req->bindValue('nom', $nom, PDO::PARAM_STR);
    $req->bindValue('prix', $prix, PDO::PARAM_INT);
    $req->bindValue('id', $id, PDO::PARAM_INT);
    $req->execute() or die(print_r($req->errorInfo(), TRUE));
    $req = $bdd->prepare('DELETE FROM produits_categories WHERE id_produit = :id_produit');
    $req->bindValue('id_produit', $id, PDO::PARAM_INT);
    $req->execute();  
    foreach ($categories as $categorie) {  
    $req = $bdd->prepare('INSERT INTO produits_categories (id_categorie, id_produit) VALUES (:id_categorie, :id_produit)');
    $req->bindValue('id_categorie', $categorie, PDO::PARAM_INT);
    $req->bindValue('id_produit', $id, PDO::PARAM_INT);
    $req->execute() or die(print_r($req->errorInfo(), TRUE));
    }
    $message = 6;
}
}

switch($message) {
    case 1:
    $message = '<h2 class="message-erreur">Merci de remplir tous les champs</h2>';
    break;
    case 2:
    $message = '<h2 class="message-erreur">Le format du fichier n\'est pas autorisé</h2>';
    break;
    case 3:
    $message = '<h2 class="message-erreur">La taille du fichier est supérieure à la limite autorisée</h2>';
    break;
    case 4:
    $message = '<h2 class="message-erreur">Il y a eu un problème de téléchargement de votre fichier. Veuillez réessayer</h2>';
    break;
    case 6:
    $message = '<h2 class="message-confirmation">Le produit a bien été modifié</h2>';
    break;    
}
}
}
if(isset($_POST['supprimer'])) {
$req = $bdd->prepare('DELETE FROM produits WHERE id_produit = :id');
$req->bindValue('id', $id, PDO::PARAM_INT);
$req->execute();
unlink($produit['photo']);
header('location: gestionproduits.php');    
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
<link href="css/styles.css" rel="stylesheet">
<link rel="stylesheet" href="js/jquery-ui/jquery-ui.min.css">
<title><?= $nom_site ?></title>
</head>
<body>
<?php include('header.php'); ?>
<div class="conteneur">
<div class="titre-page">
<h1>Modifier un produit</h1>
<a href="gestionproduits">Gestion des produits</a>
</div>
<?php if(isset($_POST['modifier'])) { echo $message; } ?>
<form action="" method="post" enctype="multipart/form-data">
<div class="contenu">
<div class="employe-flex">
<div class="infos-produit">
<h2>Informations sur le produit</h2>
<label for="nom">Nom :</label> <input type="text" name="nom" id="nom" value="<?= $produit['libelle']; ?>">
<label for="prix">Prix :</label> <input type="text" name="prix" id="prix" value="<?= $produit['prix']; ?>">
<label for="photo">Image (laisser vide pour ne pas modifier)</label> <input type="file" name="photo" id="photo">
</div>
<div class="categories">
<h2>Catégories</h2>
<div class="flex-fonctions">
<?php $req = $bdd->prepare('SELECT id_categorie, nom FROM categories ORDER BY nom');
$req->execute();
while($categ = $req->fetch()) {

$req2 = $bdd->prepare('SELECT id_categorie, id_produit FROM produits_categories WHERE id_categorie = :id_categorie AND id_produit = :id_produit');
$req2->bindValue('id_categorie', $categ['id_categorie'], PDO::PARAM_INT);
$req2->bindValue('id_produit', $id, PDO::PARAM_INT);
$req2->execute();
if($req2->rowCount() > 0) {
$checked = ' checked';    
} else {
$checked = null;    
}
?>
<div class="content-fonctions">
<input type="checkbox" name="categories[]" id="<?= $categ['id_categorie']; ?>" value="<?= $categ['id_categorie']; ?>"<?= $checked; ?>><label for="<?= $categ['id_categorie']; ?>"><?= $categ['nom']; ?></label>  
</div>
<?php } ?>
<input class="boutton-delete" type="submit" name="supprimer" value="Supprimer le produit">
</div>
</div>
</div>
<input class="boutton-rouge" type="submit" name="modifier" value="Modifier le produit">
</form>
</div>
</body>
</html>