<?php

session_start();

require('../config.php');

if(isset($_POST['action']) && $_POST['action'] == 1){

    $recherche = $_POST['recherche'];

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
   $req->execute() or die(print_r($req->errorInfo(), TRUE));

    $reponse = array();
    while($result = $req->fetch()) {
    $reponse[] = array("value"=>$result['id_client'],"label"=>$result['nom'].' '.$result['prenom']. ' - '.$result['rue'].' '.$result['numero'].', '.$result['code_postal']. ' '.$result['ville']. ' '.$result['pays']);
    }
    echo json_encode($reponse);
   }

   if(isset($_POST['action']) && $_POST['action'] == 2){

    $userid = $_POST['userid'];

    $req = $bdd->prepare('SELECT * FROM clients as a LEFT JOIN adresses as b ON a.id_adresse = b.id_adresse WHERE a.id_client = :id_client');
    $req->bindValue('id_client', $userid, PDO::PARAM_INT);
    $req->execute() or die(print_r($req->errorInfo(), TRUE));

    $reponse = array();
    while($result = $req->fetch()) {
    $reponse[] = array("id"=>$result['id_client'], "tel_fixe"=>$result['telephone_fixe'], "gsm"=>$result['gsm'], "email"=>$result['email'], "rue"=>$result['rue'], "numero"=>$result['numero'], "code_postal"=>$result['code_postal'], "ville"=>$result['ville'], "pays"=>$result['pays']);
    }
    echo json_encode($reponse);
   }

   if(isset($_POST['action']) && $_POST['action'] == 3){

    $rue = $_POST['rue'];

    $req = $bdd->prepare('SELECT MIN(id_adresse) as id_adresse, rue, code_postal, ville, pays FROM adresses WHERE rue LIKE :rue GROUP BY rue, code_postal, ville, pays');
    $req->bindValue('rue', '%'.$rue.'%', PDO::PARAM_STR);
    $req->execute() or die(print_r($req->errorInfo(), TRUE));

    $reponse = array();
    while($result = $req->fetch()) {
    $reponse[] = array("value"=>$result['id_adresse']. ','.$result['rue'], "label"=>$result['rue']. ' ' .$result['code_postal']. ' ' .$result['ville']. ' ' .$result['pays']);
    }
    echo json_encode($reponse);
   }

   if(isset($_POST['action']) && $_POST['action'] == 4){

    $id_adresse = $_POST['id_adresse'];

    $req = $bdd->prepare('SELECT * FROM adresses WHERE id_adresse = :id_adresse');
    $req->bindValue('id_adresse', $id_adresse, PDO::PARAM_INT);
    $req->execute() or die(print_r($req->errorInfo(), TRUE));

    $reponse = array();
    while($result = $req->fetch()) {
    $reponse[] = array("rue"=>$result['rue'], "code_postal"=>$result['code_postal'], "ville"=>$result['ville'], "pays"=>$result['pays']);
    }
    echo json_encode($reponse);
   }

   ?>