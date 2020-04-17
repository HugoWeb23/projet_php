<?php

session_start();

require('config.php');

if(isset($_POST['test'])) {
   var_dump($_POST['id']);
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
<h1>Gestion des clients</h1>
</div>
<div class="recherche-employe">
<label for="rechercher">Chercher un client :</label> <input type="text" name="rechercher" id="rechercher_client" placeholder="Rechercher un nom, un prénom, une adresse, ...">
</div>
<form action="" method="post">
<div id="result"></div>
</form>
</div>
</body>
</html>
