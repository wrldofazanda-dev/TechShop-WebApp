<?php


require_once 'scripts/dbconfig.php';

// check if client is logged in 
if(!isset($_SESSION['logged_in'])){
    header('Location: register.php');
}

$c = new Client; 

// get cart items 
$salehistory = $c->execPDOQuery('sp_getItemsPurchased', [$_SESSION['user_id']], ['get' => 'all']);
@$account_total = $c->execPDOQuery('sp_getCartTotal', [$_SESSION['user_id']], ['get' => 'aCartTotal']) ;


// set page title and breadcrumn
$pageTitle = 'Profile';
$breadcrumb = 'View Purchase History';

// include top and nav
require_once 'includes/_top.php';
require_once 'includes/_nav.php';

?>


<section class="py-5 bg-light">
    <div class="container ">

        <h3>Items Purchased</h3>

        <!-- display items purchased -->
            
        <?php while($ct = $salehistory->fetch(PDO::FETCH_ASSOC)) : ?>
            <div class="card w-100" style="width: 18rem;">
                <div class="card-body">
                    <img src="assets/products/<?php echo $ct['prod_img']; ?>" alt="<?php echo $ct['prod_name']; ?>" style="float:right">
                    <h5 class="card-title"><?php echo $ct['prod_name']; ?></h5>
                    
                    <h6 class="card-subtitle mb-2 text-muted">R<?php echo $ct['prod_price']; ?></h6>
                    <p class="card-text">Quantity (1 x <?php echo $ct['prod_qty']; ?>)</p>
                </div>
            </div><br>
        <?php endwhile; ?>
    
    </div>

</section>

<?php // include bottom section
require_once 'includes/_bottom.php';
?>