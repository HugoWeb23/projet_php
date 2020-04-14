<?php

session_start();

require('config.php');

if(isset($_POST['supprimer'])) {
$test = array_search($_POST['id_produit'], $_SESSION['menu']);
for($i = 0; $i < $_POST['prod_quantite']; $i++) {
array_splice($_SESSION['menu'], $test, 1);
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
<h1>Créer un menu</h1>
<a href="creerproduit">Gestion des menus</a>
</div>
<div class="contenu">
<?php
if(isset($_GET['ajouter'])) {
$id_produit = $_GET['ajouter'];
if(!isset($_SESSION['menu'])) {
$_SESSION['menu'] = array();
}
$_SESSION['menu'][] = $id_produit;
}
?>
<div class="flex-menu">
<div class="menu-apercu">
<div id="resultat"></div>
<h3>Composition du menu</h3>
<?php
if(isset($_SESSION['menu'])) {
$in = str_repeat('?,', count($_SESSION['menu']) - 1) . '?';
$sql = "SELECT id_produit, libelle, prix FROM produits WHERE id_produit IN ($in)";
$req = $bdd->prepare($sql);
$req->execute($_SESSION['menu']) or die(print_r($req->errorInfo(), TRUE));
while($afficher = $req->fetch()) {
$count = array_count_values($_SESSION['menu']);
foreach($count as $j => $k) {
if($afficher['id_produit'] == $j) {
$quantite = $k;
}
}
?>
<div class="apercu-produits">
<div class="libelle"><?= $afficher['libelle']; ?><div class="quantite">Quantité : <input type="text" class="quantite_saisie" value="<?= $quantite ?>"></div><input type="button" class="ok" data-produit="<?= $afficher['id_produit']; ?>" data-quantite="<?= $quantite ?>" value="Valider quantité"></div>
<div class="prix"><?= $afficher['prix']; ?> €</div>
<input type="button" class="supprimer" data-produit="<?= $afficher['id_produit']; ?>" value="supprimer">
</div>
<?php
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
<a href="creermenu2?ajouter=<?= $produit['id_produit']; ?>">Ajouter</a>
</div>
</div>
<?php } ?>
</div>
<?php } ?>
<script type="text/javascript">

       
   
        $(document).ready(function() {
            var counter = 0;
           
            $(".addMe").click(function(){
                $(this).parents(".test").find(".addMe")
                
                counter++;
   
                $(".theCount").text(counter);
                $(this).parents(".test").find(".theCount")
            });
   
        });
    </script>
</div>
</div>
</div>
</div>
</body>
</html>