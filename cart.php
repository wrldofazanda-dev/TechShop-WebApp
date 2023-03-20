<?php


require_once 'scripts/dbconfig.php';

// check if client is logged in 
if(!isset($_SESSION['logged_in'])){
    header('Location: register.php');
}



$c = new Client; 

// get cart items 
$cart = $c->execPDOQuery('sp_getCartItems', [$_SESSION['user_id']], ['get' => 'all']);
$cartAmountTotal = $c->execPDOQuery('sp_getCartAmountTotal', [$_SESSION['user_id']], ['get' => 'Total']);
@$account_total = $c->execPDOQuery('sp_getCartTotal', [$_SESSION['user_id']], ['get' => 'aCartTotal']) ;
if($account_total == 0){
    header('Location: index.php');
}


// set page title and breadcrumn
$pageTitle = 'View Cart';
$breadcrumb = 'Checkout is one step away!';

// include top and nav
require_once 'includes/_top.php';
require_once 'includes/_nav.php';

?>


<section class="py-5 bg-light">
    <div class="container ">
    
    <!-- display cart details -->
        <?php while($ct = $cart->fetch(PDO::FETCH_ASSOC)) : ?>
            <div class="card w-100" style="width: 18rem;">
                <div class="card-body">
                    <img src="assets/products/<?php echo $ct['prod_img']; ?>" alt="<?php echo $ct['prod_name']; ?>" style="float:right">
                    <h5 class="card-title"><?php echo $ct['prod_name']; ?></h5>
                    
                    <h6 class="card-subtitle mb-2 text-muted">R<?php echo $ct['prod_price']; ?></h6>
                    <p class="card-text">Quantity (1 x <?php echo $ct['prod_qty']; ?>)</p>
                    <a href="scripts/removeCart.php?id=<?php echo $ct['cart_id']; ?>" class="card-link">Remove Item</a>
                </div>
            </div><br>
        <?php endwhile; ?>
    
        <form action="scripts/processCart.php" method="POST">
            
            <button type="submit" class="btn btn-outline-dark btn-lg w-100">Checkout (R<?php echo $cartAmountTotal; ?>)</button>
        </form>
        
    </div>

</section>

<?php // include bottom section
require_once 'includes/_bottom.php';
?>