<?php

session_start();

require('../config.php');

if(isset($_POST['action']) && $_POST['action'] == 'modifier_commande') {

$id_commande = $_POST['id_commande'];
$id_client = $_POST['id_client'] ? $_POST['id_client'] : null;
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

$req = $bdd->prepare('SELECT *, commandes.id_commande as id_commande, commandes_contact.id_commande as id_contact FROM commandes LEFT JOIN livraisons ON commandes.id_livraison = livraisons.id_livraison LEFT JOIN adresses ON livraisons.id_adresse = adresses.id_adresse LEFT JOIN commandes_contact ON commandes.id_commande = commandes_contact.id_commande WHERE commandes.id_commande = :id_commande AND commandes.etat = 0');
$req->bindValue('id_commande', $id_commande, PDO::PARAM_INT);
$req->execute() or die(print_r($req->errorInfo(), TRUE));
$commande = $req->fetch(PDO::FETCH_ASSOC);
$id_livraison = $commande['id_livraison'];
if($type_commande == 1) {
if($rue != $commande['rue'] || $numero != $commande['numero'] || $code_postal != $commande['code_postal'] || $ville != $commande['ville'] || $pays != $commande['pays']) {
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
if($commande['id_livraison'] == null) {
$req = $bdd->prepare('INSERT INTO livraisons (etat, id_adresse) VALUES (:etat, :id_adresse)');
$req->bindValue('etat', '0', PDO::PARAM_INT);
$req->bindValue('id_adresse', $id_adresse, PDO::PARAM_INT);
$req->execute() or die(print_r($req->errorInfo(), TRUE));
$id_livraison = $bdd->lastInsertId();
} else {
$req = $bdd->prepare('UPDATE livraisons SET id_adresse = :id_adresse WHERE id_livraison = :id_livraison');
$id_livraison = $commande['id_livraison'];
$req->bindValue('id_adresse', $id_adresse, PDO::PARAM_INT);
$req->bindValue('id_livraison', $id_livraison, PDO::PARAM_INT);
$req->execute() or die(print_r($req->errorInfo(), TRUE));
}
}
if($tel_fixe != $commande['tel_fixe'] || $gsm != $commande['gsm'] || $email != $commande['email']) {
if($commande['id_contact'] == null) {
$req = $bdd->prepare('INSERT INTO commandes_contact (id_commande, tel_fixe, gsm, email) VALUES (:id_commande, :tel_fixe, :gsm, :email)');
$req->bindValue('id_commande', $id_commande, PDO::PARAM_INT);
$req->bindValue('tel_fixe', $tel_fixe, PDO::PARAM_STR);
$req->bindValue('gsm', $gsm, PDO::PARAM_STR);
$req->bindValue('email', $email, PDO::PARAM_STR);
$req->execute() or die(print_r($req->errorInfo(), TRUE));
} else {
$req = $bdd->prepare('UPDATE commandes_contact SET tel_fixe = :tel_fixe, gsm = :gsm, email = :email WHERE id_commande = :id_commande');
$req->bindValue('tel_fixe', $tel_fixe, PDO::PARAM_STR);
$req->bindValue('gsm', $gsm, PDO::PARAM_STR);
$req->bindValue('email', $email, PDO::PARAM_STR);
$req->bindValue('id_commande', $id_commande, PDO::PARAM_INT);
$req->execute() or die(print_r($req->errorInfo(), TRUE));
}
}
$req = $bdd->prepare('UPDATE commandes SET id_client = :id_client, id_livraison = :id_livraison, type = 1, commentaire = :commentaire WHERE id_commande = :id_commande');
$req->bindValue('id_client', $id_client, PDO::PARAM_INT);
$req->bindValue('id_livraison', $id_livraison, PDO::PARAM_INT);
$req->bindValue('id_commande', $id_commande, PDO::PARAM_INT);
$req->bindValue('commentaire', $commentaire, PDO::PARAM_STR); 
$req->execute() or die(print_r($req->errorInfo(), TRUE));
$message = array("type" => "succes", "message" => "Les informations ont été validées");
} elseif($type_commande == 2) {
$req = $bdd->prepare('UPDATE commandes SET id_client = :id_client, id_table = :id_table, type = 2, commentaire = :commentaire WHERE id_commande = :id_commande');
$req->bindValue('id_client', $id_client, PDO::PARAM_INT);
$req->bindValue('id_table', $table, PDO::PARAM_INT);
$req->bindValue('id_commande', $id_commande, PDO::PARAM_INT);
$req->bindValue('commentaire', $commentaire, PDO::PARAM_STR);
$req->execute();
$message = array("type" => "succes", "message" => "Les informations ont été validées");
} elseif($type_commande == 3) {
if($commande['id_contact'] == null) {
$req = $bdd->prepare('INSERT INTO commandes_contact (id_commande, tel_fixe, gsm, email) VALUES (:id_commande, :tel_fixe, :gsm, :email)');
$req->bindValue('id_commande', $id_commande, PDO::PARAM_INT);
$req->bindValue('tel_fixe', $tel_fixe, PDO::PARAM_STR);
$req->bindValue('gsm', $gsm, PDO::PARAM_STR);
$req->bindValue('email', $email, PDO::PARAM_STR);
$req->execute() or die(print_r($req->errorInfo(), TRUE));
} else {
$req = $bdd->prepare('UPDATE commandes_contact SET tel_fixe = :tel_fixe, gsm = :gsm, email = :email WHERE id_commande = :id_commande');
$req->bindValue('tel_fixe', $tel_fixe, PDO::PARAM_STR);
$req->bindValue('gsm', $gsm, PDO::PARAM_STR);
$req->bindValue('email', $email, PDO::PARAM_STR);
$req->bindValue('id_commande', $id_commande, PDO::PARAM_INT);
$req->execute() or die(print_r($req->errorInfo(), TRUE));
}
$req = $bdd->prepare('UPDATE commandes SET id_client = :id_client, type = 3, commentaire = :commentaire WHERE id_commande = :id_commande');
$req->bindValue('id_client', $id_client, PDO::PARAM_INT);
$req->bindValue('id_commande', $id_commande, PDO::PARAM_INT);
$req->bindValue('commentaire', $commentaire, PDO::PARAM_STR);
$req->execute();
$message = array("type" => "succes", "message" => "Les informations ont été validées");
} else {
$message = array("type" => "erreur", "message" => "Le type de commande est invalide");
}
echo json_encode($message);
}

