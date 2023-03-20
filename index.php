<?php

require_once 'scripts/dbconfig.php';
$c = new Client; 

// get tech products, no params to pass, get all results from procedure
$products = $c->execPDOQuery('sp_getProducts', [], ['get' => 'all']);
@$account_total = $c->execPDOQuery('sp_getCartTotal', [$_SESSION['user_id']], ['get' => 'aCartTotal']) ;


// set page title and breadcrumn
$pageTitle = 'Tech Shop';
$breadcrumb = 'Home';

// include top and nav
require_once 'includes/_top.php';
require_once 'includes/_nav.php';

?>


<section class="py-5 bg-light">
    <div class="container px-4 px-lg-5 mt-5">
        <div class="row gx-4 gx-lg-5 row-cols-2 row-cols-md-3 row-cols-xl-4 justify-content-center">
            
        <!-- display products -->
            <?php while($pd = $products->fetch(PDO::FETCH_ASSOC)) : ?>
                <div class="col mb-5">
                    <div class="card h-100">
                        <!-- Product image-->
                        <img class="card-img-top" src="assets/products/<?php echo $pd['prod_img']; ?>" alt="<?php echo $pd['prod_name']; ?>" />
                        <!-- Product details-->
                        <div class="card-body p-4">
                            <div class="text-center">
                                <!-- Product name-->
                                <h5 class="fw-bolder"><?php echo $pd['prod_name']; ?></h5>
                                <!-- Product price-->
                                R<?php echo $pd['prod_price']; ?>
                            </div>
                        </div>
                        <!-- Product actions-->
                        <div class="card-footer p-4 pt-0 border-top-0 bg-transparent">
                            <?php if(!isset($_SESSION['logged_in'])): ?>
                                <div class="text-center"><a class="btn btn-outline-dark mt-auto" href="login.php">Add to Cart</a></div>
                            <?php else: ?>
                                <div class="text-center"><a class="btn btn-outline-dark mt-auto" href="scripts/addToCart.php?id=<?php echo $pd['prod_id']; ?>">Add to Cart</a></div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
            
        </div>
    </div>
</section>

<?php // include bottom section
require_once 'includes/_bottom.php';
?>