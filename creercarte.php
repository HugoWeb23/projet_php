<?php

session_start();

require('config.php');

if(isset($_POST['valider'])) {
$expiration = $_POST['expiration'];
$id_client = $_POST['id_client'];

echo $id_client;
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
<h1>Créer une carte de fidélité</h1>
<a href="creerproduit">Gestion des cartes</a>
</div>
<div class="contenu">
<form action='' method='post'>
<div class="carte-flex">
<div class="expiration">
<select name="expiration">
<option value="1">6 mois</option>
<option value="2">1 an</option>
</select>
</div>
<div class="clients">
<input type="text" name="client" id="client_id" class="typeahead">
<div id="clients"></div>
</div>
</div>
<input class="boutton-rouge" type="submit" name="valider" value="Créer la carte">
</form>
</div>
</body>
</html>