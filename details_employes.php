<?php

session_start();

require('config.php');
verif_connexion();
$permissions = verif_permissions($personnel['id_personnel'], array('g_employe'));
if($permissions[0] == 0) {
header('location: index');
}

if(isset($_GET['id'])) {
$id = $_GET['id'];
$req = $bdd->prepare('SELECT * FROM personnel as a LEFT JOIN adresses as b ON a.id_adresse = b.id_adresse WHERE a.id_personnel = :id');
$req->bindValue('id', $id, PDO::PARAM_INT);
$req->execute();
if($req->rowCount() > 0) {
$afficher = $req->fetch(PDO::FETCH_ASSOC);
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
header('location: gestionemployess');
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
<h1>Modifier un profil employé</h1>
<a href="gestionemployes">Gestion des employés</a>
</div>
<div id="messages"></div>
<form id="modifieremploye" data-id_personnel="<?= $id; ?>" class="form">
<div class="loader" style="display: none"><img src="images/loader.gif"></div>
<div class="contenu">
<div class="employe-flex">   
<div class="infos-perso">
<h2>Informations personnelles</h2>
<label for="nom">Nom :</label> <input type="text" id="nom" name="nom" value="<?= $afficher['nom']; ?>" required>
<label for="prenom">Prenom :</label> <input type="text" id="prenom" name="prenom" value="<?= $afficher['prenom']; ?>" required>
<label for="date_naissance">Date de naissance :</label> <input type="date" id="date_naissance" name="date_naissance" value="<?= $afficher['date_naissance']; ?>" required>
<label for="tel_fixe">Numéro de téléphone :</label> <input type="text" id="tel_fixe" name="tel_fixe" value="<?= $afficher['tel_fixe']; ?>" required>
<label for="gsm">GSM :</label> <input type="text" id="gsm" name="gsm" value="<?= $afficher['gsm']; ?>" required>
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
<input type="checkbox" id="<?= $fonction['id_fonction']; ?>" class="employe-fonction" value="<?= $fonction['id_fonction']; ?>"<?= $checked; ?>><label for="<?= $fonction['id_fonction']; ?>"><?= $fonction['nom']; ?></label>  
</div>
<?php } ?>
</div>
<h2>Identifiants de connexion</h2>
<label for="email">Adresse email :</label> <input type="email" id="email" data-email="<?= $afficher['email']; ?>" name="email" value="<?= $afficher['email']; ?>" required>
<label for="password">Nouveau mot de passe :</label> <input type="password" id="password" name="password" placeholder="Saisissez un nouveau mot de passe">
<label for="confirm_password">Confirmation :</label> <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirmez le mot de passe">
<input class="boutton-delete" type="button" id="supprimeremploye" data-id_employe="<?= $id; ?>" name="supprimer" value="Supprimer le compte">
</div>
</div>
<input class="boutton-rouge" type="submit" name="valider" value="Appliquer les modifications">
</form>
</div>
</div>
</body>
</html>