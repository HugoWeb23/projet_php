<?php

session_start();

require('../config.php');

// Ajout d'un menu à la commande

if(isset($_POST['id_menu']) && $_POST['action'] == 'ajouter_menu') {
    $id_menu = $_POST['id_menu'];
    if(!isset($_SESSION['menus_commande'])) {
    $_SESSION['menus_commande'] = array();
    }
    $_SESSION['menus_commande'][] = $id_menu;
    }

    // Ajout d'un produit à la commande

if(isset($_POST['id_produit']) && $_POST['action'] == 'ajouter_produit') {
    $id_produit = $_POST['id_produit'];
    if(!isset($_SESSION['produits_commande'])) {
    $_SESSION['produits_commande'] = array();
    }
    $_SESSION['produits_commande'][] = $id_produit;
    }

// Modification de la quantité d'un menu dans une commande

if(isset($_POST['menu']) && isset($_POST['quantite_saisie']) && $_POST['action'] == 'menu_quantite') {
    if($_POST['quantite_saisie'] < 0 || $_POST['quantite_saisie'] > 30) {
    echo '<h2 class="erreur-menu">La quantité ne peut pas être inférieure à 0 et supérieure à 30</h2>';
    } elseif(!preg_match('`^[0-9]+$`', $_POST['quantite_saisie'])) {
    echo '<h2 class="erreur-menu">Merci de saisir un chiffre</h2>';
    } elseif(in_array($_POST['menu'], $_SESSION['menus_commande']) == false) {
    echo '<h2 class="erreur-menu">ID invalide</h2>';
    } else {
    $count = array_count_values($_SESSION['menus_commande']);
    foreach($count as $j => $k) {
    if($_POST['menu'] == $j) {
    $quantite = $k;
    }
    }
    if($_POST['quantite_saisie'] < $quantite) {
    $calcul = $quantite - $_POST['quantite_saisie'];
    for($i = 0; $i < $calcul; $i++){
    $chercher = array_search($_POST['menu'], $_SESSION['menus_commande']);
    array_splice($_SESSION['menus_commande'], $chercher, 1);
    }
    } else {
    $calcul = $_POST['quantite_saisie'] - $quantite;
    for($i = 0; $i < $calcul; $i++){
    array_push($_SESSION['menus_commande'], $_POST['menu']);
    }   
    }
    echo '<h2 class="validation-menu">La quantité a été modifée</h2>';
    }
    }

// Suppression d'un menu d'une commande

    if(isset($_POST['delete']) && $_POST['action'] == 'supprimer_menu') {
        if(in_array($_POST['delete'], $_SESSION['menus_commande']) == false) {
        echo '<h2 class="erreur-menu">ID invalide</h2>';
        } else {
        $chercher = array_search($_POST['delete'], $_SESSION['menus_commande']);
        $count = array_count_values($_SESSION['menus_commande']);
        foreach($count as $j => $k) {
        if($_POST['delete'] == $j) {
        $calcul = $k;
        }
        }
        for($i = 0; $i < $calcul; $i++) {
        array_splice($_SESSION['menus_commande'], $chercher, 1);
        }
        if($_SESSION['menus_commande'] == null) {
        unset($_SESSION['menus_commande']);
        }
        echo '<h2 class="validation-menu">Menu supprimé</h2>';
        }
        }

// Modification de la quantité d'un produit

if(isset($_POST['id_produit']) && isset($_POST['quantite_saisie']) && $_POST['action'] == 'produit_quantite') {
    if($_POST['quantite_saisie'] < 0 || $_POST['quantite_saisie'] > 30) {
    echo '<h2 class="erreur-menu">La quantité ne peut pas être inférieure à 0 et supérieure à 30</h2>';
    } elseif(!preg_match('`^[0-9]+$`', $_POST['quantite_saisie'])) {
    echo '<h2 class="erreur-menu">Merci de saisir un chiffre</h2>';
    } elseif(in_array($_POST['id_produit'], $_SESSION['produits_commande']) == false) {
    echo '<h2 class="erreur-menu">ID invalide</h2>';
    } else {
    $count = array_count_values($_SESSION['produits_commande']);
    foreach($count as $j => $k) {
    if($_POST['id_produit'] == $j) {
    $quantite = $k;
    }
    }
    if($_POST['quantite_saisie'] < $quantite) {
    $calcul = $quantite - $_POST['quantite_saisie'];
    for($i = 0; $i < $calcul; $i++){
    $chercher = array_search($_POST['id_produit'], $_SESSION['produits_commande']);
    array_splice($_SESSION['produits_commande'], $chercher, 1);
    }
    } else {
    $calcul = $_POST['quantite_saisie'] - $quantite;
    for($i = 0; $i < $calcul; $i++){
    array_push($_SESSION['produits_commande'], $_POST['id_produit']);
    }   
    }
    echo '<h2 class="validation-menu">La quantité a été modifée</h2>';
    }
    }

// Suppression d'un produit

if(isset($_POST['delete']) && $_POST['action'] == 'supprimer_produit') {
    if(in_array($_POST['delete'], $_SESSION['produits_commande']) == false) {
    echo '<h2 class="erreur-menu">ID invalide</h2>';
    } else {
    $chercher = array_search($_POST['delete'], $_SESSION['produits_commande']);
    $count = array_count_values($_SESSION['produits_commande']);
    foreach($count as $j => $k) {
    if($_POST['delete'] == $j) {
    $calcul = $k;
    }
    }
    for($i = 0; $i < $calcul; $i++) {
    array_splice($_SESSION['produits_commande'], $chercher, 1);
    }
    if($_SESSION['produits_commande'] == null) {
    unset($_SESSION['produits_commande']);
    }
    echo '<h2 class="validation-menu">Produit supprimé</h2>';
    }
    }


?>