<?php

session_start();

// Modification de la quantité d'un produit

if(isset($_POST['prod_quantite'])) {
if($_POST['quantite_saisie'] < $_POST['prod_quantite']) {
$calcul = $_POST['prod_quantite'] - $_POST['quantite_saisie'];
for($i = 0; $i < $calcul; $i++){
$chercher = array_search($_POST['produit'], $_SESSION['menu']);
array_splice($_SESSION['menu'], $chercher, 1);
}
} else {
$calcul = $_POST['quantite_saisie'] - $_POST['prod_quantite'];
for($i = 0; $i < $calcul; $i++){
array_push($_SESSION['menu'], $_POST['produit']);
}
}   
echo 'La quantité a été modifée'; 
}

// Suppression d'un produit

if(isset($_POST['delete'])) {
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
echo 'Produit supprimé';
}

?>