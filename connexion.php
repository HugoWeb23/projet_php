<?php

session_start();

require_once('config.php');

if(isset($_POST['connexion'])) {
$email = isset($_POST['email']) ? $_POST['email'] : '';
$mdp = isset($_POST['mdp']) ? $_POST['mdp'] : '';

if(empty($email) || empty($mdp)) {
$erreur = 2;
} else {
   
$req = $bdd->prepare('SELECT id_personnel, pass FROM personnel WHERE email = :email LIMIT 1');
$req->bindValue('email', $email, PDO::PARAM_STR);
$req->execute();
$row = $req->fetch();
if($req->rowCount() == 0) {
$erreur = 1;
} else {
$check = password_verify($mdp, $row['pass']);

if($check == true) {
$_SESSION['id_personnel'] = $row['id_personnel'];
header('location: index');
} else {
$erreur = 1;
}
}
}
switch($erreur) {
case 1:
$erreur = "Identifiants incorrects !";
break;      
case 2:
$erreur = "Merci de remplir tous les champs !";   
break; 
}
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="css/styles.css" rel="stylesheet">
    <title><?= $nom_site ?> : Connexion</title>
</head>
<body> 
<form action="" method="post">   
<div class="connexion-box">
<div class="connexion-logo">
<img src="images/logo.png">   
</div>
<div class="connexion-nom"><?= $nom_site ?> : Accéder à mon compte employé</div>
<?php
if(isset($_POST['connexion'])) { echo $erreur; } ?>
<div class="email">
<label for="email">Adresse email :</label>
<input type="email" name="email" id="email">
</div>
<div class="mdp">
<label for="mdp">Mot de passe :</label> 
<input type="password" name="mdp" id="mdp">
<div class="box-connexion">
<input class="boutton-orange" type="submit" name="connexion" value="Connexion">
</div>
</div>
</div>
</form>
</body>
</html>