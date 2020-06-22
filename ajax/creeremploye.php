<?php

session_start();

require('../config.php');

if(isset($_POST['action']) && $_POST['action'] == 'creer_employe') {
$nom = isset($_POST['nom']) ? $_POST['nom'] : '';
$prenom = isset($_POST['prenom']) ? $_POST['prenom'] : '';
$date_naissance = isset($_POST['date_naissance']) ? $_POST['date_naissance'] : '';    
$tel_fixe = isset($_POST['tel_fixe']) ? $_POST['tel_fixe'] : '';
$gsm = isset($_POST['gsm']) ? $_POST['gsm'] : '';
$fonction = isset($_POST['fonction']) ? $_POST['fonction'] : '';       
$rue = isset($_POST['rue']) ? $_POST['rue'] : '';    
$numero = isset($_POST['numero']) ? $_POST['numero'] : '';     
$ville = isset($_POST['ville']) ? $_POST['ville'] : '';   
$code_postal = isset($_POST['code_postal']) ? $_POST['code_postal'] : '';    
$pays = isset($_POST['pays']) ? $_POST['pays'] : '';                    
$email = isset($_POST['email']) ? $_POST['email'] : '';       
$password = isset($_POST['password']) ? $_POST['password'] : '';
$confirm_password = isset($_POST['confirm_password']) ? $_POST['confirm_password'] : '';

switch($pays) {
case 1: $pays='Belgique';
break;
case 2: $pays='France';
break;
default: $pays='';
}

$req = $bdd->prepare('SELECT email FROM personnel WHERE email = :email');
$req->bindValue('email', $email, PDO::PARAM_STR);
$req->execute();
$checkemail = $req->rowCount();

if(empty($nom) || empty($prenom) || empty($date_naissance) || empty($fonction) || empty($rue) ||
empty($numero) || empty($ville) || empty($code_postal) || empty($pays) || empty($email) || empty($password) || empty($tel_fixe) && empty($gsm)) {
$type = 1;  
} elseif($checkemail > 0) {
$type = 6;
} elseif(strlen($password) < 5) {
$type = 5;    
} elseif($password != $confirm_password) {
$type = 4;    
} else {
$req = $bdd->prepare('INSERT INTO adresses (rue, numero, ville, code_postal, pays) VALUES (:rue, :numero, :ville, :code_postal, :pays)');
$req->bindValue('rue', $rue, PDO::PARAM_STR);
$req->bindValue('numero', $numero, PDO::PARAM_STR);
$req->bindValue('ville', $ville, PDO::PARAM_STR);
$req->bindValue('code_postal', $code_postal, PDO::PARAM_INT);
$req->bindValue('pays', $pays, PDO::PARAM_STR);
$req->execute() or die(print_r($req->errorInfo(), TRUE));
$id_adresse = $bdd->lastInsertID();
$req = $bdd->prepare('INSERT INTO personnel (nom, prenom, email, pass, date_naissance, tel_fixe, gsm, id_adresse) VALUES (:nom, :prenom, :email, :pass, :date_naissance, :tel_fixe, :gsm, :id_adresse)');
$req->bindValue('nom', $nom, PDO::PARAM_STR);
$req->bindValue('prenom', $prenom, PDO::PARAM_STR);
$req->bindValue('email', $email, PDO::PARAM_STR);
$req->bindValue('pass', mdp_hash($password), PDO::PARAM_STR);
$req->bindValue('date_naissance', $date_naissance, PDO::PARAM_STR);
$req->bindValue('tel_fixe', $tel_fixe, PDO::PARAM_STR);
$req->bindValue('gsm', $gsm, PDO::PARAM_STR);
$req->bindValue('id_adresse', $id_adresse, PDO::PARAM_INT);
$req->execute() or die(print_r($req->errorInfo(), TRUE));
$id_personnel = $bdd->lastInsertID();
foreach($fonction as $fonct) {
$req = $bdd->prepare('INSERT INTO fonctions_personnel (id_fonction, id_personnel) VALUES (:id_fonction, :id_personnel)');
$req->bindValue('id_fonction', $fonct, PDO::PARAM_INT);
$req->bindValue('id_personnel', $id_personnel, PDO::PARAM_INT);
$req->execute() or die(print_r($req->errorInfo(), TRUE));
$type = 3;
}
}
switch($type) {
case 1:
$message = array("type" => "erreur", "message" => "Merci de remplir tous les champs");
break;
case 3:
$message = array("type" => "succes", "message" => "Le profil employé a été créé");
break;
case 4:
$message = array("type" => "erreur", "message" => "Les deux mot de passe ne correspondent pas");
break;
case 5:
$message = array("type" => "erreur", "message" => "Le mot de passe doit au moins contenir 5 caractères");
break;
case 6:
$message = array("type" => "erreur", "message" => "L'adresse e-mail saisie est déjà utilisée");
break;    
}
echo json_encode($message);
}

?>