// Ajout d'un menu à la commande

if(isset($_POST['action']) && $_POST['action'] == 'ajouter_menu') {
$id_menu = $_POST['id_menu'];
$id_commande = $_POST['id_commande'];
    
$req = $bdd->prepare('SELECT id_menu FROM menus WHERE id_menu = :id_menu');
$req->bindValue('id_menu', $id_menu, PDO::PARAM_INT);
$req->execute() or die(print_r($req->errorInfo(), TRUE));
if($req->rowCount() > 0) {
$req = $bdd->prepare('SELECT id_menu FROM commandes_menus WHERE id_menu = :id_menu AND id_commande = :id_commande');
$req->bindValue('id_menu', $id_menu, PDO::PARAM_INT);
$req->bindValue('id_commande', $id_commande, PDO::PARAM_INT);
$req->execute() or die(print_r($req->errorInfo(), TRUE));
if($req->rowCount() == 0) {
$req = $bdd->prepare('INSERT INTO commandes_menus (id_commande, id_menu, quantite) VALUES (:id_commande, :id_menu, :quantite)');
$req->bindValue('id_commande', $id_commande, PDO::PARAM_INT);
$req->bindValue('id_menu', $id_menu, PDO::PARAM_INT);
$req->bindValue('quantite', 1, PDO::PARAM_INT);
$req->execute() or die(print_r($req->errorInfo(), TRUE));
} else {
$req = $bdd->prepare('UPDATE commandes_menus SET quantite = quantite+1 WHERE id_menu = :id_menu AND id_commande = :id_commande');
$req->bindValue('id_menu', $id_menu, PDO::PARAM_INT);
$req->bindValue('id_commande', $id_commande, PDO::PARAM_INT);
$req->execute() or die(print_r($req->errorInfo(), TRUE));
}
}
}

// Ajout d'un produit à la commande

