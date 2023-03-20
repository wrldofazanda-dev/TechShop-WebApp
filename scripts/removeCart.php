<?php

// get database and helper functions
require_once 'dbconfig.php';
require_once 'functions.php';

// check not if logged in and redirect
if(!isset($_SESSION['logged_in'])){
    header('Location: index.php');
}


$c = new Client;

// clean action get variable
$cart_id = clean($_GET['id']);

$addtocart = $c->execPDOQuery('pd_cart', [$_SESSION['user_id'], $cart_id], ['get' => 'aMsg']);

header('Location: ../cart.php');