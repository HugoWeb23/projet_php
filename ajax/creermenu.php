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
case 1:
$etat = 1;
break;
case 2:
$etat = 2;
break;
default:
$etat = '';
break;
}
$req = $bdd->prepare('INSERT INTO menus (nom, prix, etat) VALUES (:nom, :prix, :etat)');
$req->bindValue('nom', $nom, PDO::PARAM_STR);
$req->bindValue('prix', $prix, PDO::PARAM_INT);
$req->bindValue('etat', $etat, PDO::PARAM_INT);
$req->execute();
$lastId = $bdd->lastInsertId();
$req = $bdd->prepare('INSERT INTO menus_produits (id_menu, id_produit) VALUES (:id_menu, :id_produit)');
foreach($_SESSION['menu'] as $menu) {
$req->bindValue('id_menu', $lastId, PDO::PARAM_INT);
$req->bindValue('id_produit', $menu, PDO::PARAM_INT);
$req->execute();
}
unset($_SESSION['menu']);
$type = 4;
}
switch($type) {
case 1:
echo '<h2 class="message-erreur">Merci de remplir tous les champs</h2>';
break;
case 2:
echo '<h2 class="message-erreur">Le prix ne peut pas être inférieur à 1</h2>';
break;
case 3:
echo '<h2 class="message-erreur">Sélectionnez au moins un produit</h2>';    
break;
case 4:
echo '<h2 class="message-confirmation">Le menu a été créé</h2>';
break;
}
}

?>