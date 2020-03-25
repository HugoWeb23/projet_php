<?php

try
{
	$bdd = new PDO('mysql:host=localhost;dbname=pizzeria;charset=utf8', 'root', '');
}
catch (Exception $e)
{
       echo 'Impossible de se connecter à la base de données';
}

$nom_site = 'Pizza Royale';

require('includes/fonctions.php');


?>