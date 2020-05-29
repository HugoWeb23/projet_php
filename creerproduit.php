<?php

session_start();

require('config.php');


if(isset($_POST["valider"])){
    $nom = isset($_POST['nom']) ? $_POST['nom'] : '';
    $prix = isset($_POST['prix']) ? $_POST['prix'] : '';
    $categories = isset($_POST['categories']) ? $_POST['categories'] : '';

    if(empty($nom) || empty($prix) || empty($categories)) {
        $message = 1;
    } else {

      
    if(isset($_FILES["photo"]) && $_FILES["photo"]["error"] == 0){
    $allowed = array("jpg" => "image/jpg", "jpeg" => "image/jpeg", "png" => "image/png");
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
          
           
    move_uploaded_file($_FILES["photo"]["tmp_name"], "uploads/" . $nom_image);
    $req = $bdd->prepare('INSERT INTO produits (libelle, prix, photo) VALUES (:nom, :prix, :photo)');
    $req->bindValue('nom', $nom, PDO::PARAM_STR);
    $req->bindValue('prix', $prix, PDO::PARAM_INT);
    $req->bindValue('photo','uploads/'.$nom_image, PDO::PARAM_STR);
    $req->execute();
    $lastid = $bdd->lastinsertid();
    foreach ($categories as $categorie) {
    $req = $bdd->prepare('INSERT INTO produits_categories (id_categorie, id_produit) VALUES (:id_categorie, :id_produit)');
    $req->bindValue('id_categorie', $categorie, PDO::PARAM_INT);
    $req->bindValue('id_produit', $lastid, PDO::PARAM_INT);
    $req->execute();
    }
    $message = 6;        
    } else {
    $message = 4; 
    }
    }
}
    } else {
       $message = 5;
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
    case 5:
    $message = '<h2 class="message-erreur">Erreur avec la photo</h2>';
    break;
    case 6:
    $message = '<h2 class="message-confirmation">Le produit a bien été ajouté</h2>';
    break;    
}
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
<h1>Créer un produit</h1>
<a href="gestionproduits">Gestion des produits</a>
</div>
<?php if(isset($_POST['valider'])) { echo $message; } ?>
<form action="" method="post" enctype="multipart/form-data">
<div class="contenu">
<div class="employe-flex">
<div class="infos-produit">
<h2>Informations sur le produit</h2>
<label for="nom">Nom :</label> <input type="text" name="nom" id="nom">
<label for="prix">Prix :</label> <input type="text" name="prix" id="prix">
<label for="photo">Image :</label> <input type="file" name="photo" id="photo">
</div>
<div class="categories">
<h2>Catégories</h2>
<div class="flex-fonctions">
<?php $req = $bdd->prepare('SELECT id_categorie, nom FROM categories ORDER BY nom');
$req->execute();
while($categ = $req->fetch()) {
?>
<div class="content-fonctions">
<input type="checkbox" name="categories[]" id="<?= $categ['id_categorie']; ?>" value="<?= $categ['id_categorie']; ?>"><label for="<?= $categ['id_categorie']; ?>"><?= $categ['nom']; ?></label>  
</div>
<?php } ?>
</div>
</div>
</div>
<input class="boutton-rouge" type="submit" name="valider" value="Créer le produit">
</form>
</div>
</body>
</html>