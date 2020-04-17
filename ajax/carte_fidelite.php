<?php

session_start();

require('../config.php');

if(isset($_POST['action']) == 'prolonger') {

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
$req->execute() or die(print_r($req->errorInfo(), TRUE));

echo 'La carte a été prolongée jusqu\'au '.$date.'';

}
}