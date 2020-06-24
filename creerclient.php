<?php

session_start();

require('config.php');
verif_connexion();
$permissions = verif_permissions($personnel['id_personnel'], array('c_client'));
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
<script>
function toggleSelect() {
  var isChecked = document.getElementById("creercarte").checked;
  document.getElementById("expiration").disabled = !isChecked;
  document.getElementById("points").disabled = !isChecked;
}
</script>
<title><?= $nom_site ?></title>
</head>
<body>
<?php include('header.php'); ?>
<div class="conteneur">
<div class="titre-page">
<h1>Créer un compte client</h1>
<a href="gestionclients">Gestion des clients</a>
</div>
<div id="messages"></div>
<form id="creerclient" class="form">
<div class="contenu">
<div class="employe-flex">   
<div class="infos-perso">
<h2>Informations personnelles</h2>
<label for="nom">Nom :</label> <input type="text" id="nom" name="nom"<?php if(isset($nom) && $type != 3) { echo ' value="'.$nom.'"'; } ?> placeholder="Saisissez un nom">
<label for="prenom">Prénom :</label> <input type="text" id="prenom" name="prenom" placeholder="Saisissez un prenom">
<label for="date_naissance">Date de naissance :</label> <input type="date" id="date_naissance" name="date_naissance">
<label for="email">Email :</label> <input type="text" id="email" name="email" placeholder="Saisissez une adresse email">
<label for="telephone_fixe">Tél. fixe :</label> <input type="text" id="telephone_fixe" name="telephone_fixe" placeholder="Saisissez un numéro de fixe">
<label for="gsm">GSM :</label> <input type="text" id="gsm" name="gsm" placeholder="Saisissez un numéro de GSM">
<label for="rue">Rue :</label> <input type="text" id="rue" name="rue" placeholder="Saisissez un nom de rue">
<label for="numero">Numéro :</label> <input type="text" id="numero" name="numero" placeholder="Saisissez un numéro d'habitation">
<label for="ville">Ville :</label> <input type="text" id="ville" name="ville" placeholder="Saisissez un nom de ville">
<label for="code_postal">Code postal :</label> <input type="text" id="code_postal" name="code_postal" placeholder="Saisissez un code postal">
<label for="pays">Pays :</label><select name="pays" id="pays"><option value="Belgique">Belgique</option><option value="France">France</option></select>
</div>
<div class="identifiants">
<h2>Carte de fidélité</h2>
<div class="creer_carte">
<input type="checkbox" id="creercarte" name="creercarte" onClick="toggleSelect()" checked><label for="creercarte">Créer une carte de fidélité</label>
</div>
<label for="points">Nombre de points : <label>
<input type="number" id="points" name="points" value="0">
<label for="expiration">Durée de validité<label>
<select id="expiration" name="expire">
<option value="6" selected>6 mois</option>
<option value="12">1 an</option>
</select>
</div>
</div>
<input class="boutton-rouge" type="submit" name="valider" value="Créer le compte">
</form>
</div>
</div>
</body>
</html>