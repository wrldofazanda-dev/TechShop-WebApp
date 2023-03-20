<?php

require_once 'scripts/dbconfig.php';

// check if logged in and redirect
if(isset($_SESSION['logged_in'])){
    header('Location: index.php');
}

// unset error logs
$msg = @$_SESSION['log_msg'];
$mode = @$_SESSION['log_mode'];

$_SESSION['log_msg'] = '';
$_SESSION['log_mode'] = '';

// set page title and breadcrumn
$pageTitle = 'Tech Shop Account';
$breadcrumb = 'Start shopping today!';

// include top and nav
require_once 'includes/_top.php';
require_once 'includes/_nav.php';

?>


<section class="py-5 pl-5 bg-light">

    <div class="container ">
        <h2>Sign Up <span>or <a href="login.php"><h4>Sign in</h4></a></span></h2>
        <form class="form w-100" action="scripts/user.php?action=register"  method="POST">

            <?php if(isset($msg)): ?>
                <div class="alert alert-<?php echo $mode; ?>" role="alert">
                    <?php echo $msg; ?>
                </div>
            <?php endif; ?>

            <div class="form-group">
                <label class="control-label " for="email">Full Name:</label>
                <div class="">
                    <input type="text" class="form-control" id="edtName" placeholder="Enter Full Name" name="edtName">
                </div>
            </div>


            <div class="form-group">
                <label class="control-label " for="email">Email:</label>
                <div class="">
                    <input type="email" class="form-control" id="edtEmail" placeholder="Enter Email Address" name="edtEmail">
                </div>
            </div>

            <div class="form-group">
                <label class="control-label " for="pwd">Password:</label>
                <div class="">          
                    <input type="password" class="form-control" id="edtPwd" placeholder="Enter Password" name="edtPwd">
                </div>
            </div>
           
            <div class="form-group">        
                <div class="col-sm-offset-2 py-3">
                    <button type="submit" class="btn btn-outline-dark btn-lg w-100">Submit</button>
                </div>
            </div>
        </form>
    </div>

</section>

<?php // include bottom section
require_once 'includes/_bottom.php';
?>