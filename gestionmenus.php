<?php

session_start();

require('config.php');

if(isset($_GET['id'])) {

$id_menu = $_GET['id'];
$req = $bdd->prepare('SELECT * FROM menus WHERE id_menu = :id');
$req->bindValue('id', $id_menu, PDO::PARAM_INT);
$req->execute();
$menu = $req->fetch();
if($req->rowCount() == 0) {
header('location: gestionmenus');
}
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
<script src="js/functions.js"></script>
<link href="css/styles.css" rel="stylesheet">
<title><?= $nom_site ?></title>
</head>
<body>
<?php include('header.php'); ?>
<div class="conteneur">
<div class="titre-page">
<h1>Gestion des menus</h1>
<?php
if(isset($_GET['id'])) {
echo '<a href="gestionmenus">Gestion des menus</a>';

} else {
    echo '<a href="creermenu">Créer un menu</a>';
}
?>
</div>
<div class="contenu">
<?php
if(!isset($_GET['id'])) { ?>
<table class="liste-employes">
<tr>
    <th>Nom</th>      
    <th>Prix</th>
    <th>État</th>
    <th>Date de création</th>
    <th>Actions</th>
</tr>
<?php
$req = $bdd->prepare('SELECT * FROM menus ORDER BY etat ASC, nom ASC');
$req->execute();
while($afficher = $req->fetch()) {
switch($afficher['etat']) {
case 2:
$etat = 'Inactif';
break;
case 1:
$etat = 'Actif';
break;
}
    echo '
    <tr><td>'.$afficher['nom'].'</td>
    <td>'.$afficher['prix'].' €</td>
    <td>'.$etat.'</td>
    <td>'.$afficher['date_creation'].'</td>
    <td><a href="?id='.$afficher['id_menu'].'">Modifier</a> - <a href="#" data-id="'.$afficher['id_menu'].'" class="supprimer-menu">Supprimer</a></td></tr>
    ';
}
?>
<?php } ?>
<?php
if(isset($_GET['id'])) {
?>
<div class="infos-menu">
<div id="resultat-menu"></div>
<div class="loader" style="display: none"><img src="images/loader.gif"></div>
<span class="titre-menu">Informations sur le menu</span>
<form id="modifierMenu" action="" method="post">
<label for="nom">Nom : </label><input type="text" class="lol" name="nom" id="nom" value="<?= $menu['nom']; ?>">
<label for="prix">Prix : </label><input type="number" name="prix" id="prix" value="<?= $menu['prix']; ?>">
<input type="radio" name="etat" id="etat-1" value="1" <?php if($menu['etat'] == 1) { echo 'checked'; } ?>><label for="etat-1">Actif</label><input type="radio" name="etat" id="etat-2" value="2" <?php if($menu['etat'] == 2) { echo 'checked'; } ?>><label for="etat-2">Inactif</label>
<input type="submit" class="boutton-rouge" name="modifier" id="modifier_menu" value="Modifier les informations du menu" data-menu_id="<?= $id_menu; ?>">
</form>
</div>
<div class="contenu">
<div class="flex-menu">
<div class="menu-apercu">
<h3>Composition du menu</h3>
<div id="resultat"></div>
<?php
$req = $bdd->prepare('SELECT COUNT(*) as compteur, b.id_produit as id, a.libelle as libelle, a.prix as prix FROM produits as a INNER JOIN menus_produits as b ON a.id_produit = b.id_produit WHERE b.id_menu = :id GROUP BY b.id_produit ORDER BY id ASC');
$req->bindValue('id', $id_menu, PDO::PARAM_INT);
$req->execute();
while($afficher = $req->fetch()) {
?>
<div class="apercu-produits">
<div class="libelle"><?= $afficher['libelle']; ?>
<div class="quantite">Quantité : 
<input type="text" class="quantite_saisie" value="<?= $afficher['compteur']; ?>">
</div>
<input type="button" class="produit_quantite" data-produit="<?= $afficher['id']; ?>" data-menu="<?= $menu['id_menu']; ?>" value="Valider quantité">
</div>
<div class="prix"><?= $afficher['prix']; ?> €</div>
<input type="button" class="supprimer_produit" data-produit="<?= $afficher['id']; ?>" data-menu="<?= $menu['id_menu']; ?>" value="supprimer">
</div>
<?php 
}
$req = $bdd->prepare('SELECT SUM(a.prix) as total_produits FROM produits as a INNER JOIN menus_produits as b ON a.id_produit = b.id_produit WHERE b.id_menu = :id');
$req->bindValue('id', $id_menu, PDO::PARAM_INT);
$req->execute();
$total = $req->fetch(PDO::FETCH_ASSOC);
?>
<?php
if($total['total_produits'] > 0) { ?>
<div class="total">Différence : <?php $diff = $total['total_produits'] - $menu['prix']; echo $diff; ?> €</div>
<div class="total">Total produits : <?= $total['total_produits']; ?> €</div>
<?php } ?>
<div class="total">Prix menu : <?= $menu['prix']; ?> €</div>
</div>
<div class="menu-categories">
<?php 
$req = $bdd->prepare('SELECT * FROM categories');
$req->execute();
while($categ = $req->fetch()) {
?>
<div class="nom-categorie">
<h2><?= $categ['nom']; ?></h2>
</div>
<div class="flex-produits">

<?php
$req2 = $bdd->prepare('SELECT a.id_produit as id_produit, a.photo as photo, a.libelle as libelle, a.prix as prix FROM produits as a INNER JOIN produits_categories as b ON a.id_produit = b.id_produit WHERE b.id_categorie = :id');
$req2->bindValue('id', $categ['id_categorie'], PDO::PARAM_INT);
$req2->execute() or die(print_r($req->errorInfo(), TRUE));
if($req2->rowCount() == 0) {
echo 'Aucun produit';    
}
while($produit = $req2->fetch()) {
?>
<div class="apercuproduit">
<img src="<?= $produit['photo']; ?>">
<div class="details-produit">
<p>Nom : <?= $produit['libelle']; ?></p>
<p>Prix : <?= $produit['prix']; ?></p>
<button data-id_produit="<?= $produit['id_produit']; ?>" data-id_menu="<?= $id_menu; ?>" class="modifiermenu-ajouter-produit">Ajouter</button>
</div>
</div>
<?php } ?>
</div>
<?php } ?>
</div>
</div>
</div>
<?php } ?>
</div>
</body>
</html>