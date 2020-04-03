<?php

function mdp_hash($pass) {
    $cout = ['cost' => 8]; // Coût volontairement faible pour réduire le temps de hachage en local
    $pass  = password_hash($pass, PASSWORD_DEFAULT, $cout);
    return $pass;  
}

?>