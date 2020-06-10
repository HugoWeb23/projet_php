<?php

session_start();

require('config.php');

if(isset($_POST['valider'])) {
$nom = isset($_POST['nom']) ? $_POST['nom'] : '';
$prenom = isset($_POST['prenom']) ? $_POST['prenom'] : '';
$date_naissance = isset($_POST['date_naissance']) ? $_POST['date_naissance'] : '';   
$email = isset($_POST['email']) ? $_POST['email'] : '';       
$telephone_fixe = isset($_POST['telephone_fixe']) ? $_POST['telephone_fixe'] : '';
$gsm = isset($_POST['gsm']) ? $_POST['gsm'] : '';       
$rue = isset($_POST['rue']) ? $_POST['rue'] : '';    
$numero = isset($_POST['numero']) ? $_POST['numero'] : '';     
$ville = isset($_POST['ville']) ? $_POST['ville'] : '';   
$code_postal = isset($_POST['code_postal']) ? $_POST['code_postal'] : '';    
$pays = isset($_POST['pays']) ? $_POST['pays'] : '';                   
$points_carte = isset($_POST['points']) ? $_POST['points'] : '0';  
$expiration_carte = isset($_POST['expire']) ? $_POST['expire'] : '6';

$year = date('y');
$day = date('d');
$month = date('m');

$date_expiration = date("Y-m-d", mktime(0, 0, 0, $month+$expiration_carte, $day, $year));

$req = $bdd->prepare('SELECT email FROM personnel WHERE email = :email');
$req->bindValue('email', $email, PDO::PARAM_STR);
$req->execute();
$checkemail = $req->rowCount();

if(empty($nom) || empty($prenom) || empty($date_naissance) || empty($rue) ||
empty($numero) || empty($ville) || empty($code_postal) || empty($pays) || empty($email) || empty($telephone_fixe) && empty($gsm)) {
$type = 1;  
} elseif($checkemail > 0) {
$type = 6;   
} else {
$req = $bdd->prepare('INSERT INTO adresses (rue, numero, ville, code_postal, pays) VALUES (:rue, :numero, :ville, :code_postal, :pays)');
$req->bindValue('rue', $rue, PDO::PARAM_STR);
$req->bindValue('numero', $numero, PDO::PARAM_STR);
$req->bindValue('ville', $ville, PDO::PARAM_STR);
$req->bindValue('code_postal', $code_postal, PDO::PARAM_INT);
$req->bindValue('pays', $pays, PDO::PARAM_STR);
$req->execute() or die(print_r($req->errorInfo(), TRUE));
$id_adresse = $bdd->lastInsertID();
$req = $bdd->prepare('INSERT INTO clients (nom, prenom, email, date_naissance, telephone_fixe, gsm, id_adresse) VALUES (:nom, :prenom, :email, :date_naissance, :telephone_fixe, :gsm, :id_adresse)');
$req->bindValue('nom', $nom, PDO::PARAM_STR);
$req->bindValue('prenom', $prenom, PDO::PARAM_STR);
$req->bindValue('email', $email, PDO::PARAM_STR);
$req->bindValue('date_naissance', $date_naissance, PDO::PARAM_STR);
$req->bindValue('telephone_fixe', $telephone_fixe, PDO::PARAM_STR);
$req->bindValue('gsm', $gsm, PDO::PARAM_STR);
$req->bindValue('id_adresse', $id_adresse, PDO::PARAM_INT);
$req->execute() or die(print_r($req->errorInfo(), TRUE));
$id_client = $bdd->lastInsertID();
if(isset($_POST['creercarte'])) {
$req = $bdd->prepare('INSERT INTO cartes_fidelite (date_creation, points, expire, id_client) VALUES (:date_creation, :points, :expire, :id_client)');
$req->bindValue('date_creation', date('Y-m-d'), PDO::PARAM_STR);
$req->bindValue('points', $points_carte, PDO::PARAM_INT);
$req->bindValue('expire', $date_expiration, PDO::PARAM_STR);
$req->bindValue('id_client', $id_client, PDO::PARAM_INT);
$req->execute();
}
$type = 3;
}
switch($type) {
case 1:
$message = '<h2 class="message-erreur">Merci de remplir tous les champs</h2>';
break;
case 3:
$message = '<h2 class="message-confirmation">Le compte client a été créé</h2>';
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
<script>
function toggleSelect()
{
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
<?php if(isset($_POST['valider'])) { echo $message; } ?>
<form action="" method="post">
<div class="contenu">
<div class="employe-flex">   
<div class="infos-perso">
<h2>Informations personnelles</h2>
<label for="nom">Nom :</label> <input type="text" id="nom" name="nom"<?php if(isset($nom) && $type != 3) { echo ' value="'.$nom.'"'; } ?> placeholder="Saisissez un nom">
<label for="prenom">Prénom :</label> <input type="text" id="prenom" name="prenom"<?php if(isset($prenom) && $type != 3) { echo ' value="'.$prenom.'"'; } ?> placeholder="Saisissez un prenom">
<label for="date_naissance">Date de naissance :</label> <input type="date" id="date_naissance"<?php if(isset($date_naissance) && $type != 3) { echo ' value="'.$date_naissance.'"'; } ?> name="date_naissance">
<label for="email">Email :</label> <input type="text" id="email" name="email"<?php if(isset($email) && $type != 3) { echo ' value="'.$email.'"'; } ?> placeholder="Saisissez une adresse email">
<label for="telephone_fixe">Tél. fixe :</label> <input type="text" id="telephone_fixe" name="telephone_fixe"<?php if(isset($telephone_fixe) && $type != 3) { echo ' value="'.$telephone_fixe.'"'; } ?> placeholder="Saisissez un numéro de fixe">
<label for="gsm">GSM :</label> <input type="text" id="gsm" name="gsm"<?php if(isset($gsm) && $type != 3) { echo ' value="'.$gsm.'"'; } ?> placeholder="Saisissez un numéro de GSM">
<label for="rue">Rue :</label> <input type="text" id="rue" name="rue"<?php if(isset($rue) && $type != 3) { echo ' value="'.$rue.'"'; } ?> placeholder="Saisissez un nom de rue">
<label for="numero">Numéro :</label> <input type="text" id="numero" name="numero"<?php if(isset($numero) && $type != 3) { echo ' value="'.$numero.'"'; } ?> placeholder="Saisissez un numéro d'habitation">
<label for="ville">Ville :</label> <input type="text" id="ville" name="ville"<?php if(isset($ville) && $type != 3) { echo ' value="'.$ville.'"'; } ?> placeholder="Saisissez un nom de ville">
<label for="code_postal">Code postal :</label> <input type="text" id="code_postal" name="code_postal"<?php if(isset($code_postal) && $type != 3) { echo ' value="'.$code_postal.'"'; } ?> placeholder="Saisissez un code postal">
<label for="pays">Pays :</label><select name="pays" id="pays"><option value="Belgique"<?php if(isset($pays) && $pays == 'Belgique' && $type != 3) { echo ' selected'; } ?>>Belgique</option><option value="France"<?php if(isset($pays) && $pays == 'France' && $type != 3) { echo ' selected'; } ?>>France</option></select>
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