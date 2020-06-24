<?php

session_start();

// Ajout d'un produit

if(isset($_POST['id_produit'])) {
$id_produit = $_POST['id_produit'];
if(!isset($_SESSION['menu'])) {
$_SESSION['menu'] = array();
}
if(array_key_exists($id_produit, $_SESSION['menu'])) {
$_SESSION['menu'][$id_produit] += 1;
} else {
$_SESSION['menu'][$id_produit] = 1;
}
}

// Modification de la quantité d'un produit

if(isset($_POST['produit']) && isset($_POST['quantite_saisie'])) {
if($_POST['quantite_saisie'] < 0 || $_POST['quantite_saisie'] > 30) {
echo '<h2 class="erreur-menu">La quantité ne peut pas être inférieure à 0 et supérieure à 30</h2>';
} elseif(!preg_match('`^[0-9]+$`', $_POST['quantite_saisie'])) {
echo '<h2 class="erreur-menu">Merci de saisir un chiffre</h2>';
} elseif(array_key_exists($_POST['produit'], $_SESSION['menu']) == false) {
echo '<h2 class="erreur-menu">ID invalide</h2>';
} else {
$_SESSION['menu'][$_POST['produit']] = $_POST['quantite_saisie'];
if($_POST['quantite_saisie'] == 0) {
unset($_SESSION['menu'][$_POST['produit']]);
}
echo '<h2 class="validation-menu">La quantité a été modifée</h2>';
}
}

// Suppression d'un produit

if(isset($_POST['delete'])) {
if(array_key_exists($_POST['delete'], $_SESSION['menu']) == false) {
echo '<h2 class="erreur-menu">ID invalide</h2>';
} else {
unset($_SESSION['menu'][$_POST['delete']]);
}
}

?>