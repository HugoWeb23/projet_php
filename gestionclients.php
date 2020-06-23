<?php

session_start();

require('config.php');
verif_connexion();

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
<h1>Chercher des clients</h1>
</div>
<div class="recherche-employe">
<label for="rechercher_client">Effectuer une recherche :</label> <input type="text" name="rechercher" id="rechercher_client" placeholder="Rechercher un nom, un prénom, une adresse, ...">
</div>
<div id="result"></div>
</div>
</body>
</html>
