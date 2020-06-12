<?php

require('../config.php');

if(isset($_POST['action']) && $_POST['action'] == 'creer_client') {
    $nom = isset($_POST['nom']) ? $_POST['nom'] : '';
    $prenom = isset($_POST['prenom']) ? $_POST['prenom'] : '';
    $date_naissance = isset($_POST['date_naissance']) ? $_POST['date_naissance'] : '';   
    $email = isset($_POST['email']) ? $_POST['email'] : '';       
    $telephone_fixe = isset($_POST['telephone_fixe']) ? $_POST['telephone_fixe'] : '';
    $gsm = isset($_POST['gsm']) ? $_POST['gsm'] : '';       
    $rue = isset($_POST['rue']) ? $_POST['rue'] : '';    
    $numero = isset($_POST['numero']) ? $_POST['numero'] : '';     
    $ville = isset($_POST['ville']) ? $_POST['ville'] : '';   
    $code_postal = isset($_POST['code_postal']) ? $_POST['code_postal'] : '';    
    $pays = isset($_POST['pays']) ? $_POST['pays'] : '';
    $creer_carte = isset($_POST['creercarte']) ? $_POST['creercarte'] : '1';             
    $points_carte = isset($_POST['points']) ? $_POST['points'] : '0';  
    $expiration_carte = isset($_POST['expire']) ? $_POST['expire'] : '6';
    
    $year = date('y');
    $day = date('d');
    $month = date('m');
    
    $date_expiration = date("Y-m-d", mktime(0, 0, 0, $month+$expiration_carte, $day, $year));
    
    $req = $bdd->prepare('SELECT email FROM personnel WHERE email = :email');
    $req->bindValue('email', $email, PDO::PARAM_STR);
    $req->execute();
    $checkemail = $req->rowCount();
    
    if(empty($nom) || empty($prenom) || empty($date_naissance) || empty($rue) ||
    empty($numero) || empty($ville) || empty($code_postal) || empty($pays) || empty($email) || empty($telephone_fixe) && empty($gsm)) {
    $type = 1;  
    } elseif($checkemail > 0) {
    $type = 6;   
    } else {
    $req = $bdd->prepare('INSERT INTO adresses (rue, numero, ville, code_postal, pays) VALUES (:rue, :numero, :ville, :code_postal, :pays)');
    $req->bindValue('rue', $rue, PDO::PARAM_STR);
    $req->bindValue('numero', $numero, PDO::PARAM_STR);
    $req->bindValue('ville', $ville, PDO::PARAM_STR);
    $req->bindValue('code_postal', $code_postal, PDO::PARAM_INT);
    $req->bindValue('pays', $pays, PDO::PARAM_STR);
    $req->execute() or die(print_r($req->errorInfo(), TRUE));
    $id_adresse = $bdd->lastInsertID();
    $req = $bdd->prepare('INSERT INTO clients (nom, prenom, email, date_naissance, telephone_fixe, gsm, id_adresse) VALUES (:nom, :prenom, :email, :date_naissance, :telephone_fixe, :gsm, :id_adresse)');
    $req->bindValue('nom', $nom, PDO::PARAM_STR);
    $req->bindValue('prenom', $prenom, PDO::PARAM_STR);
    $req->bindValue('email', $email, PDO::PARAM_STR);
    $req->bindValue('date_naissance', $date_naissance, PDO::PARAM_STR);
    $req->bindValue('telephone_fixe', $telephone_fixe, PDO::PARAM_STR);
    $req->bindValue('gsm', $gsm, PDO::PARAM_STR);
    $req->bindValue('id_adresse', $id_adresse, PDO::PARAM_INT);
    $req->execute() or die(print_r($req->errorInfo(), TRUE));
    $id_client = $bdd->lastInsertID();
    if($creer_carte == 1) {
    $req = $bdd->prepare('INSERT INTO cartes_fidelite (date_creation, points, expire, id_client) VALUES (:date_creation, :points, :expire, :id_client)');
    $req->bindValue('date_creation', date('Y-m-d'), PDO::PARAM_STR);
    $req->bindValue('points', $points_carte, PDO::PARAM_INT);
    $req->bindValue('expire', $date_expiration, PDO::PARAM_STR);
    $req->bindValue('id_client', $id_client, PDO::PARAM_INT);
    $req->execute();
    }
    $type = 3;
    }
    switch($type) {
    case 1:
    $message = array("type" => "erreur", "message" => "Merci de remplir tous les champs");
    break;
    case 3:
    $message = array("type" => "succes", "message" => "Le compte client a été créé");
    break;
    case 6:
    $message = array("type" => "erreur", "message" => "L'adresse e-mail saisie est déjà utilisée");
    break;    
    }
    echo json_encode($message);
    }

?>