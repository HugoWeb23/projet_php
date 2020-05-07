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

if(isset($_POST['action']) && $_POST['action'] == 'modifier_menu') {
$id_menu = $_POST['id_menu'];
$nom = $_POST['nom'];
$prix = $_POST['prix'];
$etat = $_POST['etat'];

switch($etat) {
case 1:
$etat = 1;
break;
case 2:
$etat = 2;
break;
default:
$etat = 0;
break;
}

if(strlen($nom) == 0 || $prix == 0 || $etat == 0) {
echo '<h2 class="message-erreur">Merci de compléter tous les champs</h2>';
} else {
$req = $bdd->prepare('UPDATE menus SET nom = :nom, prix = :prix, etat = :etat WHERE id_menu = :id_menu');
$req->bindValue('nom', $nom, PDO::PARAM_STR);
$req->bindValue('prix', $prix, PDO::PARAM_INT);
$req->bindValue('etat', $etat, PDO::PARAM_INT);
$req->bindValue('id_menu', $id_menu, PDO::PARAM_INT);
$req->execute();
echo '<h2 class="message-confirmation">Les informations du menu ont été modifiées</h2>';
}
}

if(isset($_POST['action']) && $_POST['action'] == 'ajouter_produit') {
$id_menu = $_POST['id_menu'];
$id_produit = $_POST['id_produit'];

$req = $bdd->prepare('SELECT id_produit FROM produits WHERE id_produit = :id_produit');
$req->bindValue('id_produit', $id_produit, PDO::PARAM_INT);
$req->execute();
if($req->rowCount() > 0)
$req = $bdd->prepare('INSERT INTO menus_produits (id_menu, id_produit) VALUES (:id_menu, :id_produit)');
$req->bindValue('id_menu', $id_menu, PDO::PARAM_INT);
$req->bindValue('id_produit', $id_produit, PDO::PARAM_INT);
$req->execute();
}
?>