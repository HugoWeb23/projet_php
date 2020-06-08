<?php

// Hachage du mot de passe

function mdp_hash($pass) {
$cout = ['cost' => 8]; // Coût volontairement faible pour réduire le temps de hachage en local
$pass  = password_hash($pass, PASSWORD_DEFAULT, $cout);
return $pass;  
}

// Création d'un nom aléatoire pour l'upload d'une image

function nom_aleatoire() {
$nom = str_shuffle(uniqid());
return $nom;
}

// Calcul du nombre de minutes entre deux dates

function minutes_dates($date_commande = null, $date_actuelle = null) {
if(strlen($date_actuelle) == 0) {
$date_actuelle = time();   
} else {
$date_actuelle = strtotime($date_actuelle);
}
$date_commande = strtotime($date_commande);
$minutes = ceil(abs($date_actuelle - $date_commande) / 60);
return $minutes;
}

// Vérification des permissions

function verif_permissions(int $id_personnel, array $permission = null) {
global $bdd;
$req = $bdd->prepare('SELECT * FROM fonctions_personnel as a INNER JOIN permissions as b ON a.id_fonction = b.id_fonction WHERE a.id_personnel = :id_personnel');
$req->bindValue('id_personnel', $id_personnel, PDO::PARAM_INT);
$req->execute();
$afficher = $req->fetchAll();
$autorisation = array();
foreach($afficher as $infos) {
foreach($permission as $perm) {
if($infos[$perm] == 1) {
$autorisation[] = 1;
} else {
$autorisation[] = 0;
}
}
}
return $autorisation;
}
?>