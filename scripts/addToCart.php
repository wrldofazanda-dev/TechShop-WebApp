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
$prod_id = clean($_GET['id']);
$qty = 1;

// add item to cart and redirect
$addtocart = $c->execPDOQuery('pi_addToCart', [$prod_id, $_SESSION['user_id'], $qty], ['get' => 'aMsg']);

header('Location: ../index.php');