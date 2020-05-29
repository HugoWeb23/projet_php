<?php

session_start();

require('../config.php');

$email = $_POST['email'];

$req = $bdd->prepare('SELECT email FROM personnel WHERE email = :email');
$req->bindValue('email', $email, PDO::PARAM_STR);
$req->execute();
if($req->rowCount() == 0) {
$type = array('dispo' => 1);
} else {
$type = array('dispo' => 0);
}
echo json_encode($type);