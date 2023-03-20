<?php

// get database and helper functions
require_once 'dbconfig.php';
require_once 'functions.php';

// check not if logged in and redirect
if(!isset($_SESSION['logged_in'])){
    header('Location: index.php');
}


$c = new Client;


$processCart = $c->execPDOQuery('pi_checkout', [$_SESSION['user_id']], ['get' => 'aMsg']);

header('Location: ../profile.php');