<?php

session_start();

require('config.php');

if(isset($_POST['valider'])) {
$nom = isset($_POST['nom']) ? $_POST['nom'] : '';
$prenom = isset($_POST['prenom']) ? $_POST['prenom'] : '';
$date_naissance = isset($_POST['date_naissance']) ? $_POST['date_naissance'] : '';    
$telephone = isset($_POST['telephone']) ? $_POST['telephone'] : '';
$fonction = isset($_POST['fonction']) ? $_POST['fonction'] : '';       
$rue = isset($_POST['rue']) ? $_POST['rue'] : '';    
$numero = isset($_POST['numero']) ? $_POST['numero'] : '';     
$ville = isset($_POST['ville']) ? $_POST['ville'] : '';   
$code_postal = isset($_POST['code_postal']) ? $_POST['code_postal'] : '';    
$pays = isset($_POST['pays']) ? $_POST['pays'] : '';                    
$email = isset($_POST['email']) ? $_POST['email'] : '';       
$password = isset($_POST['password']) ? $_POST['password'] : '';
$confirm_password = isset($_POST['confirm_password']) ? $_POST['confirm_password'] : '';

switch($pays) {
case 1: $pays='Belgique';
break;
case 2: $pays='France';
break;
default: $pays='';
}

$req = $bdd->prepare('SELECT email FROM personnel WHERE email = :email');
$req->bindValue('email', $email, PDO::PARAM_STR);
$req->execute();
$checkemail = $req->rowCount();

if(empty($nom) || empty($prenom) || empty($date_naissance) || empty($telephone) || empty($fonction) || empty($rue) ||
empty($numero) || empty($ville) || empty($code_postal) || empty($pays) || empty($email) || empty($password)) {
$type = 1;  
} elseif($checkemail > 0) {
$type = 6;
} elseif(strlen($password) < 5) {
$type = 5;    
} elseif($password != $confirm_password) {
$type = 4;    
} else {
$req = $bdd->prepare('INSERT INTO adresses (rue, numero, ville, code_postal, pays) VALUES (:rue, :numero, :ville, :code_postal, :pays)');
$req->bindValue('rue', $rue, PDO::PARAM_STR);
$req->bindValue('numero', $numero, PDO::PARAM_STR);
$req->bindValue('ville', $ville, PDO::PARAM_STR);
$req->bindValue('code_postal', $code_postal, PDO::PARAM_INT);
$req->bindValue('pays', $pays, PDO::PARAM_STR);
$req->execute() or die(print_r($req->errorInfo(), TRUE));
$id_adresse = $bdd->lastInsertID();
$req = $bdd->prepare('INSERT INTO personnel (nom, prenom, email, pass, date_naissance, telephone, id_adresse) VALUES (:nom, :prenom, :email, :pass, :date_naissance, :telephone, :id_adresse)');
$req->bindValue('nom', $nom, PDO::PARAM_STR);
$req->bindValue('prenom', $prenom, PDO::PARAM_STR);
$req->bindValue('email', $email, PDO::PARAM_STR);
$req->bindValue('pass', mdp_hash($password), PDO::PARAM_STR);
$req->bindValue('date_naissance', $date_naissance, PDO::PARAM_STR);
$req->bindValue('telephone', $telephone, PDO::PARAM_STR);
$req->bindValue('id_adresse', $id_adresse, PDO::PARAM_INT);
$req->execute() or die(print_r($req->errorInfo(), TRUE));
$id_personnel = $bdd->lastInsertID();
foreach($fonction as $fonct) {
$req = $bdd->prepare('INSERT INTO fonctions_personnel (id_fonction, id_personnel) VALUES (:id_fonction, :id_personnel)');
$req->bindValue('id_fonction', $fonct, PDO::PARAM_INT);
$req->bindValue('id_personnel', $id_personnel, PDO::PARAM_INT);
$req->execute() or die(print_r($req->errorInfo(), TRUE));
$type = 3;
}
}
switch($type) {
case 1:
$message = '<h2 class="message-erreur">Merci de remplir tous les champs</h2>';
break;
case 3:
$message = '<h2 class="message-confirmation">Le profil employé a été créé</h2>';
break;
case 4:
$message = '<h2 class="message-erreur">Les deux mots de passes ne correspondent pas</h2>';
break;
case 5:
$message = '<h2 class="message-erreur">Le mot de passe doit au moins contenir 5 caractères</h2>';
break;
case 6:
$message = '<h2 class="message-erreur">L\'adresse email saisie est déjà utilisée</h2>';
break;    
}
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
<?php if(isset($_POST['valider'])) { echo $message; } ?>
<form action="" method="post">
<div class="contenu">
<div class="employe-flex">   
<div class="infos-perso">
<h2>Informations personnelles</h2>
<label for="nom">Nom :</label> <input type="text" id="nom" name="nom"<?php if(isset($nom) && $type != 3) { echo ' value="'.$nom.'"'; } ?> placeholder="Saisissez un nom">
<label for="prenom">Prénom :</label> <input type="text" id="prenom" name="prenom"<?php if(isset($prenom) && $type != 3) { echo ' value="'.$prenom.'"'; } ?> placeholder="Saisissez un prenom">
<label for="date_naissance">Date de naissance :</label> <input type="date" id="date_naissance" name="date_naissance"<?php if(isset($date_naissance) && $type != 3) { echo ' value="'.$date_naissance.'"'; } ?>>
<label for="telephone">Numéro de téléphone :</label> <input type="text" id="telephone" name="telephone"<?php if(isset($telephone) && $type != 3) { echo ' value="'.$telephone.'"'; } ?> placeholder="Saisissez un numéro de téléphone">
<label for="rue">Rue :</label> <input type="text" id="rue" name="rue"<?php if(isset($rue) && $type != 3) { echo ' value="'.$rue.'"'; } ?> placeholder="Saisissez un nom de rue">
<label for="numero">Numéro :</label> <input type="text" id="numero" name="numero"<?php if(isset($numero) && $type != 3) { echo ' value="'.$numero.'"'; } ?> placeholder="Saisissez un numéro d'habitation">
<label for="ville">Ville :</label> <input type="text" id="ville" name="ville"<?php if(isset($ville) && $type != 3) { echo ' value="'.$ville.'"'; } ?> placeholder="Saisissez un nom de ville">
<label for="code_postal">Code postal :</label> <input type="text" id="code_postal" name="code_postal"<?php if(isset($code_postal) && $type != 3) { echo ' value="'.$code_postal.'"'; } ?> placeholder="Saisissez un code postal">
<label for="pays">Pays :</label><select name="pays" id="pays"><option value="1"<?php if(isset($pays) && $pays == 'Belgique' && $type != 3) echo ' selected'; ?>>Belgique</option><option value="2"<?php if(isset($pays) && $pays == 'France' && $type != 3) echo ' selected'; ?>>France</option></select>
</div>
<div class="identifiants">
<h2>Attribuer des fonctions</h2>
<div class="flex-fonctions">
<?php $req = $bdd->prepare('SELECT id_fonction, nom FROM fonctions ORDER BY id_fonction');
$req->execute();
while($fonctions = $req->fetch()) {
?>
<div class="content-fonctions">
<input type="checkbox" name="fonction[]" id="<?= $fonctions['id_fonction']; ?>" value="<?= $fonctions['id_fonction']; ?>"><label for="<?= $fonctions['id_fonction']; ?>"><?= $fonctions['nom']; ?></label>  
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