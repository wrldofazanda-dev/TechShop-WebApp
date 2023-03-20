<nav class="navbar navbar-expand-lg navbar-light bg-white">
    <div class="container px-4 px-lg-5">
        <a class="navbar-brand" href="index.php">
            <img src="assets/logo.png" alt="Tech Shop Logo">
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-lg-4">

                <li class="nav-item"><a class="nav-link active" aria-current="page" href="index.php">Home</a></li>
                
                <!-- show profile or sign in if user logged in -->
                <?php if(isset($_SESSION['logged_in'])) : ?>

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">Profile</a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="profile.php">Profile</a></li>
                            <li><hr class="dropdown-divider" /></li>
                            <li><a class="dropdown-item" href="scripts/signOut.php">Sign Out</a></li>
                        </ul>
                    </li>

                <?php else: ?>
                    <li class="nav-item"><a class="nav-link" href="register.php">Sign In</a></li>
                <?php endif; ?>

            </ul>

            <form class="d-flex" action="cart.php" method="POST">
                <button class="btn btn-outline-dark" type="submit">
                    <i class="bi-cart-fill me-1"></i>
                    Cart
                    <span class="badge bg-dark text-white ms-1 rounded-pill">
                        <?php echo @$account_total; ?>
                    </span>
                </button>
            </form>
        </div>
    </div>
</nav>