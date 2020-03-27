<?php

session_start();

require('config.php');

if(isset($_GET['id'])) {
$nom = isset($_POST['nom']) ? $_POST['nom'] : '';
$prenom = isset($_POST['prenom']) ? $_POST['prenom'] : '';
$date_naissance = isset($_POST['date_naissance']) ? $_POST['date_naissance'] : '';    
$telephone = isset($_POST['telephone']) ? $_POST['telephone'] : '';
$fonction = isset($_POST['fonction']) ? $_POST['fonction'] : '';       
$fonction_secondaire = isset($_POST['fonction_secondaire']) ? $_POST['fonction_secondaire'] : '';  
$rue = isset($_POST['rue']) ? $_POST['rue'] : '';    
$numero = isset($_POST['numero']) ? $_POST['numero'] : '';     
$ville = isset($_POST['ville']) ? $_POST['ville'] : '';   
$code_postal = isset($_POST['code_postal']) ? $_POST['code_postal'] : '';    
$pays = isset($_POST['pays']) ? $_POST['pays'] : '';                    
$email = isset($_POST['email']) ? $_POST['email'] : '';       
$password = isset($_POST['password']) ? $_POST['password'] : '';
$confirm_password = isset($_POST['confirm_password']) ? $_POST['confirm_password'] : '';
$id = $_GET['id'];
$req = $bdd->prepare('SELECT * FROM personnel as a LEFT JOIN adresses as b ON a.id_adresse = b.id_adresse WHERE a.id_personnel = :id');
$req->execute(array('id' => $id));
if($req->rowCount() > 0) {
$afficher = $req->fetch(PDO::FETCH_ASSOC);
if(isset($_POST['valider'])) {
switch($pays) {
case 1: $pays='Belgique';
break;
case 2: $pays='France';
break;
default: $pays='';
}
if($nom == $afficher['nom'] && $prenom == $afficher['prenom'] && $date_naissance == $afficher['date_naissance'] && $telephone == $afficher['telephone'] &&
$rue == $afficher['rue'] && $numero == $afficher['numero'] && $ville == $afficher['ville'] && $code_postal == $afficher['code_postal'] && $pays == $afficher['pays'] && $email == $afficher['email']) { 
$message = null;
} elseif(empty($nom) || empty($prenom) || empty($date_naissance) || empty($telephone) || empty($fonction) || empty($rue) ||
empty($numero) || empty($ville) || empty($code_postal) || empty($pays) || empty($email)) {
$message = 1;    
} else {
$req = $bdd->prepare('UPDATE personnel as a LEFT JOIN adresses as b ON a.id_adresse = b.id_adresse SET a.nom = :nom, a.prenom = :prenom, a.email = :email, a.date_naissance = :date_naissance, a.telephone = :telephone,
b.rue = :rue, b.numero = :numero, b.ville = :ville, b.code_postal = :code_postal, b.pays = :pays WHERE a.id_personnel = :id AND b.id_adresse = (SELECT id_adresse FROM personnel WHERE id_personnel = :id)');    
$req->execute(array('nom' => $nom, 'prenom' => $prenom, 'email' => $email, 'date_naissance' => $date_naissance, 'telephone' => $telephone,
'rue' => $rue, 'numero' => $numero, 'ville' => $ville, 'code_postal' => $code_postal, 'pays' => $pays, 'id' => $id, 'id' => $id)) or die(print_r($req->errorInfo(), TRUE));
$message = 2;
}    
if(!empty($password) && !empty($confirm_password)) {
if(strlen($password) < 5) {
$message = 3;   
} elseif($password != $confirm_password) {
$message = 4;
} else {
$req = $bdd->prepare('UPDATE personnel SET pass = :password WHERE id_personnel = :id');
$req->execute(array('password' => mdp_hash($password), 'id' => $id));
$message = 5;
}
}

switch($message) {
case 1:
$message = 'Saissiez toutes les informations personnelles demandées';
break;
case 2:
$message = 'Les informations personnes ont été mises à jour';  
break;
case 3:
$message = 'Le mot de passe doit contenir au moins 5 caractères';
break;  
case 4:
$message = 'Les mots de passe ne sont pas identiques';
break;     
case 5:
$message = 'Le mot de passe a bien été modifié';
break;
}
}
} else {
header('location: gestionemployes');
exit;    
}
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link href="css/styles.css" rel="stylesheet">
<title><?= $nom_site ?></title>
</head>
<body>
<?php include('header.php'); ?>
<div class="conteneur">
<div class="titre-page">
<h1>Modifier un profil employé</h1>
<a href="gestionemployes">Gestion des employés</a>
<?php if(isset($_POST['valider'])) { echo '<h2>'.$message.'</h2>'; } ?>
</div>
<form action="" method="post">
<div class="contenu">
<div class="employe-flex">   
<div class="infos-perso">
<h2>Informations personnelles</h2>
<label for="nom">Nom :</label> <input type="text" id="nom" name="nom" value="<?= $afficher['nom']; ?>">
<label for="prenom">Prenom :</label> <input type="text" id="prenom" name="prenom" value="<?= $afficher['prenom']; ?>">
<label for="date_naissance">Date de naissance :</label> <input type="date" id="date_naissance" name="date_naissance" value="<?= $afficher['date_naissance']; ?>">
<label for="telephone">Numéro de téléphone :</label> <input type="text" id="telephone" name="telephone" value="<?= $afficher['telephone']; ?>">
<label for="rue">Rue :</label> <input type="text" id="rue" name="rue" value="<?= $afficher['rue']; ?>">
<label for="numero">Numéro :</label> <input type="text" id="numero" name="numero" value="<?= $afficher['numero']; ?>">
<label for="ville">Ville :</label> <input type="text" id="ville" name="ville" value="<?= $afficher['ville']; ?>">
<label for="code_postal">Code postal :</label> <input type="text" id="code_postal" name="code_postal" value="<?= $afficher['code_postal']; ?>">
<label for="pays">Pays :</label><select name="pays" id="pays"><option value="1">Belgique</option><option value="2">France</option></select>
</div>
<div class="identifiants">
<h2>Poste</h2>
<label for="fonction">Fonction principale :</label> <select name="fonction" id="fonction">
<?php $req = $bdd->prepare('SELECT id_fonction, nom FROM fonctions ORDER BY id_fonction');
$req->execute();
while($fonction = $req->fetch()) {
?>
<option value="<?= $fonction['id_fonction']; ?>"><?= $fonction['nom']; ?></option>    
<?php } ?>
</select>
<label for="fonction_secondaire">Fonction secondaire :</label> <select name="fonction_secondaire" id="fonction_secondaire">
<option value="0">Aucune</option>    
<?php
$req = $bdd->prepare('SELECT id_fonction, nom FROM fonctions ORDER BY id_fonction');
$req->execute();
while($fonction = $req->fetch()) {
?>
<option value="<?= $fonction['id_fonction']; ?>"><?= $fonction['nom']; ?></option>    
<?php } ?>
</select>
<h2>Identifiants de connexion</h2>
<label for="email">Adresse email :</label> <input type="email" id="email" name="email" value="<?= $afficher['email']; ?>">
<label for="password">Nouveau mot de passe :</label> <input type="password" id="password" name="password" placeholder="Saisissez un nouveau mot de passe">
<label for="confirm_password">Confirmation :</label> <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirmez le mot de passe">
</div>
</div>
<input class="boutton-rouge" type="submit" name="valider" value="Appliquer les modifications">
</form>
</div>
</div>
</body>
</html>