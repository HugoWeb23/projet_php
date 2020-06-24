<?php

session_start();

require('../config.php');

if(isset($_POST['nom']) && isset($_POST['prix']) && isset($_POST['etat'])) {
$nom = isset($_POST['nom']) ? $_POST['nom'] : '';
$prix = isset($_POST['prix']) ? $_POST['prix'] : '';
$etat = isset($_POST['etat']) ? $_POST['etat'] : '';

if(empty($nom) || empty($prix) || empty($etat)) {
$type = 1;
} elseif($prix < 1) {
$type = 2;
} elseif(isset($_SESSION['menu']) == null || !isset($_SESSION['menu'])) {
$type = 3;
} else {
switch($etat) {
case 2:
$etat = 2;
break;
case 1:
$etat = 1;
break;
default:
$etat = 1;
break;
}
$req = $bdd->prepare('INSERT INTO menus (nom, prix, etat, date_creation) VALUES (:nom, :prix, :etat, :date_creation)');
$req->bindValue('nom', $nom, PDO::PARAM_STR);
$req->bindValue('prix', $prix, PDO::PARAM_STR);
$req->bindValue('etat', $etat, PDO::PARAM_INT);
$req->bindValue('date_creation', date('Y-m-d'), PDO::PARAM_STR);
$req->execute();
$id_menu = $bdd->lastInsertId();
$req = $bdd->prepare('INSERT INTO menus_produits (id_menu, id_produit, quantite) VALUES (:id_menu, :id_produit, :quantite)');
foreach($_SESSION['menu'] as $key => $value) {
$req->bindValue('id_menu', $id_menu, PDO::PARAM_INT);   
$req->bindValue('id_produit', $key, PDO::PARAM_INT);
$req->bindValue('quantite', $value, PDO::PARAM_INT);   
$req->execute();
}
unset($_SESSION['menu']);
$type = 4;
}
switch($type) {
case 1:
$type = array("type" => "erreur", "message" => "Merci de remplir tous les champs !");
break; 
case 2:
$type = array("type" => "erreur", "message" => "Le prix ne peut pas être inférieur à 1");
break;
case 3:
$type = array("type" => "erreur", "message" => "Sélectionnez au moins un produit");  
break;
case 4:
$type = array("type" => "succes", "message" => "Le menu a été créé"); 
break;
}
echo json_encode($type);
}


?>