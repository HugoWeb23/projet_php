<?php

function mdp_hash($pass) {
$cout = ['cost' => 8]; // Coût volontairement faible pour réduire le temps de hachage en local
$pass  = password_hash($pass, PASSWORD_DEFAULT, $cout);
return $pass;  
}

function nom_aleatoire() {
$nom = str_shuffle(uniqid());
return $nom;
}

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
?>