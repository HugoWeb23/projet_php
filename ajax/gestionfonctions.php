<?php

require('../config.php');

if(isset($_POST['action']) && $_POST['action'] == 'creer_fonction') {
$nom = $_POST['nom'];
if(strlen($nom) == 0) {
$message = array("type" => "erreur", "message" => "Vous devez choisir un nom pour la fonction à créer");
} else {
$req = $bdd->prepare('INSERT INTO fonctions (nom) VALUES (:nom)');
$req->bindValue('nom', $nom, PDO::PARAM_STR);
$req->execute();
$lastID = $bdd->lastInsertId();
$message = array("type" => "succes", "id_fonction" => $lastID);
}
echo json_encode($message);
}

if(isset($_POST['action']) && $_POST['action'] == 'editer_fonction') {
$nom = $_POST['nom'];
$id_fonction = $_POST['id_fonction'];
if(strlen($nom) == 0) {
$message = array("type" => "erreur", "message" => "Vous devez choisir un nom pour la fonction à modifier");
} else {
$req = $bdd->prepare('UPDATE fonctions SET nom = :nom WHERE id_fonction = :id_fonction');
$req->bindValue('nom', $nom, PDO::PARAM_STR);
$req->bindValue('id_fonction', $id_fonction, PDO::PARAM_INT);
$req->execute();
$message = array("type" => "succes");
}
echo json_encode($message);
}

if(isset($_POST['action']) && $_POST['action'] == 'supprimer_fonction') {
$id_fonction = $_POST['id_fonction'];
$req = $bdd->prepare('DELETE FROM fonctions WHERE id_fonction = :id_fonction');
$req->bindValue('id_fonction', $id_fonction, PDO::PARAM_INT);
$req->execute();
$message = array("type" => "succes");
echo json_encode($message);
}

?>