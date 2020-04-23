<?php

session_start();

require('../config.php');

if(isset($_POST['action']) && $_POST['action'] == 'delete_menu') {

$id_menu = $_POST['id_menu'];

$req = $bdd->prepare('DELETE FROM menus WHERE id_menu = :id');
$req->bindValue('id', $id_menu, PDO::PARAM_INT);
$req->execute() or die(print_r($req->errorInfo(), TRUE));

}
?>