<?php

session_start();

// Ajout d'un produit

if(isset($_POST['id_produit'])) {
$id_produit = $_POST['id_produit'];
if(!isset($_SESSION['menu'])) {
$_SESSION['menu'] = array();
}
$_SESSION['menu'][] = $id_produit;
}

// Modification de la quantité d'un produit

if(isset($_POST['produit']) && isset($_POST['quantite_saisie'])) {
if($_POST['quantite_saisie'] < 0 || $_POST['quantite_saisie'] > 30) {
echo '<h2 class="erreur-menu">La quantité ne peut pas être inférieure à 0 et supérieure à 30</h2>';
} elseif(!preg_match('`^[0-9]+$`', $_POST['quantite_saisie'])) {
echo '<h2 class="erreur-menu">Merci de saisir un chiffre</h2>';
} elseif(in_array($_POST['produit'], $_SESSION['menu']) == false) {
echo '<h2 class="erreur-menu">ID invalide</h2>';
} else {
$count = array_count_values($_SESSION['menu']);
foreach($count as $j => $k) {
if($_POST['produit'] == $j) {
$quantite = $k;
}
}
if($_POST['quantite_saisie'] < $quantite) {
$calcul = $quantite - $_POST['quantite_saisie'];
for($i = 0; $i < $calcul; $i++){
$chercher = array_search($_POST['produit'], $_SESSION['menu']);
array_splice($_SESSION['menu'], $chercher, 1);
}
} else {
$calcul = $_POST['quantite_saisie'] - $quantite;
for($i = 0; $i < $calcul; $i++){
array_push($_SESSION['menu'], $_POST['produit']);
}   
}
echo '<h2 class="validation-menu">La quantité a été modifée</h2>';
}
}

// Suppression d'un produit

if(isset($_POST['delete'])) {
if(in_array($_POST['delete'], $_SESSION['menu']) == false) {
echo '<h2 class="erreur-menu">ID invalide</h2>';
} else {
$chercher = array_search($_POST['delete'], $_SESSION['menu']);
$count = array_count_values($_SESSION['menu']);
foreach($count as $j => $k) {
if($_POST['delete'] == $j) {
$calcul = $k;
}
}
for($i = 0; $i < $calcul; $i++) {
array_splice($_SESSION['menu'], $chercher, 1);
}
if($_SESSION['menu'] == null) {
unset($_SESSION['menu']);
}
echo '<h2 class="validation-menu">Produit supprimé</h2>';
}
}

?>