<?php

session_start();

require('../config.php');

// Création de la commande

if(isset($_POST['action']) && $_POST['action'] == 'creer_commande') {
$id_client = $_POST['id_client'];
$tel_fixe = $_POST['tel_fixe'];
$gsm = $_POST['gsm'];
$email = $_POST['email'];
$type_commande = $_POST['type_commande'];
$table = $_POST['table'];
$rue = $_POST['rue'];
$numero = $_POST['numero'];
$code_postal = $_POST['code_postal'];
$ville = $_POST['ville'];
$pays = $_POST['pays'];
$commentaire = $_POST['commentaire'];

switch($type_commande) {
case 1:
case 2:
case 3:
$type_commande = $type_commande;
break;
default:
$type_commande = 0;
}
if(strlen($id_client) == 0) {
$id_client = null;
}
if($type_commande == 1) {
if(isset($_SESSION['produits_commande']) && count($_SESSION['produits_commande']) || isset($_SESSION['menus_commande']) && count($_SESSION['menus_commande'])) {
$req = $bdd->prepare('SELECT * FROM adresses WHERE rue = :rue AND numero = :numero AND ville = :ville AND code_postal = :code_postal AND pays = :pays');
$req->bindValue('rue', $rue, PDO::PARAM_STR);
$req->bindValue('numero', $numero, PDO::PARAM_INT);
$req->bindValue('ville', $ville, PDO::PARAM_STR);
$req->bindValue('code_postal', $code_postal, PDO::PARAM_STR);
$req->bindValue('pays', $pays, PDO::PARAM_STR);
$req->execute() or die(print_r($req->errorInfo(), TRUE));
$adresse = $req->fetch();
if($req->rowCount() > 0) {
$id_adresse = $adresse['id_adresse'];
}
if($req->rowCount() == 0) {
$req = $bdd->prepare('INSERT INTO adresses (rue, numero, ville, code_postal, pays) VALUES (:rue, :numero, :ville, :code_postal, :pays)');
$req->bindValue('rue', $rue, PDO::PARAM_STR);
$req->bindValue('numero', $numero, PDO::PARAM_INT);
$req->bindValue('ville', $ville, PDO::PARAM_STR);
$req->bindValue('code_postal', $code_postal, PDO::PARAM_STR);
$req->bindValue('pays', $pays, PDO::PARAM_STR);
$req->execute() or die(print_r($req->errorInfo(), TRUE));
$id_adresse = $bdd->lastInsertId();
}
$req = $bdd->prepare('INSERT INTO livraisons (etat, id_adresse) VALUES (:etat, :id_adresse)');
$req->bindValue('etat', '0', PDO::PARAM_INT);
$req->bindValue('id_adresse', $id_adresse, PDO::PARAM_INT);
$req->execute() or die(print_r($req->errorInfo(), TRUE));
$id_livraison = $bdd->lastInsertId();

$req = $bdd->prepare('INSERT INTO commandes (date, id_personnel, id_client, id_livraison, type, etat, commentaire) VALUES (:date, :id_personnel, :id_client, :id_livraison, :type, :etat, :commentaire)');
$req->bindValue('date', date('Y-m-d H:i:s'), PDO::PARAM_STR);
$req->bindValue('id_personnel', $personnel['id_personnel'], PDO::PARAM_INT);
$req->bindValue('id_client', $id_client, PDO::PARAM_INT);
$req->bindValue('id_livraison', $id_livraison, PDO::PARAM_INT);
$req->bindValue('type', '1', PDO::PARAM_INT);
$req->bindValue('etat', '0', PDO::PARAM_INT);
$req->bindValue('commentaire', $commentaire, PDO::PARAM_STR);
$req->execute() or die(print_r($req->errorInfo(), TRUE));
$id_commande = $bdd->lastInsertId();

$req = $bdd->prepare('INSERT INTO commandes_contact (id_commande, tel_fixe, gsm, email) VALUES (:id_commande, :tel_fixe, :gsm, :email)');
$req->bindValue('id_commande', $id_commande, PDO::PARAM_INT);
$req->bindValue('tel_fixe', $tel_fixe, PDO::PARAM_STR);
$req->bindValue('gsm', $gsm, PDO::PARAM_STR);
$req->bindValue('email', $email, PDO::PARAM_STR);
$req->execute() or die(print_r($req->errorInfo(), TRUE));

if(isset($_SESSION['menus_commande']) && count($_SESSION['menus_commande']) > 0) {
$req = $bdd->prepare('INSERT INTO commandes_menus (id_commande, id_menu, quantite, etat) VALUES (:id_commande, :id_menu, :quantite, :etat)');
foreach($_SESSION['menus_commande'] as $key => $value) {
$req->bindValue('id_commande', $id_commande, PDO::PARAM_INT);   
$req->bindValue('id_menu', $key, PDO::PARAM_INT);
$req->bindValue('quantite', $value, PDO::PARAM_INT);   
$req->bindValue('etat', 0, PDO::PARAM_INT);  
$req->execute();
}
}
if(isset($_SESSION['produits_commande']) && count($_SESSION['produits_commande']) > 0) {
$req = $bdd->prepare('INSERT INTO commandes_produits (id_commande, id_produit, quantite, etat) VALUES (:id_commande, :id_produit, :quantite, :etat)');
foreach($_SESSION['produits_commande'] as $key => $value) {
$req->bindValue('id_commande', $id_commande, PDO::PARAM_INT);   
$req->bindValue('id_produit', $key, PDO::PARAM_INT);
$req->bindValue('quantite', $value, PDO::PARAM_INT);   
$req->bindValue('etat', 0, PDO::PARAM_INT);  
$req->execute();
}
}
unset($_SESSION['produits_commande']);
unset($_SESSION['menus_commande']);
$message = array("type" => "succes", "message" => "La commande a été créée !");
} else {
$message = array("type" => "erreur", "message" => "Sélectionnez au moins un produit ou un menu");
}
echo json_encode($message);
}
if($type_commande == 2) {
if(isset($_SESSION['produits_commande']) && count($_SESSION['produits_commande']) || isset($_SESSION['menus_commande']) && count($_SESSION['menus_commande'])) {
$req = $bdd->prepare('INSERT INTO commandes (date, id_personnel, id_table, id_client, type, etat, commentaire) VALUES (:date, :id_personnel, :id_table, :id_client, :type, :etat, :commentaire)');
$req->bindValue('date', date('Y-m-d H:i:s'), PDO::PARAM_STR);
$req->bindValue('id_personnel', $personnel['id_personnel'], PDO::PARAM_INT);
$req->bindValue('id_table', $table, PDO::PARAM_INT);
$req->bindValue('id_client', $id_client, PDO::PARAM_INT);
$req->bindValue('type', '2', PDO::PARAM_INT);
$req->bindValue('etat', '0', PDO::PARAM_INT);
$req->bindValue('commentaire', $commentaire, PDO::PARAM_STR);
$req->execute() or die(print_r($req->errorInfo(), TRUE));
$id_commande = $bdd->lastInsertId();

if(isset($_SESSION['menus_commande']) && count($_SESSION['menus_commande']) > 0) {
$req = $bdd->prepare('INSERT INTO commandes_menus (id_commande, id_menu, quantite, etat) VALUES (:id_commande, :id_menu, :quantite, :etat)');
foreach($_SESSION['menus_commande'] as $key => $value) {
$req->bindValue('id_commande', $id_commande, PDO::PARAM_INT);   
$req->bindValue('id_menu', $key, PDO::PARAM_INT);
$req->bindValue('quantite', $value, PDO::PARAM_INT);   
$req->bindValue('etat', 0, PDO::PARAM_INT);  
$req->execute();
}
}
if(isset($_SESSION['produits_commande']) && count($_SESSION['produits_commande']) > 0) {
$req = $bdd->prepare('INSERT INTO commandes_produits (id_commande, id_produit, quantite, etat) VALUES (:id_commande, :id_produit, :quantite, :etat)');
foreach($_SESSION['produits_commande'] as $key => $value) {
$req->bindValue('id_commande', $id_commande, PDO::PARAM_INT);   
$req->bindValue('id_produit', $key, PDO::PARAM_INT);
$req->bindValue('quantite', $value, PDO::PARAM_INT);   
$req->bindValue('etat', 0, PDO::PARAM_INT);  
$req->execute();
}
}
unset($_SESSION['produits_commande']);
unset($_SESSION['menus_commande']);
$message = array("type" => "succes", "message" => "La commande a été créée !");
} else {
$message = array("type" => "erreur", "message" => "Sélectionnez au moins un produit ou un menu");
}
echo json_encode($message);
}
if($type_commande == 3) {
if(isset($_SESSION['produits_commande']) && count($_SESSION['produits_commande']) || isset($_SESSION['menus_commande']) && count($_SESSION['menus_commande'])) {
$req = $bdd->prepare('INSERT INTO commandes (date, id_personnel, id_client, type, etat, commentaire) VALUES (:date, :id_personnel, :id_client, :type, :etat, :commentaire)');
$req->bindValue('date', date('Y-m-d H:i:s'), PDO::PARAM_STR);
$req->bindValue('id_personnel', $personnel['id_personnel'], PDO::PARAM_INT);
$req->bindValue('id_client', $id_client, PDO::PARAM_INT);
$req->bindValue('type', '3', PDO::PARAM_INT);
$req->bindValue('etat', '0', PDO::PARAM_INT);
$req->bindValue('commentaire', $commentaire, PDO::PARAM_STR);
$req->execute() or die(print_r($req->errorInfo(), TRUE));
$id_commande = $bdd->lastInsertId();

if(isset($_SESSION['menus_commande']) && count($_SESSION['menus_commande']) > 0) {
$req = $bdd->prepare('INSERT INTO commandes_menus (id_commande, id_menu, quantite, etat) VALUES (:id_commande, :id_menu, :quantite, :etat)');
foreach($_SESSION['menus_commande'] as $key => $value) {
$req->bindValue('id_commande', $id_commande, PDO::PARAM_INT);   
$req->bindValue('id_menu', $key, PDO::PARAM_INT);
$req->bindValue('quantite', $value, PDO::PARAM_INT);   
$req->bindValue('etat', 0, PDO::PARAM_INT);
$req->execute() or die(print_r($req->errorInfo(), TRUE));
}
}
if(isset($_SESSION['produits_commande']) && count($_SESSION['produits_commande']) > 0) {
$req = $bdd->prepare('INSERT INTO commandes_produits (id_commande, id_produit, quantite, etat) VALUES (:id_commande, :id_produit, :quantite, :etat)');
foreach($_SESSION['produits_commande'] as $key => $value) {
$req->bindValue('id_commande', $id_commande, PDO::PARAM_INT);   
$req->bindValue('id_produit', $key, PDO::PARAM_INT);
$req->bindValue('quantite', $value, PDO::PARAM_INT);   
$req->bindValue('etat', 0, PDO::PARAM_INT);  
$req->execute() or die(print_r($req->errorInfo(), TRUE));
}
}
$req = $bdd->prepare('INSERT INTO commandes_contact (id_commande, tel_fixe, gsm, email) VALUES (:id_commande, :tel_fixe, :gsm, :email)');
$req->bindValue('id_commande', $id_commande, PDO::PARAM_INT);
$req->bindValue('tel_fixe', $tel_fixe, PDO::PARAM_STR);
$req->bindValue('gsm', $gsm, PDO::PARAM_STR);
$req->bindValue('email', $email, PDO::PARAM_STR);
$req->execute() or die(print_r($req->errorInfo(), TRUE));
unset($_SESSION['produits_commande']);
unset($_SESSION['menus_commande']);
$message = array("type" => "succes", "message" => "La commande a été créée !");
} else {
$message = array("type" => "erreur", "message" => "Sélectionnez au moins un produit ou un menu");
}
echo json_encode($message);
}
}

