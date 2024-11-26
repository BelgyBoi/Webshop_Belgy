<nav id="navbar">
    <!-- Hamburger menu button -->
    <a href="#" id="hamburger-menu" aria-label="Open menu">
        <i class="fas fa-bars"></i>
    </a>

    <!-- Logo -->
    <a href="index.php" id="logo">
        <img src="assets/Belgy_logo.png" alt="Belgylogo">
    </a>

    <!-- Right-side icons -->
    <div id="navbar-icons">
        <!-- Search icon -->
        <a href="#" id="search-link" aria-label="Open search">
            <i class="fas fa-search"></i>
        </a>

        <!-- User page dropdown -->
        <div id="user-dropdown">
            <a href="#" id="user-page" aria-label="User page">
                <i class="fas fa-user"></i>
            </a>
            <div id="user-menu">
                <a href="user.php">View Account</a>
                <a href="classes/logout.php">Logout</a> <!-- Updated path to match correct location -->
            </div>
        </div>

        <!-- Shopping cart icon -->
        <a href="cart.php" id="shopping-cart" aria-label="Shopping cart">
            <i class="fas fa-shopping-cart"></i>
        </a>
    </div>
</nav>

<!-- Include widgets.php for search and hamburger widgets -->
<?php include 'widgets.php'; ?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="js/nav.js"></script> 
<script src="js/data.js"></script>
