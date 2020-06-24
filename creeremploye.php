<?php

session_start();

require('config.php');
verif_connexion();
$permissions = verif_permissions($personnel['id_personnel'], array('c_employe'));
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
<h1>Créer un compte employé</h1>
<a href="gestionemployes">Gestion des employés</a>
</div>
<div id="messages"></div>
<form id="creeremploye" class="form">
<div class="loader" style="display: none"><img src="images/loader.gif"></div>
<div class="contenu">
<div class="employe-flex">   
<div class="infos-perso">
<h2>Informations personnelles</h2>
<label for="nom">Nom :</label> <input type="text" id="nom" name="nom" placeholder="Saisissez un nom">
<label for="prenom">Prénom :</label> <input type="text" id="prenom" name="prenom" placeholder="Saisissez un prenom">
<label for="date_naissance">Date de naissance :</label> <input type="date" id="date_naissance" name="date_naissance">
<label for="tel_fixe">Téléphone fixe :</label> <input type="text" id="tel_fixe" name="telephone" placeholder="Saisissez un numéro de téléphone">
<label for="gsm">GSM :</label> <input type="text" id="gsm" name="gsm" placeholder="Saisissez un numéro de GSM">
<label for="rue">Rue :</label> <input type="text" id="rue" name="rue" placeholder="Saisissez un nom de rue">
<label for="numero">Numéro :</label> <input type="text" id="numero" name="numero" placeholder="Saisissez un numéro d'habitation">
<label for="ville">Ville :</label> <input type="text" id="ville" name="ville" placeholder="Saisissez un nom de ville">
<label for="code_postal">Code postal :</label> <input type="text" id="code_postal" name="code_postal" placeholder="Saisissez un code postal">
<label for="pays">Pays :</label><select name="pays" id="pays"><option value="1">Belgique</option><option value="2">France</option></select>
</div>
<div class="identifiants">
<h2>Attribuer des fonctions</h2>
<div class="flex-fonctions">
<?php $req = $bdd->prepare('SELECT id_fonction, nom FROM fonctions ORDER BY id_fonction');
$req->execute();
while($fonctions = $req->fetch()) {
?>
<div class="content-fonctions">
<input type="checkbox" id="<?= $fonctions['id_fonction']; ?>" class="employe-fonction" value="<?= $fonctions['id_fonction']; ?>"><label for="<?= $fonctions['id_fonction']; ?>"><?= $fonctions['nom']; ?></label>  
</div>
<?php } ?>
</div>
<h2>Identifiants de connexion</h2>
<label for="email">Adresse email :</label> <input type="email" id="email" name="email"<?php if(isset($email) && $type != 3) { echo ' value="'.$email.'"'; } ?> placeholder="Saisissez une adresse email">
<label for="password">Mot de passe :</label> <input type="password" id="password" name="password" placeholder="Saisissez un mot de passe">
<label for="confirm_password">Confirmation :</label> <input type="password" id="confirm_password" name="confirm_password" placeholder="Répétez le mot de passe">
</div>
</div>
<input class="boutton-rouge" type="submit" name="valider" value="Créer le compte">
</form>
</div>
</div>
</body>
</html>