// Ajout d'un menu à la commande

if(isset($_POST['id_menu']) && $_POST['action'] == 'ajouter_menu') {
$id_menu = $_POST['id_menu'];
if(!isset($_SESSION['menus_commande'])) {
$_SESSION['menus_commande'] = array();
}
if(array_key_exists($id_menu, $_SESSION['menus_commande'])) {
$_SESSION['menus_commande'][$id_menu] += 1;
} else {
$_SESSION['menus_commande'][$id_menu] = 1;
}
}

// Ajout d'un produit à la commande

if(isset($_POST['id_produit']) && $_POST['action'] == 'ajouter_produit') {
$id_produit = $_POST['id_produit'];
if(!isset($_SESSION['produits_commande'])) {
$_SESSION['produits_commande'] = array();
}
if(array_key_exists($id_produit, $_SESSION['produits_commande'])) {
$_SESSION['produits_commande'][$id_produit] += 1;
} else {
$_SESSION['produits_commande'][$id_produit] = 1;
}
}

// Modification de la quantité d'un menu dans une commande

if(isset($_POST['menu']) && isset($_POST['quantite_saisie']) && $_POST['action'] == 'menu_quantite') {
if($_POST['quantite_saisie'] < 0 || $_POST['quantite_saisie'] > 30) {
echo '<h2 class="erreur-menu">La quantité ne peut pas être inférieure à 0 et supérieure à 30</h2>';
} elseif(!preg_match('`^[0-9]+$`', $_POST['quantite_saisie'])) {
echo '<h2 class="erreur-menu">Merci de saisir un chiffre</h2>';
} elseif(array_key_exists($_POST['menu'], $_SESSION['menus_commande']) == false) {
echo '<h2 class="erreur-menu">ID invalide</h2>';
} else {
$_SESSION['menus_commande'][$_POST['menu']] = $_POST['quantite_saisie'];
if($_POST['quantite_saisie'] == 0) {
unset($_SESSION['menus_commande'][$_POST['menu']]);
}
echo '<h2 class="validation-menu">La quantité a été modifée</h2>';
}
}

