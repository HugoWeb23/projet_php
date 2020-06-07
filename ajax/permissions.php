<?php

session_start();

require('../config.php');

if(isset($_POST['action']) && $_POST['action'] == 'modif_permissions') {
$id_permission = $_POST['id_permission'];
$type = $_POST['type'];
$valeur = $_POST['valeur'];
switch($type) {
case 1:
$type = 'c_produit';
break;
case 2:
$type = 'g_produits';
break;
case 3:
$type = 'c_menu';
break;
case 4:
$type = 'g_menus';
break;
case 5:
$type = 'g_categ';
break;
case 6:
$type = 'c_employe';
break;
case 7:
$type = 'g_employe';
break;
case 8:
$type = 'g_fonctions';
break;
case 9:
$type = 'g_permissions';
break;
case 10:
$type = 'c_client';
break;
case 11:
$type = 'g_clients';
break;
case 12:
$type = 'c_commande';
break;
case 13:
$type = 'g_commandes';
break;
case 14:
$type = 'g_livraisons';
break;
default:
$type = '0';
}

$req = $bdd->prepare('UPDATE permissions SET '.$type.' = :valeur WHERE id_permission = :id_permission');
$req->bindValue('valeur', $valeur, PDO::PARAM_INT);
$req->bindValue('id_permission', $id_permission, PDO::PARAM_INT);
$req->execute();
$message = array("type" => "succes");

echo json_encode($message);
}

?>