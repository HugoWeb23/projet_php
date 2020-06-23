<?php

session_start();

require('config.php');
verif_connexion();

if(isset($_GET['id'])) {
$id = $_GET['id'];

$req = $bdd->prepare('SELECT * FROM clients as a LEFT JOIN adresses as b ON a.id_adresse = b.id_adresse WHERE a.id_client = :id');
$req->bindValue('id', $id, PDO::PARAM_INT);
$req->execute();
if($req->rowCount() > 0) {
$afficher = $req->fetch(PDO::FETCH_ASSOC);
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
<h1>Gestion du compte client</h1>
<a href="gestionclients">Gestion des clients</a>
</div>
<div id="messages"></div>
<form id="modifierclient" data-id_client="<?= $id ?>">
<div class="contenu">
<div class="employe-flex">   
<div class="infos-perso">
<h2>Informations personnelles</h2>
<label for="nom">Nom :</label> <input type="text" id="nom" name="nom" value="<?= $afficher['nom'] ?>" placeholder="Saisissez un nom">
<label for="prenom">Prénom :</label> <input type="text" id="prenom" name="prenom" value="<?= $afficher['prenom'] ?>" placeholder="Saisissez un prenom">
<label for="date_naissance">Date de naissance :</label> <input type="date" id="date_naissance" value="<?= $afficher['date_naissance'] ?>" name="date_naissance">
<label for="email">Email :</label> <input type="text" id="email" name="email" data-email_actuelle="<?= $afficher['email'] ?>" value="<?= $afficher['email'] ?>" placeholder="Saisissez une adresse email">
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
<div class="creer-carte">
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
</div>
<?php
} else {
while($carte = $req->fetch()) {
?>
<div class="carte-fidelite">
<div id="resultat"></div>
<p>Numéro : <?= $carte['id_carte']; ?></p>
<p>Points : <span id="count" data-count="<?= $carte['points']; ?>"><?= $carte['points']; ?></span> <a href="#" id="pointsUp">+</a> <a href="#" id="pointsDown">-</a></p>
<p>Date d'expiration : <?= $carte['expire']; ?></p>
<p><label for="prolongerCarte">Prolonger :</label> <select name="mois" id="prolongerCarte"><option value="0">Non</option><option value="1">1 mois</option><option value="2">2 mois</option><option value="3">3 mois</option>
<option value="6">6 mois</option><option value="12">1 an</option><option value="24">2 ans</option></select>
<p><label for="supprimerCarteFidelite">Supprimer :</label> <input type="button" id="supprimerCarteFidelite" data-id_client="<?= $id; ?>" data-id_carte = "<?= $carte['id_carte']; ?>" value="Supprimer">
</div>
<?php
}
}
?>
<input class="boutton-delete" type="button" id="supprimerclient" data-client="<?= $afficher['id_client']; ?>" value="Supprimer le compte" style="width:100%;">
</div>
</div>
<input class="boutton-rouge" type="submit" name="valider" value="Valider les modifications">
</form>
</div>
</div>
</body>
</html>