// Suppression d'un menu d'une commande

if(isset($_POST['delete']) && $_POST['action'] == 'supprimer_menu') {
if(array_key_exists($_POST['delete'], $_SESSION['menus_commande']) == false) {
echo '<h2 class="erreur-menu">ID invalide</h2>';
} else {
unset($_SESSION['menus_commande'][$_POST['delete']]);
}
}

// Modification de la quantité d'un produit

if(isset($_POST['id_produit']) && isset($_POST['quantite_saisie']) && $_POST['action'] == 'produit_quantite') {
if($_POST['quantite_saisie'] < 0 || $_POST['quantite_saisie'] > 30) {
echo '<h2 class="erreur-menu">La quantité ne peut pas être inférieure à 0 et supérieure à 30</h2>';
} elseif(!preg_match('`^[0-9]+$`', $_POST['quantite_saisie'])) {
echo '<h2 class="erreur-menu">Merci de saisir un chiffre</h2>';
} elseif(array_key_exists($_POST['id_produit'], $_SESSION['produits_commande']) == false) {
echo '<h2 class="erreur-menu">ID invalide</h2>';
} else {
$_SESSION['produits_commande'][$_POST['id_produit']] = $_POST['quantite_saisie'];
if($_POST['quantite_saisie'] == 0) {
unset($_SESSION['produits_commande'][$_POST['id_produit']]);
}
echo '<h2 class="validation-menu">La quantité a été modifée</h2>';
}
}

// Suppression d'un produit

if(isset($_POST['delete']) && $_POST['action'] == 'supprimer_produit') {
if(array_key_exists($_POST['delete'], $_SESSION['produits_commande']) == false) {
echo '<h2 class="erreur-menu">ID invalide</h2>';
} else {
unset($_SESSION['produits_commande'][$_POST['delete']]);
}
}

?>