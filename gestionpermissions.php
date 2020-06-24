<?php

session_start();

require('config.php');
verif_connexion();
$permissions = verif_permissions($personnel['id_personnel'], array('g_permissions'));
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
<script src="js/functions.js"></script>
<script src="js/jquery-ui/jquery-ui.min.js"></script>
<link rel="icon" href="images/favicon.ico"/>
<link href="css/styles.css" rel="stylesheet">
<title><?= $nom_site ?></title>
</head>
<body>
<?php include('header.php'); ?>
<div class="conteneur">
<div class="titre-page">
<h1>Gestion des permissions du personnel</h1>
</div>
<?php
$req = $bdd->prepare('SELECT * FROM fonctions as a LEFT JOIN permissions as b ON a.id_fonction = b.id_fonction');
$req->execute();
while($afficher = $req->fetch()) {
?>
<div class="fonction">
<div class="nom-fonction">
<h3><?= $afficher['nom']; ?></h3>
</div>
<div class="permissions">
<div class="permission"><span><label> Créer un produit <input type="checkbox" data-type="1" data-id="<?= $afficher['id_permission'] ?>" name="text"<?php if($afficher['c_produit'] == 1) { echo ' checked'; } ?>></label></span></div>
<div class="permission"><span><label>Gestion des produits <input type="checkbox" data-type="2" data-id="<?= $afficher['id_permission'] ?>" name="text"<?php if($afficher['g_produits'] == 1) { echo ' checked'; } ?>></label></span></div>
<div class="permission"><span><label>Création des menus <input type="checkbox" data-type="3" data-id="<?= $afficher['id_permission'] ?>" name="text"<?php if($afficher['c_menu'] == 1) { echo ' checked'; } ?>></label></span></div>
<div class="permission"><span><label>Gestion des menus <input type="checkbox" data-type="4" data-id="<?= $afficher['id_permission'] ?>" name="text"<?php if($afficher['g_menus'] == 1) { echo ' checked'; } ?>></label></span></div>
<div class="permission"><span><label>Gestion des catégories de produits <input type="checkbox" data-id="<?= $afficher['id_permission'] ?>" data-type="5" name="text"<?php if($afficher['g_categ'] == 1) { echo ' checked'; } ?>></label></span></div>
<div class="permission"><span><label>Créer des employés <input type="checkbox" data-type="6" data-id="<?= $afficher['id_permission'] ?>" name="text"<?php if($afficher['c_employe'] == 1) { echo ' checked'; } ?>></label></span></div>
<div class="permission"><span><label>Gestion des employés <input type="checkbox" data-type="7" data-id="<?= $afficher['id_permission'] ?>" name="text"<?php if($afficher['g_employe'] == 1) { echo ' checked'; } ?>></label></span></div>
<div class="permission"><span><label>Gestion des fonctions <input type="checkbox" data-type="8" data-id="<?= $afficher['id_permission'] ?>" name="text"<?php if($afficher['g_fonctions'] == 1) { echo ' checked'; } ?>></label></span></div>
<div class="permission"><span><label>Gestion des permissions <input type="checkbox" data-type="9" data-id="<?= $afficher['id_permission'] ?>" name="text"<?php if($afficher['g_permissions'] == 1) { echo ' checked'; } ?>></label></span></div>
<div class="permission"><span><label>Créer des clients <input type="checkbox" data-type="10" data-id="<?= $afficher['id_permission'] ?>" name="text"<?php if($afficher['c_client'] == 1) { echo ' checked'; } ?>></label></span></div>
<div class="permission"><span><label>Gestion des clients <input type="checkbox" data-type="11" data-id="<?= $afficher['id_permission'] ?>" name="text"<?php if($afficher['g_clients'] == 1) { echo ' checked'; } ?>></label></span></div>
<div class="permission"><span><label>Créer des commandes <input type="checkbox" data-type="12" data-id="<?= $afficher['id_permission'] ?>" name="text"<?php if($afficher['c_commande'] == 1) { echo ' checked'; } ?>></label></span></div>
<div class="permission"><span><label>Gestion des commandes <input type="checkbox" data-type="13" data-id="<?= $afficher['id_permission'] ?>" name="text"<?php if($afficher['g_commandes'] == 1) { echo ' checked'; } ?>></label></span></div>
<div class="permission"><span><label>Gestion des livraisons <input type="checkbox" data-type="14" data-id="<?= $afficher['id_permission'] ?>" name="text"<?php if($afficher['g_livraisons'] == 1) { echo ' checked'; } ?>></label></span></div>
</div>
</div>
<?php } ?>
</div>
</body>
</html>