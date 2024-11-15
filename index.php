<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Responsive Navbar</title>
    <link rel="stylesheet" href="css/normalize.css">
    <link rel="stylesheet" href="css/style.css"> 
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
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

            <!-- User page icon -->
            <a href="user-page.php" id="user-page" aria-label="User page">
                <i class="fas fa-user"></i>
            </a>

            <!-- Shopping cart icon -->
            <a href="cart.php" id="shopping-cart" aria-label="Shopping cart">
                <i class="fas fa-shopping-cart"></i>
            </a>
        </div>
    </nav>

    <!-- Hamburger menu widget -->
    <div id="hamburger-widget" class="widget">
        <button id="close-hamburger">Close</button>
        <!-- Content for the hamburger menu -->
    </div>

    <!-- Search bar widget -->
    <div id="search-widget" class="widget">
        <button id="close-search">Close</button>
        <form action="" id="search-form">
            <input type="text" id="search-bar" placeholder="What are you looking for?">
        </form>
    </div>

    <script src="js/nav.js"></script>
</body>
</html>