if(isset($_POST['action']) && $_POST['action'] == 'ajouter_produit') {
$id_produit = $_POST['id_produit'];
$id_commande = $_POST['id_commande'];
    
$req = $bdd->prepare('SELECT id_produit FROM produits WHERE id_produit = :id_produit');
$req->bindValue('id_produit', $id_produit, PDO::PARAM_INT);
$req->execute() or die(print_r($req->errorInfo(), TRUE));
if($req->rowCount() > 0) {
$req = $bdd->prepare('SELECT id_produit FROM commandes_produits WHERE id_produit = :id_produit AND id_commande = :id_commande');
$req->bindValue('id_produit', $id_produit, PDO::PARAM_INT);
$req->bindValue('id_commande', $id_commande, PDO::PARAM_INT);
$req->execute() or die(print_r($req->errorInfo(), TRUE));
if($req->rowCount() == 0) {
$req = $bdd->prepare('INSERT INTO commandes_produits (id_commande, id_produit, quantite) VALUES (:id_commande, :id_produit, :quantite)');
$req->bindValue('id_commande', $id_commande, PDO::PARAM_INT);
$req->bindValue('id_produit', $id_produit, PDO::PARAM_INT);
$req->bindValue('quantite', 1, PDO::PARAM_INT);
$req->execute() or die(print_r($req->errorInfo(), TRUE));
} else {
$req = $bdd->prepare('UPDATE commandes_produits SET quantite = quantite+1 WHERE id_produit = :id_produit AND id_commande = :id_commande');
$req->bindValue('id_produit', $id_produit, PDO::PARAM_INT);
$req->bindValue('id_commande', $id_commande, PDO::PARAM_INT);
$req->execute() or die(print_r($req->errorInfo(), TRUE));
}
}
}

if(isset($_POST['action']) && $_POST['action'] == 'commande_quantite_menu') {
$id_commande = $_POST['id_commande'];
$id_menu = $_POST['id_menu'];
$quantite = $_POST['quantite'];

$req = $bdd->prepare('UPDATE commandes_menus SET quantite = :quantite, etat = 0 WHERE id_commande = :id_commande AND id_menu = :id_menu');
$req->bindValue('quantite', $quantite, PDO::PARAM_INT);
$req->bindValue('id_commande', $id_commande, PDO::PARAM_INT);
$req->bindValue('id_menu', $id_menu, PDO::PARAM_INT);
$req->execute();
echo '<h2 class="validation-menu">La quantité a été modifée</h2>';
}

if(isset($_POST['action']) && $_POST['action'] == 'commande_supprimer_menu') {
$id_commande = $_POST['id_commande'];
$id_menu = $_POST['id_menu'];

$req = $bdd->prepare('DELETE FROM commandes_menus WHERE id_menu = :id_menu AND id_commande = :id_commande');
$req->bindValue('id_commande', $id_commande, PDO::PARAM_INT);
$req->bindValue('id_menu', $id_menu, PDO::PARAM_INT);
$req->execute();
}

if(isset($_POST['action']) && $_POST['action'] == 'commande_quantite_produit') {
$id_commande = $_POST['id_commande'];
$id_produit = $_POST['id_produit'];
$quantite = $_POST['quantite'];
    
$req = $bdd->prepare('UPDATE commandes_produits SET quantite = :quantite, etat = 0 WHERE id_commande = :id_commande AND id_produit = :id_produit');
$req->bindValue('quantite', $quantite, PDO::PARAM_INT);
$req->bindValue('id_commande', $id_commande, PDO::PARAM_INT);
$req->bindValue('id_produit', $id_produit, PDO::PARAM_INT);
$req->execute();
echo '<h2 class="validation-menu">La quantité a été modifée</h2>';
}
    
if(isset($_POST['action']) && $_POST['action'] == 'commande_supprimer_produit') {
$id_commande = $_POST['id_commande'];
$id_produit = $_POST['id_produit'];
    
$req = $bdd->prepare('DELETE FROM commandes_produits WHERE id_produit = :id_produit AND id_commande = :id_commande');
$req->bindValue('id_commande', $id_commande, PDO::PARAM_INT);
$req->bindValue('id_produit', $id_produit, PDO::PARAM_INT);
$req->execute();
}


?>