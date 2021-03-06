<?php

require('../config.php');

if(isset($_POST['action']) && $_POST['action'] == 'modifier_client') {
    $id = isset($_POST['id_client']) ? $_POST['id_client'] : '0';
    $nom = isset($_POST['nom']) ? $_POST['nom'] : '';
    $prenom = isset($_POST['prenom']) ? $_POST['prenom'] : '';
    $date_naissance = isset($_POST['date_naissance']) ? $_POST['date_naissance'] : '';   
    $email_actuelle = isset($_POST['email_actuelle']) ? $_POST['email_actuelle'] : '';
    $email = isset($_POST['email']) ? $_POST['email'] : '';       
    $telephone_fixe = isset($_POST['telephone_fixe']) ? $_POST['telephone_fixe'] : '';
    $gsm = isset($_POST['gsm']) ? $_POST['gsm'] : '';       
    $rue = isset($_POST['rue']) ? $_POST['rue'] : '';    
    $numero = isset($_POST['numero']) ? $_POST['numero'] : '';     
    $ville = isset($_POST['ville']) ? $_POST['ville'] : '';   
    $code_postal = isset($_POST['code_postal']) ? $_POST['code_postal'] : '';    
    $pays = isset($_POST['pays']) ? $_POST['pays'] : '';                     
    
    if(empty($nom) || empty($prenom) || empty($date_naissance) || empty($rue) ||
    empty($numero) || empty($ville) || empty($code_postal) || empty($pays) || empty($email) || empty($telephone_fixe) && empty($gsm)) {
    $type = 1;  
    } else {
        $checkemail = true;
        if($email_actuelle != $email) {
            $req = $bdd->prepare('SELECT email FROM clients WHERE email = :email');
            $req->bindValue('email', $email, PDO::PARAM_STR);
            $req->execute();
            $checkemail = $req->rowCount();
            if($checkemail > 0) {
            $type = 6;
            $checkemail = false; 
            } else {
            $checkemail = true;
            } 
        }    
        if($checkemail == true) {
        $req = $bdd->prepare('SELECT * FROM adresses WHERE rue = :rue AND numero = :numero AND ville = :ville AND code_postal = :code_postal AND pays = :pays');
        $req->bindValue('rue', $rue, PDO::PARAM_STR);
        $req->bindValue('numero', $numero, PDO::PARAM_STR);
        $req->bindValue('ville', $ville, PDO::PARAM_STR);
        $req->bindValue('code_postal', $code_postal, PDO::PARAM_STR);
        $req->bindValue('pays', $pays, PDO::PARAM_STR);
        $req->execute() or die(print_r($req->errorInfo(), TRUE));
        $adresse = $req->fetch();
        if($req->rowCount() > 0) {
        $id_adresse = $adresse['id_adresse'];
        } elseif($req->rowCount() == 0) {
        $req = $bdd->prepare('INSERT INTO adresses (rue, numero, ville, code_postal, pays) VALUES (:rue, :numero, :ville, :code_postal, :pays)');
        $req->bindValue('rue', $rue, PDO::PARAM_STR);
        $req->bindValue('numero', $numero, PDO::PARAM_STR);
        $req->bindValue('ville', $ville, PDO::PARAM_STR);
        $req->bindValue('code_postal', $code_postal, PDO::PARAM_STR);
        $req->bindValue('pays', $pays, PDO::PARAM_STR);
        $req->execute() or die(print_r($req->errorInfo(), TRUE));
        $id_adresse = $bdd->lastInsertId();
        }
        $req = $bdd->prepare('UPDATE clients SET nom = :nom, prenom = :prenom, email = :email, date_naissance = :date_naissance, telephone_fixe = :telephone_fixe,
        gsm = :gsm, id_adresse = :id_adresse WHERE id_client = :id');    
        $req->bindValue('nom', $nom, PDO::PARAM_STR);
        $req->bindValue('prenom', $prenom, PDO::PARAM_STR);
        $req->bindValue('email', $email, PDO::PARAM_STR);
        $req->bindValue('date_naissance', $date_naissance, PDO::PARAM_STR);
        $req->bindValue('telephone_fixe', $telephone_fixe, PDO::PARAM_STR);
        $req->bindValue('gsm', $gsm, PDO::PARAM_STR);
        $req->bindValue('id_adresse', $id_adresse, PDO::PARAM_INT);
        $req->bindValue('id', $id, PDO::PARAM_INT);
        $req->execute() or die(print_r($req->errorInfo(), TRUE));
        $type = 3;
        }
        } 
    switch($type) {
    case 1:
    $message = array("type" => "erreur", "message" => "Merci de remplir tous les champs");
    break;
    case 3:
    $message = array("type" => "succes", "message" => "Le compte client a été modifié");
    break;
    case 6:
    $message = array("type" => "erreur", "message" => "L'adresse e-mail saisie est déjà utilisée");
    break;    
    }
    echo json_encode($message);
    }

    if(isset($_POST['action']) && $_POST['action'] == 'supprimer_client') {
    $id_client = $_POST['id_client'];
    $req = $bdd->prepare('DELETE FROM clients WHERE id_client = :id');
    $req->bindValue('id', $id_client, PDO::PARAM_INT);
    $req->execute() or die(print_r($req->errorInfo(), TRUE));
    $message = array("type" => "succes", "message" => "Le compte client a été supprimé");
    echo json_encode($message);
    }

?>