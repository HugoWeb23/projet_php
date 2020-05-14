<?php

require('includes/db.php');
require('includes/fonctions.php');

/* Nom de l'application */

$nom_site = 'Pizza Royale';

if(isset($_SESSION['id_personnel'])) {

$req = $bdd->prepare('SELECT * FROM personnel WHERE id_personnel = :id_personnel');
$req->execute(array('id_personnel' => $_SESSION['id_personnel']));
$personnel = $req->fetch();
$count = $req->rowCount();
if($count < 1) {
    session_destroy();
    header('location: connexion');
    exit;
}
}

?>