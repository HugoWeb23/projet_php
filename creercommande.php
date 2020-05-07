<?php

session_start();

require('config.php');

?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<!-- Script -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

<!-- jQuery UI -->
<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
<script src="js/functions.js"></script>
<link href="css/styles.css" rel="stylesheet">
<title><?= $nom_site ?></title>
</head>
<body>
<?php include('header.php'); ?>
<div class="conteneur">
<div class="titre-page">
<h1>Créer une commande</h1>
<a href="gestioncommandes">Gestion des commandes</a>
</div>
<div class="contenu">
<div class="infos-menu">
<div id="resultat-menu"></div>
<div class="loader" style="display: none"><img src="images/loader.gif"></div>
<span class="titre-menu">Informations commande</span>
<form id="creerCommande" action="" method="post">
<div class="commande-infos-client">
<label for="client">Chercher un client : </label><input type="text" name="client" id="nom_client" placeholder="Tapez un nom"><input type="text" id="selectuser_id" name="client_id" disabled><input type="button" id="viderclient" value="Supprimer">
</div>
<div class="commande-type">
<label for="type_commande">Type de commande :</label><input type="radio" name="type" value="1" id="1" checked><label for="1">Livraison</label> <input type="radio" name="type" value="2" id="2"><label for="2">Sur place</label>
</div>
<div class="commande-adresse">
<span class="titre-menu">Adresse de livraison</span>
<input type="text" id="rue" name="rue" placeholder="Rue">
<input type="text" id="numero" name="numéro" placeholder="Numéro">
<input type="text" id="code_postal" name="code_postal" placeholder="Code postal">
<input type="text" id="ville" name="ville" placeholder="Ville">
<input type="text" id="pays" name="pays" placeholder="Pays">
</div>
<?php
$req = $bdd->prepare('SELECT * FROM tables ORDER BY nb_places');
$req->execute();
?>
<label for="table">Table : </label><select name="table" id="table" disabled>
<option value="0">Aucune</option>
<?php
while ($table = $req->fetch()) {
echo '<option value="'.$table['id_table'].'">Table '.$table['id_table'].' ['.$table['nb_places'].' places]</option>';
}
?>
</select>
<input type="submit" class="boutton-rouge" name="creer" id="creer_menu" value="Créer la commande">
</form>
</div>
<div class="flex-menu">
<div class="menu-apercu">
<h3>Détails commande</h3>
<div id="resultat"></div>
<?php
if(isset($_SESSION['commande'])) {
if($_SESSION['commande'] == null) {
unset($_SESSION['commande']);
} else {
$in = str_repeat('?,', count($_SESSION['commande']) - 1) . '?';
$sql = "SELECT id_produit, libelle, prix FROM produits WHERE id_produit IN ($in)";
$req = $bdd->prepare($sql);
$req->execute($_SESSION['commande']) or die(print_r($req->errorInfo(), TRUE));
while($afficher = $req->fetch()) {
$count = array_count_values($_SESSION['commande']);
foreach($count as $j => $k) {
if($afficher['id_produit'] == $j) {
$quantite = $k;
}
}
?>
<div class="apercu-produits">
<div class="libelle"><?= $afficher['libelle']; ?>
<div class="quantite">Quantité : 
<input type="text" class="quantite_saisie" value="<?= $quantite ?>">
</div>
<input type="button" class="validerQuantite" data-produit="<?= $afficher['id_produit']; ?>" value="Valider quantité">
</div>
<div class="prix"><?= $afficher['prix']; ?> €</div>
<input type="button" class="supprimer" data-produit="<?= $afficher['id_produit']; ?>" value="supprimer">
</div>
<?php
}
}
}
?>

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
<p>Nom : <?= $produit['libelle']; ?></p>
<p>Prix : <?= $produit['prix']; ?></p>
<button data-id="<?= $produit['id_produit']; ?>" class="creermenu-ajouter-produit">Ajouter</button>
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