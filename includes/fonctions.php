<?php

function mdp_hash($pass) {
    $cout = ['cost' => 12];
    $pass  = password_hash($pass, PASSWORD_DEFAULT, $cout);
    return $pass;  
}

?>