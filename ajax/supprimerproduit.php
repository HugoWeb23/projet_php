<?php

require('../config.php');

if(isset($_POST['action']) && $_POST['action'] == 'supprimer_produit') {
$id_produit = isset($_POST['id_produit']) ? $_POST['id_produit'] : '';
$photo = isset($_POST['photo']) ? $_POST['photo'] : '';
if(empty($id_produit)) {
$message = array("type" => "erreur", "message" => "L'ID du produit n'est pas valide");
} else {
$req = $bdd->prepare('DELETE FROM produits WHERE id_produit = :id');
$req->bindValue('id', $id_produit, PDO::PARAM_INT);
$req->execute();
unlink('../'.$photo);
$message = array("type" => "succes", "message" => "Le produit a été supprimé");
}
echo json_encode($message);
}

?>