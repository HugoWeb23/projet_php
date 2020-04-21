<?php

session_start();

require('../config.php');

if(isset($_POST['action']) && $_POST['action'] == 'prolonger') {

$mois = $_POST['mois'];
$id_carte = $_POST['id_carte'];
$id_client = $_POST['id_client'];

$year = date('y');
$day = date('d');
$month = date('m');

$date = date("Y-m-d", mktime(0, 0, 0, $month+$mois, $day, $year));
$mois_valides = array(1, 2, 3, 6, 12, 24);

$req = $bdd->prepare('SELECT id_carte FROM cartes_fidelite WHERE id_carte = :id_carte AND id_client = :id_client');
$req->bindValue('id_carte', $id_carte, PDO::PARAM_INT);
$req->bindValue('id_client', $id_client, PDO::PARAM_INT);
$req->execute();

if(!preg_match('`^[0-9]+$`', $mois)) {
echo 'La date est invalide';
} elseif($req->rowCount() == 0) {
echo 'Le numéro de carte est invalide';
} elseif(!in_array($mois, $mois_valides)) {
echo 'Cette date n\'est pas autorisée';
} else {

$req = $bdd->prepare('UPDATE cartes_fidelite SET expire = :date WHERE id_client = :id_client');
$req->bindValue('date', $date, PDO::PARAM_STR);
$req->bindValue('id_client', $id_client, PDO::PARAM_INT);
$req->execute();

echo 'La carte a été prolongée jusqu\'au '.$date.'';
}
}

if(isset($_POST['action']) && $_POST['action'] == 'supprimer') {

$id_carte = $_POST['id_carte'];
$id_client = $_POST['id_client'];

$req = $bdd->prepare('SELECT id_carte FROM cartes_fidelite WHERE id_client = :id_client AND id_carte = :id_carte');
$req->bindValue('id_client', $id_client, PDO::PARAM_INT);
$req->bindValue('id_carte', $id_carte, PDO::PARAM_INT);
$req->execute();
if($req->rowCount() == 0) {
echo 'Cette carte n\'est pas associée à ce client';
} else {
$req = $bdd->prepare('DELETE FROM cartes_fidelite WHERE id_carte = :id_carte');
$req->bindValue('id_carte', $id_carte, PDO::PARAM_INT);
$req->execute();
}
}

if(isset($_POST['action']) && $_POST['action'] == 'points') {

$points = $_POST['points'];
$id_client = $_POST['id_client'];
$id_carte = $_POST['id_carte'];

$req = $bdd->prepare('UPDATE cartes_fidelite SET points = :points WHERE id_client = :id_client AND id_carte = :id_carte');
$req->bindValue('points', $points, PDO::PARAM_INT);
$req->bindValue('id_client', $id_client, PDO::PARAM_INT);
$req->bindValue('id_carte', $id_carte, PDO::PARAM_INT);
$req->execute();
}

if(isset($_POST['action']) && $_POST['action'] == 'creer') {

$points = $_POST['points'];
$id_client = $_POST['id_client'];
$duree = $_POST['duree'];

$year = date('y');
$day = date('d');
$month = date('m');

$date = date("Y-m-d", mktime(0, 0, 0, $month+$duree, $day, $year));
$mois_valides = array(1, 2, 3, 6, 12, 24);

$req = $bdd->prepare('INSERT INTO cartes_fidelite (date_creation, points, expire, id_client) VALUES (:date_creation, :points, :expire, :id_client)');
$req->bindValue('date_creation', date("Y-m-d"), PDO::PARAM_STR);
$req->bindValue('points', $points, PDO::PARAM_INT);
$req->bindValue('expire', $date, PDO::PARAM_STR);
$req->bindValue('id_client', $id_client, PDO::PARAM_INT);
$req->execute();
}