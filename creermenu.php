<?php

session_start();

require('config.php');
verif_connexion();
$permissions = verif_permissions($personnel['id_personnel'], array('c_menu'));
if($permissions[0] == 0) {
header('location: index');
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
<h1>Créer un menu</h1>
<a href="gestionmenus">Gestion des menus</a>
</div>
<div class="contenu">
<div class="infos-menu">
<div id="resultat-menu"></div>
<div class="loader" style="display: none"><img src="images/loader.gif"></div>
<span class="titre-menu">Informations sur le menu</span>
<form id="creerMenu" action="" method="post">
<div class="label-menu">
<label for="nom">Nom : </label><input type="text" class="lol" name="nom" id="nom">
</div>
<div class="label-menu">
<label for="prix">Prix : </label><input type="number" name="prix" id="prix" step="0.01">
</div>
<div class="label-menu">
<label for="etat">État : </label><input type="radio" name="etat" id="etat-1" value="1" checked><label for="etat-1">Actif</label><input type="radio" name="etat" id="etat-2" value="2"><label for="etat-2">Inactif</label>
</div>
<input type="submit" class="boutton-rouge" name="creer" id="creer_menu" value="Créer le menu">
</form>
</div>
<div class="flex-menu">
<div class="menu-apercu">
<h3>Composition du menu</h3>
<div id="resultat"></div>
<?php
$total_menu = 0;
if(isset($_SESSION['menu'])) {
if($_SESSION['menu'] == null) {
unset($_SESSION['menu']);
} else {
$in = str_repeat('?,', count($_SESSION['menu']) - 1) . '?';
$sql = "SELECT id_produit, libelle, prix FROM produits WHERE id_produit IN ($in)";
$req = $bdd->prepare($sql);
$i = 1;
foreach($_SESSION['menu'] as $j => $k) {
$req->bindValue($i, $j, PDO::PARAM_INT);
$i++;
}
$req->execute() or die(print_r($req->errorInfo(), TRUE));
while($afficher = $req->fetch()) {
foreach($_SESSION['menu'] as $j => $k) {
if($afficher['id_produit'] == $j) {
$quantite = $k;
$total_menu += $afficher['prix'] * $quantite;
}
}
?>
<div class="apercu-produits">
<div class="libelle"><?= $afficher['libelle']; ?>
<div class="quantite">Quantité : 
<input type="text" class="quantite_saisie" value="<?= $quantite ?>">
</div>
</div>
<div class="prix" data-prix="<?= $afficher['prix']; ?>"><?= number_format($afficher['prix'], 2); ?> €</div>
<input type="button" class="boutton-quantite validerQuantite" data-produit="<?= $afficher['id_produit']; ?>" value="Valider quantité">
<input type="button" class="boutton-supprimer-produit supprimer" data-produit="<?= $afficher['id_produit']; ?>" value="Supprimer">
</div>
<?php } ?>
<?php
}
}
?>
<div class="afficher-total">
<div class="total">Total des produits : <span><?= number_format($total_menu, 2); ?></span> €</div>
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
<label for="produit<?= $produit['id_produit']; ?>">
<img src="<?= $produit['photo']; ?>">
<div class="details-produit">
<p>Nom : <span><?= $produit['libelle']; ?></span></p>
<p>Prix : <span><?= number_format($produit['prix'], 2); ?></span> €</p>
<button data-id="<?= $produit['id_produit']; ?>" class="boutton-produits creermenu-ajouter-produit">Ajouter</button>
</div>
</div>
<?php } ?>
</div>
<?php } ?>
</div>
</div>
</div>
</div>
</body>
</html>