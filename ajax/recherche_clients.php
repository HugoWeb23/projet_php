<?php

session_start();

require('../config.php');

if(isset($_POST['query'])) {

$recherche = ($_POST['query']);    

$req = $bdd->prepare('SELECT * FROM clients as a LEFT JOIN adresses as b ON a.id_adresse = b.id_adresse WHERE a.nom LIKE :nom OR a.prenom LIKE :prenom OR
a.email LIKE :email OR a.date_naissance LIKE :date_naissance OR a.telephone_fixe LIKE :telephone_fixe OR gsm LIKE :gsm OR b.rue LIKE :rue OR numero LIKE :numero OR ville LIKE :ville OR code_postal LIKE :code_postal OR pays LIKE :pays');
$req->bindValue('nom', '%'.$recherche.'%', PDO::PARAM_STR);
$req->bindValue('prenom', '%'.$recherche.'%', PDO::PARAM_STR);
$req->bindValue('email', '%'.$recherche.'%', PDO::PARAM_STR);
$req->bindValue('date_naissance', '%'.$recherche.'%', PDO::PARAM_STR);
$req->bindValue('telephone_fixe', '%'.$recherche.'%', PDO::PARAM_STR);
$req->bindValue('gsm', '%'.$recherche.'%', PDO::PARAM_STR);
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
    <table>
    <thead>
<tr>
    <th>Nom</th>      
    <th>Prénom</th>
    <th>Date de naissance</th>
    <th>Téléphone fixe</th>
    <th>GSM</th>        
    <th>Email</th>
    <th>Adresse</th>
    <th>Actions</th>
</tr>
</thead>';
    while($afficher = $req->fetch()) {

        if(strlen($afficher['telephone_fixe']) > 1) {
            $telephone_fixe = $afficher['telephone_fixe'];
            } else {
                $telephone_fixe = '<span style="opacity: 0.5;">(Aucun numéro)</span>';
            }

        if(strlen($afficher['gsm']) > 1) {
            $gsm = $afficher['gsm'];
            } else {
            $gsm = '<span style="opacity: 0.5;">(Aucun numéro)</span>';
            }    

        if(strlen($afficher['rue']) > 1) {
            $adresse = $afficher['rue'].', '.$afficher['numero'].'<br>'.$afficher['code_postal']. ' ' .$afficher['ville'];
            } else {
            $adresse = '<span style="opacity: 0.5;">(Pas d\'adresse)</span>';
            }
    
        echo '
        <tbody>
        <tr><td data-label="Nom">'.$afficher['nom'].'</td>
        <td data-label="Prénom">'.$afficher['prenom'].'</td>
        <td data-label="Naissance">'.$afficher['date_naissance'].'</td>
        <td data-label="Téléphone fixe">'.$telephone_fixe.'</td>
        <td data-label="GSM">'.$gsm.'</td>
        <td data-label="Email">'.$afficher['email'].'</td>
        <td data-label="Adresse">'.$adresse.'</td>
        <td data-label="Actions"><a href="details_clients?id='.$afficher['id_client'].'" class="boutton-bleu">Détails</a></td></tr>
        </tbody>
        ';
            }
}
}
?>
