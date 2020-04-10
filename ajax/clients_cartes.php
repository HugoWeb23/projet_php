<?php

session_start();

require('../config.php');

if (isset($_GET['term'])){

$req = $bdd->prepare('SELECT * FROM clients WHERE nom LIKE :nom');
$req->bindValue('nom', '%'.$_GET['term'].'%', PDO::PARAM_STR);
$req->execute();

while($row = $req->fetch()) {
   $return_arr[] = array("id"=>$row['id_client'], "nom"=>$row['nom']);
}

echo json_encode($return_arr[0]);

}


?>