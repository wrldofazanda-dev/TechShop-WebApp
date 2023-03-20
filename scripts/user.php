<?php

// get database and helper functions
require_once 'dbconfig.php';
require_once 'functions.php';

$c = new Client;

// clean action get variable
$action = clean($_GET['action']);

// check if login or register
if($action == 'register'){

    // clean register post vars
    $username = clean($_POST['edtName']);
    $email = clean($_POST['edtEmail']);
    $pwd = clean($_POST['edtPwd']);

    // check is register is successful
    $register = $c->execPDOQuery('sp_registerUser', [$email, $pwd, $username], ['get' => 'aMsg']);

    if($register == 'valid'){
        $_SESSION['log_msg'] = 'Account creation was successful! Please fill in the below to login';
        $_SESSION['log_mode'] = 'success';
        header('Location: ../login.php');
    }else{
        $_SESSION['log_msg'] = 'Could not create account at this time. Please try again.';
        header('Location: ../register.php');
    }

}else if($action == 'login'){

    // clean login post vars
    $email = clean($_POST['edtEmail']);
    $pwd = clean($_POST['edtPwd']);

    // check if login is sucessful
    $login = $c->execPDOQuery('sp_loginUser', [$email, $pwd], ['get' => 'aMsg']);

    if($login == 'valid'){
        $_SESSION['logged_in'] = 1;
        $_SESSION['user_id'] = $c->execPDOQuery('sp_getUserIdByEmail', [$email], ['get' => 'aUserId']);

        header('Location: ../index.php');
    }else{
        $_SESSION['log_msg'] = 'Please ensure email and password are correct!';
        $_SESSION['log_mode'] = 'danger';
        header('Location: ../login.php');
    }


}