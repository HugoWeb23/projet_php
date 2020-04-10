<?php

session_start();

require('config.php');

if(isset($_GET['id'])) {
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
$id = $_GET['id'];
$req = $bdd->prepare('SELECT * FROM personnel as a LEFT JOIN adresses as b ON a.id_adresse = b.id_adresse WHERE a.id_personnel = :id');
$req->bindValue('id', $id, PDO::PARAM_INT);
$req->execute();
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
if(empty($nom) || empty($prenom) || empty($date_naissance) || empty($telephone) || empty($rue) ||
empty($numero) || empty($ville) || empty($code_postal) || empty($pays) || empty($email)) {
$message_infosperso = 1;    
} else {
$checkemail = true;    
if($email != $afficher['email']) {
$req = $bdd->prepare('SELECT email FROM personnel WHERE email = :email');
$req->bindValue('email', $email, PDO::PARAM_STR);
$req->execute();
$checkemail = $req->rowCount();
if($checkemail > 0) {
$message_infosperso = 6;
$checkemail = false; 
} else {
$checkemail = true;
}    
}
if($checkemail == true) {
if(empty($fonction)) {
$message_infosperso = 3;   
} else {
$req = $bdd->prepare('UPDATE personnel as a LEFT JOIN adresses as b ON a.id_adresse = b.id_adresse SET a.nom = :nom, a.prenom = :prenom, a.email = :email, a.date_naissance = :date_naissance, a.telephone = :telephone,
b.rue = :rue, b.numero = :numero, b.ville = :ville, b.code_postal = :code_postal, b.pays = :pays WHERE a.id_personnel = :id AND b.id_adresse = (SELECT id_adresse FROM personnel WHERE id_personnel = :id)');    
$req->bindValue('nom', $nom, PDO::PARAM_STR);
$req->bindValue('prenom', $prenom, PDO::PARAM_STR);
$req->bindValue('email', $email, PDO::PARAM_STR);
$req->bindValue('date_naissance', $date_naissance, PDO::PARAM_STR);
$req->bindValue('telephone', $telephone, PDO::PARAM_STR);
$req->bindValue('rue', $rue, PDO::PARAM_STR);
$req->bindValue('numero', $numero, PDO::PARAM_STR);
$req->bindValue('ville', $ville, PDO::PARAM_STR);
$req->bindValue('code_postal', $code_postal, PDO::PARAM_INT);
$req->bindValue('pays', $pays, PDO::PARAM_STR);
$req->bindValue('id', $id, PDO::PARAM_INT);
$req->bindValue('id', $id, PDO::PARAM_INT);
$req->execute() or die(print_r($req->errorInfo(), TRUE));

$req = $bdd->prepare('DELETE FROM fonctions_personnel WHERE id_personnel = :id_personnel');
$req->bindValue('id_personnel', $id, PDO::PARAM_INT);
$req->execute();

foreach($fonction as $fonct) {
$req = $bdd->prepare('INSERT INTO fonctions_personnel (id_fonction, id_personnel) VALUES (:id_fonction, :id_personnel)');
$req->bindValue('id_fonction', $fonct, PDO::PARAM_INT);
$req->bindValue('id_personnel', $id, PDO::PARAM_INT);
$req->execute() or die(print_r($req->errorInfo(), TRUE));
}
$message_infosperso = 2;
}
}   
} 
if(!empty($password) && !empty($confirm_password)) {
if(strlen($password) < 5) {
$message_mdp = 1;   
} elseif($password != $confirm_password) {
$message_mdp = 2;
} else {
$req = $bdd->prepare('UPDATE personnel SET pass = :password WHERE id_personnel = :id');
$req->bindValue('password', mdp_hash($password), PDO::PARAM_STR);
$req->bindValue('id', $id, PDO::PARAM_INT);
$req->execute();
$message_mdp = 3;
}
switch($message_mdp) {
case 1:
$message_mdp = '<h2 class="message-erreur">Le mot de passe doit contenir au moins 5 caractères</h2>';
break;  
case 2:
$message_mdp = '<h2 class="message-erreur">Les mots de passe ne sont pas identiques</h2>';
break;     
case 3:
$message_mdp = '<h2 class="message-confirmation">Le mot de passe a bien été modifié</h2>';
break;
}
}

switch($message_infosperso) {
case 1:
$message_infosperso = '<h2 class="message-erreur">Saisissez toutes les informations personnelles demandées</h2>';
break;
case 2:
$message_infosperso = '<h2 class="message-confirmation">Les informations personnelles ont été mises à jour</h2>';  
break;
case 3:
$message_infosperso = '<h2 class="message-erreur">Sélectionnez au minimum une fonction</h2>';
break;
case 6:
$message_infosperso = '<h2 class="message-erreur">L\'adresse email saisie est déjà utilisée</h2>';
break;
}
}
} else {
header('location: gestionemployes');
exit;    
}
} else {
header('location: gestionemployes'); 
}
if(isset($_POST['supprimer'])) {
$req = $bdd->prepare('DELETE a, b FROM personnel as a LEFT JOIN adresses as b ON a.id_adresse = b.id_adresse WHERE a.id_personnel = :id');
$req->bindValue('id', $id, PDO::PARAM_INT);
$req->execute() or die(print_r($req->errorInfo(), TRUE));
header('location: gestionemployes');
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
</div>
<?php if(isset($message_infosperso)) { echo $message_infosperso; } ?>
<?php if(isset($message_mdp)) { echo $message_mdp; } ?>
<form action="" method="post">
<div class="contenu">
<div class="employe-flex">   
<div class="infos-perso">
<h2>Informations personnelles</h2>
<label for="nom">Nom :</label> <input type="text" id="nom" name="nom" value="<?= $afficher['nom']; ?>" required>
<label for="prenom">Prenom :</label> <input type="text" id="prenom" name="prenom" value="<?= $afficher['prenom']; ?>" required>
<label for="date_naissance">Date de naissance :</label> <input type="date" id="date_naissance" name="date_naissance" value="<?= $afficher['date_naissance']; ?>" required>
<label for="telephone">Numéro de téléphone :</label> <input type="text" id="telephone" name="telephone" value="<?= $afficher['telephone']; ?>" required>
<label for="rue">Rue :</label> <input type="text" id="rue" name="rue" value="<?= $afficher['rue']; ?>" required>
<label for="numero">Numéro :</label> <input type="text" id="numero" name="numero" value="<?= $afficher['numero']; ?>" required>
<label for="ville">Ville :</label> <input type="text" id="ville" name="ville" value="<?= $afficher['ville']; ?>" required>
<label for="code_postal">Code postal :</label> <input type="text" id="code_postal" name="code_postal" value="<?= $afficher['code_postal']; ?>" required>
<label for="pays">Pays :</label><select name="pays" id="pays" required><option value="1"<?php if($afficher['pays'] == 'Belgique') { echo ' selected'; } ?>>Belgique</option><option value="2"<?php if($afficher['pays'] == 'France') { echo ' selected'; } ?>>France</option></select>
</div>
<div class="identifiants">
<h2>Gestion des fonctions</h2>
<div class="flex-fonctions">
<?php 
$req = $bdd->prepare('SELECT id_fonction, nom FROM fonctions ORDER BY id_fonction');
$req->execute();
$nb = $req->rowCount();
while($fonction = $req->fetch()) {

$req0 = $bdd->prepare('SELECT id_fonction, id_personnel FROM fonctions_personnel WHERE id_personnel = :id AND id_fonction = :id_fonction ORDER BY id_fonction');
$req0->bindValue('id', $id, PDO::PARAM_INT);
$req0->bindValue('id_fonction', $fonction['id_fonction'], PDO::PARAM_INT);
$req0->execute() or die(print_r($req->errorInfo(), TRUE));
   
if($req0->rowCount() > 0) {
$checked = ' checked';
} else {
$checked = null;
}
?>
<div class="content-fonctions">
<input type="checkbox" name="fonction[]" id="<?= $fonction['id_fonction']; ?>" value="<?= $fonction['id_fonction']; ?>"<?= $checked; ?>><label for="<?= $fonction['id_fonction']; ?>"><?= $fonction['nom']; ?></label>  
</div>
<?php } ?>
</div>
<h2>Identifiants de connexion</h2>
<label for="email">Adresse email :</label> <input type="email" id="email" name="email" value="<?= $afficher['email']; ?>" required>
<label for="password">Nouveau mot de passe :</label> <input type="password" id="password" name="password" placeholder="Saisissez un nouveau mot de passe">
<label for="confirm_password">Confirmation :</label> <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirmez le mot de passe">
<form action="" method="post">
<input class="boutton-delete" type="submit" name="supprimer" value="Supprimer le compte">
</form>
</div>
</div>
<input class="boutton-rouge" type="submit" name="valider" value="Appliquer les modifications">
</form>
</div>
</div>
</body>
</html>