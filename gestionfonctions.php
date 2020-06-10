<?php

session_start();

require('config.php');

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
<h1>Gestion des fonctions</h1>
<a class="nouv-fonction" href="#">Créer une fonction</a>
</div>
<table>
<thead>
<tr>
<th>Nom</th>
<th>Actions</th>
</tr>
</thead>
<?php
$req = $bdd->prepare('SELECT * FROM fonctions');
$req->execute();
while($afficher = $req->fetch()) {
echo '
<tbody>
<tr>
<td data-label="Nom">'.$afficher['nom'].'</td>
<td data-label="Actions"><button class="editerfonction" data-id="'.$afficher['id_fonction'].'">Éditer</button> - <button class="supprimerfonction" data-id="'.$afficher['id_fonction'].'">Supprimer</button></td>
</tr>
</tbody>
';
}
?>
</table>
</div>
</body>
</html>