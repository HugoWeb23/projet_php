<?php 

session_start();

require('includes/config.php'); 

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="css/styles.css" rel="stylesheet">
    <title><?= $nom_site ?></title>
</head>
<body>
<?php include('header.php'); ?>
<br></br>
<?php
if(isset($_POST['envoyer'])) {
    $mdp = $_POST['mdp'];
    $req = $bdd->prepare('UPDATE personnel SET pass = :pass WHERE id_personnel = 1');
    $req->execute(array('pass' => mdp_hash($mdp)));
}

?>
<form action="" method="post">
<input type="text" name="mdp">
<input type="submit" name="envoyer" value="test">
</form>
</body>
</html>