<?php

session_start();

require('../config.php');

$pseudo = $_POST['pseudo'];

$req = $bdd->prepare('SELECT email FROM clients WHERE email = :email');
$req->bindValue('email', $pseudo, PDO::PARAM_STR);
$req->execute();
if($req->rowCount() == 0) {
echo 'yes';  
} else {
echo 'no';
$ok = false;
}