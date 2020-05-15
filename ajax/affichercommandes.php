<?php

session_start();

require('../config.php');

if(isset($_POST['action']) && $_POST['action'] == 'afficher_commandes') {

$req = $bdd->prepare('SELECT * FROM commandes WHERE etat = 0 ORDER BY id_commande');
$req->execute();
if($req->rowCount() < 1) {
    echo 'Aucun résultat';
} else {
    echo '
    <table class="liste-employes">
<tr>
    <th>#</th>      
    <th>Date</th>
    <th>Actions</th>
</tr>';
    while($afficher = $req->fetch()) {
    
        echo '
        <tr><td>'.$afficher['id_commande'].'</td>
        <td>'.$afficher['date'].'</td>
        <td><a href="#" class="cloturer_commande" data-id_commande="'.$afficher['id_commande'].'">Détails</a></td></tr>
        ';
            }
}
}

if(isset($_POST['action']) && $_POST['action'] == 'cloturer_commande') {
$id_commande = $_POST['id_commande'];
$req = $bdd->prepare('UPDATE commandes SET etat = 1 WHERE id_commande = :id_commande');
$req->bindValue('id_commande', $id_commande, PDO::PARAM_INT);
$req->execute() or die(print_r($req->errorInfo(), TRUE));
}

?>