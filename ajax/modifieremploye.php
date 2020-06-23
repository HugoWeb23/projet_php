<?php

require('../config.php');

if(isset($_POST['action']) && $_POST['action'] == 'modifier_employe') {
$id = isset($_POST['id_personnel']) ? $_POST['id_personnel'] : '';
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
$email_actuelle = isset($_POST['email_actuelle']) ? $_POST['email_actuelle'] : '';                
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
if(empty($nom) || empty($prenom) || empty($date_naissance) || empty($rue) ||
empty($numero) || empty($ville) || empty($code_postal) || empty($pays) || empty($email) || empty($telephone) && empty($gsm)) {
$message_infosperso = 1;    
} else {
$checkemail = true;    
if($email != $email_actuelle) {
$req = $bdd->prepare('SELECT email FROM personnel WHERE email = :email');
$req->bindValue('email', $email, PDO::PARAM_STR);
$req->execute();
$checkemail = $req->rowCount();
if($checkemail > 0) {
$message_infosperso = 6;
$checkemail = false; 
} else {
$checkemail = true;
}    
}
if($checkemail == true) {
if(empty($fonction)) {
$message_infosperso = 3;   
} else {
$message_infosperso = 2;
}
}   
}
$message_mdp = 3; 
if(!empty($password) && !empty($confirm_password)) {
if(strlen($password) < 5) {
$message_mdp = 1;   
} elseif($password != $confirm_password) {
$message_mdp = 2;
} else {
$message_mdp = 3;
}
}
if($message_infosperso == 2 && $message_mdp == 3) {
$req = $bdd->prepare('UPDATE personnel as a LEFT JOIN adresses as b ON a.id_adresse = b.id_adresse SET a.nom = :nom, a.prenom = :prenom, a.email = :email, a.date_naissance = :date_naissance, a.tel_fixe = :tel_fixe, a.gsm = :gsm, b.rue = :rue, b.numero = :numero, b.ville = :ville, b.code_postal = :code_postal, b.pays = :pays WHERE a.id_personnel = :id');    
$req->bindValue('nom', $nom, PDO::PARAM_STR);
$req->bindValue('prenom', $prenom, PDO::PARAM_STR);
$req->bindValue('email', $email, PDO::PARAM_STR);
$req->bindValue('date_naissance', $date_naissance, PDO::PARAM_STR);
$req->bindValue('tel_fixe', $tel_fixe, PDO::PARAM_STR);
$req->bindValue('gsm', $gsm, PDO::PARAM_STR);
$req->bindValue('rue', $rue, PDO::PARAM_STR);
$req->bindValue('numero', $numero, PDO::PARAM_STR);
$req->bindValue('ville', $ville, PDO::PARAM_STR);
$req->bindValue('code_postal', $code_postal, PDO::PARAM_INT);
$req->bindValue('pays', $pays, PDO::PARAM_STR);
$req->bindValue('id', $id, PDO::PARAM_INT);
$req->execute() or die(print_r($req->errorInfo(), TRUE));

$req = $bdd->prepare('DELETE FROM fonctions_personnel WHERE id_personnel = :id_personnel');
$req->bindValue('id_personnel', $id, PDO::PARAM_INT);
$req->execute();

foreach($fonction as $fonct) {
$req = $bdd->prepare('INSERT INTO fonctions_personnel (id_fonction, id_personnel) VALUES (:id_fonction, :id_personnel)');
$req->bindValue('id_fonction', $fonct, PDO::PARAM_INT);
$req->bindValue('id_personnel', $id, PDO::PARAM_INT);
$req->execute() or die(print_r($req->errorInfo(), TRUE));
}
$message_infosperso = 4;
if(!empty($password) && !empty($confirm_password)) {
$req = $bdd->prepare('UPDATE personnel SET pass = :password WHERE id_personnel = :id');
$req->bindValue('password', mdp_hash($password), PDO::PARAM_STR);
$req->bindValue('id', $id, PDO::PARAM_INT);
$req->execute();
}
}
switch($message_infosperso) {
case 1:
$message = array("type" => "erreur", "message" => "Saisissez toutes les informations personnelles demandées");
break;
case 4:
$message = array("type" => "succes", "message" => "Le compte client a été mis à jour");
break;
case 3:
$message = array("type" => "erreur", "message" => "Vous devez au moins sélectionner une fonction");
break;
case 6:
$message = array("type" => "erreur", "message" => "L'adresse e-mail saisie est déjà utilisée");
break;
}

switch($message_mdp) {
case 1:
$message = array("type" => "erreur", "message" => "Le mot de passe doit au minimum contenir 5 caractères");
break;  
case 2:
$message = array("type" => "erreur", "message" => "Les mots de passe ne sont pas identiques");
break; 
}
echo json_encode($message);
}

if(isset($_POST['action']) && $_POST['action'] == 'supprimer_employe') {
$id_employe = isset($_POST['id_employe']) ? $_POST['id_employe'] : '';
if(empty($id_employe)) {
$message = array("type" => "erreur", "message" => "L'ID de l'employé n'est pas valide");
} else {
$req = $bdd->prepare('DELETE FROM personnel WHERE id_personnel = :id_personnel');
$req->bindValue('id_personnel', $id_employe, PDO::PARAM_INT);
$req->execute();
$message = array("type" => "succes", "message" => "Le compte employé a été supprimé");
}
echo json_encode($message);
}

?>