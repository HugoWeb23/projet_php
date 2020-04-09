<?php

session_start();

require('../config.php');

if(isset($_POST['query'])) {

$recherche = ($_POST['query']);    

$req = $bdd->prepare('SELECT * FROM personnel as a LEFT JOIN adresses as b ON a.id_adresse = b.id_adresse WHERE a.nom LIKE :nom OR a.prenom LIKE :prenom OR
a.email LIKE :email OR a.date_naissance LIKE :date_naissance OR a.telephone LIKE :telephone OR b.rue LIKE :rue OR numero LIKE :numero OR ville LIKE :ville OR code_postal LIKE :code_postal OR pays LIKE :pays');
$req->bindValue('nom', '%'.$recherche.'%', PDO::PARAM_STR);
$req->bindValue('prenom', '%'.$recherche.'%', PDO::PARAM_STR);
$req->bindValue('email', '%'.$recherche.'%', PDO::PARAM_STR);
$req->bindValue('date_naissance', '%'.$recherche.'%', PDO::PARAM_STR);
$req->bindValue('telephone', '%'.$recherche.'%', PDO::PARAM_STR);
$req->bindValue('rue', '%'.$recherche.'%', PDO::PARAM_STR);
$req->bindValue('numero', '%'.$recherche.'%', PDO::PARAM_STR);
$req->bindValue('ville', '%'.$recherche.'%', PDO::PARAM_STR);
$req->bindValue('code_postal', '%'.$recherche.'%', PDO::PARAM_STR);
$req->bindValue('pays', '%'.$recherche.'%', PDO::PARAM_STR);
$req->execute();
if($req->rowCount() < 1) {
    echo 'Aucun résultat';
} else {
    echo '
    <table class="liste-employes">
<tr>
    <th>Nom</th>      
    <th>Prénom</th>
    <th>Date de naissance</th>
    <th>Téléphone</th>    
    <th>Email</th>
    <th>Adresse</th>
    <th>Actions</th>
</tr>';
    while($afficher = $req->fetch()) {

        if(strlen($afficher['rue']) > 1) {
        $condition = $afficher['rue'].', '.$afficher['numero'].'<br>'.$afficher['code_postal']. ' ' .$afficher['ville'];
        } else {
            $condition = '<span style="opacity: 0.5;">(Pas d\'adresse)</span>';
        }
    
        echo '
        <tr><td>'.$afficher['nom'].'</td>
        <td>'.$afficher['prenom'].'</td>
        <td>'.$afficher['date_naissance'].'</td>
        <td>'.$afficher['telephone'].'</td>
        <td>'.$afficher['email'].'</td>
        <td>'.$condition.'</td>
        <td><a href="details_employes?id='.$afficher['id_personnel'].'">Détails</a></td></tr>
        ';
            
            
            }
}
}
?>