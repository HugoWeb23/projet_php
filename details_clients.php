<?php

session_start();

require('config.php');

if(isset($_GET['id'])) {
$id = $_GET['id'];

// Vérification de l'existence d'une adresse
$req = $bdd->prepare('SELECT id_adresse FROM adresses WHERE id_adresse = (SELECT id_adresse FROM clients WHERE id_client = :id)');
$req->bindValue('id', $id, PDO::PARAM_INT);
$req->execute();
if($req->rowCount() == 0) {
$req = $bdd->prepare('INSERT INTO adresses (rue, numero, ville, code_postal, pays) VALUES (:rue, :numero, :ville, :code_postal, :pays)');
$req->bindValue('rue', '0', PDO::PARAM_INT);
$req->bindValue('numero', '0', PDO::PARAM_INT);
$req->bindValue('ville', '0', PDO::PARAM_INT);
$req->bindValue('code_postal', '0', PDO::PARAM_INT);
$req->bindValue('pays', '0', PDO::PARAM_INT);
$req->execute();
$id_adresse = $bdd->lastInsertId();
$req = $bdd->prepare('UPDATE clients SET id_adresse = :id_adresse WHERE id_client = :id');
$req->bindValue('id_adresse', $id_adresse, PDO::PARAM_INT);
$req->bindValue('id', $id, PDO::PARAM_INT);
$req->execute();
}

$req = $bdd->prepare('SELECT * FROM clients as a LEFT JOIN adresses as b ON a.id_adresse = b.id_adresse WHERE a.id_client = :id');
$req->bindValue('id', $id, PDO::PARAM_INT);
$req->execute();
if($req->rowCount() > 0) {
$afficher = $req->fetch(PDO::FETCH_ASSOC);

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

if(empty($nom) || empty($prenom) || empty($date_naissance) || empty($rue) ||
empty($numero) || empty($ville) || empty($code_postal) || empty($pays) || empty($email) || empty($telephone_fixe) && empty($gsm)) {
$type = 1;  
} else {
    $checkemail = true;    
    if($email != $afficher['email']) {
    $req = $bdd->prepare('SELECT email FROM clients WHERE email = :email');
    $req->bindValue('email', $email, PDO::PARAM_STR);
    $req->execute();
    $checkemail = $req->rowCount();
    if($checkemail > 0) {
    $type = 6;
    $checkemail = false; 
    } else {
    $checkemail = true;
    }    
    }
    if($checkemail == true) {
    $req = $bdd->prepare('UPDATE clients as a LEFT JOIN adresses as b ON a.id_adresse = b.id_adresse SET a.nom = :nom, a.prenom = :prenom, a.email = :email, a.date_naissance = :date_naissance, a.telephone_fixe = :telephone_fixe,
    a.gsm = :gsm, b.rue = :rue, b.numero = :numero, b.ville = :ville, b.code_postal = :code_postal, b.pays = :pays WHERE a.id_client = :id AND b.id_adresse = (SELECT id_adresse FROM clients WHERE id_client = :id)');    
    $req->bindValue('nom', $nom, PDO::PARAM_STR);
    $req->bindValue('prenom', $prenom, PDO::PARAM_STR);
    $req->bindValue('email', $email, PDO::PARAM_STR);
    $req->bindValue('date_naissance', $date_naissance, PDO::PARAM_STR);
    $req->bindValue('telephone_fixe', $telephone_fixe, PDO::PARAM_STR);
    $req->bindValue('gsm', $gsm, PDO::PARAM_STR);
    $req->bindValue('rue', $rue, PDO::PARAM_STR);
    $req->bindValue('numero', $numero, PDO::PARAM_STR);
    $req->bindValue('ville', $ville, PDO::PARAM_STR);
    $req->bindValue('code_postal', $code_postal, PDO::PARAM_INT);
    $req->bindValue('pays', $pays, PDO::PARAM_STR);
    $req->bindValue('id', $id, PDO::PARAM_INT);
    $req->bindValue('id', $id, PDO::PARAM_INT);
    $req->execute() or die(print_r($req->errorInfo(), TRUE));
    $type = 3;
    }
    } 
switch($type) {
case 1:
$message = '<h2 class="message-erreur">Merci de remplir tous les champs</h2>';
break;
case 3:
$message = '<h2 class="message-confirmation">Le compte client a été modifié</h2>';
break;
case 6:
$message = '<h2 class="message-erreur">L\'adresse email saisie est déjà utilisée</h2>';
break;    
}
}
if(isset($_POST['supprimer'])) {
    $req = $bdd->prepare('DELETE a, b, c FROM clients as a LEFT JOIN adresses as b ON a.id_adresse = b.id_adresse LEFT JOIN cartes_fidelite as c ON a.id_client = c.id_client WHERE a.id_client = :id');
    $req->bindValue('id', $id, PDO::PARAM_INT);
    $req->execute() or die(print_r($req->errorInfo(), TRUE));
    header('location: gestionclients');
    }
} else {
    header('location: gestionclients');
}

} else {
    header('location: gestionclients');
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
<script src="js/functions.js"></script>
<script src="js/jquery.js"></script>
<script src="js/jquery-ui/jquery-ui.min.js"></script>
<script src="js/functions.js"></script>
<link href="css/styles.css" rel="stylesheet">
<link rel="stylesheet" href="js/jquery-ui/jquery-ui.min.css">
<title><?= $nom_site ?></title>
</head>
<body>
<?php include('header.php'); ?>
<div class="conteneur">
<div class="titre-page">
<h1>Gestion du compte client</h1>
<a href="gestionclients">Gestion des clients</a>
</div>
<?php if(isset($_POST['valider'])) { echo $message; } ?>
<form action="" method="post">
<div class="contenu">
<div class="employe-flex">   
<div class="infos-perso">
<h2>Informations personnelles</h2>
<label for="nom">Nom :</label> <input type="text" id="nom" name="nom" value="<?= $afficher['nom'] ?>" placeholder="Saisissez un nom">
<label for="prenom">Prénom :</label> <input type="text" id="prenom" name="prenom" value="<?= $afficher['prenom'] ?>" placeholder="Saisissez un prenom">
<label for="date_naissance">Date de naissance :</label> <input type="date" id="date_naissance" value="<?= $afficher['date_naissance'] ?>" name="date_naissance">
<label for="email">Email :</label> <input type="text" id="email" name="email" value="<?= $afficher['email'] ?>" placeholder="Saisissez une adresse email">
<label for="telephone_fixe">Tél. fixe :</label> <input type="text" id="telephone_fixe" name="telephone_fixe" value="<?= $afficher['telephone_fixe'] ?>" placeholder="Saisissez un numéro de fixe">
<label for="gsm">GSM :</label> <input type="text" id="gsm" name="gsm" value="<?= $afficher['gsm'] ?>" placeholder="Saisissez un numéro de GSM">
<label for="rue">Rue :</label> <input type="text" id="rue" name="rue" value="<?= $afficher['rue'] ?>" placeholder="Saisissez un nom de rue">
<label for="numero">Numéro :</label> <input type="text" id="numero" name="numero" value="<?= $afficher['numero'] ?>" placeholder="Saisissez un numéro d'habitation">
<label for="ville">Ville :</label> <input type="text" id="ville" name="ville" value="<?= $afficher['ville'] ?>" placeholder="Saisissez un nom de ville">
<label for="code_postal">Code postal :</label> <input type="text" id="code_postal" name="code_postal" value="<?= $afficher['code_postal'] ?>" placeholder="Saisissez un code postal">
<label for="pays">Pays :</label><select name="pays" id="pays"><option value="Belgique" <?php if($afficher['pays'] == 'Belgique') { echo ' selected'; } ?>>Belgique</option><option value="France" <?php if($afficher['pays'] == 'France') { echo ' selected'; } ?>>France</option></select>
</div>
<div class="identifiants">
<h2>Carte de fidélité</h2>
<?php
$req = $bdd->prepare('SELECT * FROM cartes_fidelite WHERE id_client = :id');
$req->bindValue('id', $id, PDO::PARAM_INT);
$req->execute();
if($req->rowCount() == 0) {
?>
<p>Ce client ne possède pas de carte de fidélité</p>
<h3>Créer une carte :</h3>
<label for="points">Points :</label>
<input type="text" id="points" value="0">
<label for="duree">Durée de validité</label>
<select id="duree">
<option value="1">1 mois</option>
<option value="2">2 mois</option>
<option value="3">3 mois</option>
<option value="4">4 mois</option>
<option value="5">5 mois</option>
<option value="6" selected>6 mois</option>
<option value="12">1 an</option>
<option value="24">2 ans</option>
</select>
<input type="button" id="creerCarte" data-client="<?= $id; ?>" value="Créer la carte">
<?php
} else {
while($carte = $req->fetch()) {
?>
<div class="carte-fidelite">
<div id="resultat"></div>
<p>Numéro : <?= $carte['id_carte']; ?></p>
<p>Points : <span id="count" data-count="<?= $carte['points']; ?>"><?= $carte['points']; ?></span> <a href="#" id="pointsUp">+</a> <a href="#" id="pointsDown">-</a></p>
<p>Date d'expiration : <?= $carte['expire']; ?></p>
<input type="hidden" id="id_client" value="<?= $id; ?>">
<input type="hidden" id="id_carte" value="<?= $carte['id_carte']; ?>">
<p><label for="prolongerCarte">Prolonger :</label> <select name="mois" id="prolongerCarte"><option value="0">Non</option><option value="1">1 mois</option><option value="2">2 mois</option><option value="3">3 mois</option>
<option value="6">6 mois</option><option value="12">1 an</option><option value="24">2 ans</option></select>
<p><label for="supprimerCarteFidelite">Supprimer :</label> <input type="button" id="supprimerCarteFidelite" value="Supprimer">
</div>
<?php
}
}
?>
<input class="boutton-delete" type="submit" name="supprimer" value="Supprimer le compte">
</div>
</div>
<input class="boutton-rouge" type="submit" name="valider" value="Valider les modifications">
</form>
</div>
</div>
</body>
</html>