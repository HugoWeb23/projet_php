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
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<script src="js/jquery.js"></script>
<script src="js/jquery-ui/jquery-ui.min.js"></script>
<script src="js/functions.js"></script>
<link rel="icon" href="images/favicon.ico"/>
<link href="css/styles.css" rel="stylesheet">
<link rel="stylesheet" href="js/jquery-ui/jquery-ui.min.css">
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
<table>
<thead>
<tr>
    <th>Nom</th>      
    <th>Prix</th>
    <th>État</th>
    <th>Date de création</th>
    <th>Actions</th>
</tr>
</thead>
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
    <tbody>
    <tr><td data-label="Nom">'.$afficher['nom'].'</td>
    <td data-label="Prix">'.$afficher['prix'].' €</td>
    <td data-label="État">'.$etat.'</td>
    <td data-label="Date de création">'.$afficher['date_creation'].'</td>
    <td data-label="Actions"><a href="?id='.$afficher['id_menu'].'">Modifier</a> - <a href="#" data-id="'.$afficher['id_menu'].'" class="supprimer-menu">Supprimer</a></td></tr>
    </tbody>
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
<div class="label-menu">
<label for="nom">Nom : </label><input type="text" class="lol" name="nom" id="nom" value="<?= $menu['nom']; ?>">
</div>
<div class="label-menu">
<label for="prix">Prix : </label><input type="number" name="prix" id="prix" value="<?= $menu['prix']; ?>">
</div>
<div class="label-menu">
<label for="etat">État : </label><input type="radio" name="etat" id="etat-1" value="1" <?php if($menu['etat'] == 1) { echo 'checked'; } ?>><label for="etat-1">Actif</label><input type="radio" name="etat" id="etat-2" value="2" <?php if($menu['etat'] == 2) { echo 'checked'; } ?>><label for="etat-2">Inactif</label>
</div>
<input type="submit" class="boutton-rouge" name="modifier" id="modifier_menu" value="Modifier le menu" data-menu_id="<?= $id_menu; ?>">
</form>
</div>
<div class="contenu">
<div class="flex-menu">
<div class="menu-apercu">
<h3>Composition du menu</h3>
<div id="resultat"></div>
<?php
$total_menu = 0;
$req = $bdd->prepare('SELECT b.quantite, b.id_produit as id, a.libelle as libelle, a.prix as prix FROM produits as a INNER JOIN menus_produits as b ON a.id_produit = b.id_produit WHERE b.id_menu = :id GROUP BY b.id_produit ORDER BY id ASC');
$req->bindValue('id', $id_menu, PDO::PARAM_INT);
$req->execute();
while($afficher = $req->fetch()) {
$total_menu += $afficher['prix'] * $afficher['quantite'];
?>
<div class="apercu-produits">
<div class="libelle"><?= $afficher['libelle']; ?>
<div class="quantite">Quantité : 
<input type="text" class="quantite_saisie" value="<?= $afficher['quantite']; ?>">
</div>
</div>
<div class="prix" data-prix="<?= $afficher['prix']; ?>"><?= $afficher['prix']; ?> €</div>
<input type="button" class="boutton-quantite produit_quantite" data-produit="<?= $afficher['id']; ?>" data-menu="<?= $menu['id_menu']; ?>" value="Valider quantité">
<input type="button" class="boutton-supprimer-produit supprimer_produit" data-produit="<?= $afficher['id']; ?>" data-menu="<?= $menu['id_menu']; ?>" value="Supprimer">
</div>
<?php } ?>
<div class="afficher-total">
<div id="diff">Différence : <span><?= abs($total_menu -  $menu['prix']); ?> €</span></div>
<div class="total">Total produits : <span><?= $total_menu; ?> €</span></div>
<div id="prix_menu">Prix menu : <span><?= $menu['prix']; ?> €</span></div>
</div>
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
<p>Nom : <span><?= $produit['libelle']; ?></span></p>
<p>Prix : <span><?= $produit['prix']; ?></span> €</p>
<button data-id_produit="<?= $produit['id_produit']; ?>" data-id_menu="<?= $id_menu; ?>" class="boutton-produits modifiermenu-ajouter-produit">Ajouter</button>
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