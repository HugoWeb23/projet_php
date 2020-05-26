<?php

session_start();

require('../config.php');

function recherche_categ(object $bdd, int $id_categorie) {
$req = $bdd->prepare('SELECT id_categorie FROM categories WHERE id_categorie = :id_categorie');
$req->bindValue('id_categorie', $id_categorie, PDO::PARAM_INT);
$req->execute();
$check = $req->rowCount();
return $check;
}

if(isset($_POST['action']) && $_POST['action'] == 'modifier') {
$id_categorie = isset($_POST['id_categorie']) ? $_POST['id_categorie'] : 0;
$nom = isset($_POST['nom']) ? $_POST['nom'] : '';
$description = isset($_POST['description']) ? $_POST['description'] : '';   

if(recherche_categ($bdd, $id_categorie) > 0) {
$req = $bdd->prepare('UPDATE categories SET nom = :nom, description = :description WHERE id_categorie = :id');
$req->bindValue('nom', $nom, PDO::PARAM_STR);
$req->bindValue('description', $description, PDO::PARAM_STR);
$req->bindValue('id', $id_categorie, PDO::PARAM_INT);
$req->execute();   
$message = array("type" => "succes"); 
} else {
$message = array("type" => "erreur", "message" => "Cette catégorie n'existe pas");
}
echo json_encode($message);
}


if(isset($_POST['action']) && $_POST['action'] == 'creer') {
$nom = isset($_POST['nom']) ? $_POST['nom'] : '';
$description = isset($_POST['description']) ? $_POST['description'] : '';
$req = $bdd->prepare('INSERT INTO categories (nom, description) VALUES (:nom, :description)');
$req->bindValue('nom', $nom, PDO::PARAM_STR);  
$req->bindValue('description', $description, PDO::PARAM_STR);
$req->execute();
$id_categorie = $bdd->lastInsertId();
echo $id_categorie;         
}

if(isset($_POST['action']) && $_POST['action'] == 'supprimer') {
$id_categorie = isset($_POST['id_categorie']) ? $_POST['id_categorie'] : 0;
if(recherche_categ($bdd, $id_categorie) > 0) {
$req = $bdd->prepare('DELETE FROM categories WHERE id_categorie = :id');
$req->bindValue('id', $id_categorie, PDO::PARAM_INT);
$req->execute() or die(print_r($req->errorInfo(), TRUE));
$message = array("type" => "succes");
} else {
$message = array("type" => "erreur", "message" => "Cette catégorie n'existe pas");
}
echo json_encode($message);
}

?>