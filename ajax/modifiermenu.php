<?php

session_start();

require('../config.php');

if(isset($_POST['action']) && $_POST['action'] == 'modif_quantite') {

$id_menu = $_POST['id_menu'];
$id_produit = $_POST['produit'];
$quantite_saisie = $_POST['quantite_saisie'];

$req = $bdd->prepare('SELECT COUNT(*) as compteur FROM menus_produits WHERE id_produit = :id_produit AND id_menu = :id_menu');
$req->bindValue('id_produit', $id_produit, PDO::PARAM_INT);
$req->bindValue('id_menu', $id_menu, PDO::PARAM_INT);
$req->execute() or die(print_r($req->errorInfo(), TRUE));
$quantite = $req->fetch();

if($quantite_saisie < $quantite['compteur']) {
$calcul = $quantite['compteur'] - $quantite_saisie;
$req = $bdd->prepare('DELETE FROM menus_produits WHERE id_produit = :id_produit AND id_menu = :id_menu LIMIT :limite');
$req->bindValue('id_produit', $id_produit, PDO::PARAM_INT);
$req->bindValue('id_menu', $id_menu, PDO::PARAM_INT);
$req->bindValue('limite', $calcul, PDO::PARAM_INT);
$req->execute() or die(print_r($req->errorInfo(), TRUE));
} elseif($quantite_saisie > $quantite['compteur']) {
$calcul = $quantite_saisie - $quantite['compteur'];
$req = $bdd->prepare('INSERT INTO menus_produits (id_menu, id_produit) VALUES (:id_menu, :id_produit)');
$req->bindValue('id_menu', $id_menu, PDO::PARAM_INT);
$req->bindValue('id_produit', $id_produit, PDO::PARAM_INT);
for($i = 0; $i < $calcul; $i++) {
$req->execute();
}
}
echo 'ok';
}

if(isset($_POST['action']) && $_POST['action'] == 'supprimer_produit') {
$id_menu = $_POST['id_menu'];
$id_produit = $_POST['produit'];

$req = $bdd->prepare('DELETE FROM menus_produits WHERE id_produit = :id_produit AND id_menu = :id_menu');
$req->bindValue('id_produit', $id_produit, PDO::PARAM_INT);
$req->bindValue('id_menu', $id_menu, PDO::PARAM_INT);
$req->execute();
}

?>