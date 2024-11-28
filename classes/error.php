<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Checkout Error</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <?php include_once("nav.php"); ?>

    <div class="container">
        <h1>Checkout Error</h1>
        <p>There was an error processing your purchase. Please ensure you have enough Belgy Coins (BC) and try again.</p>
        <a href="cart.php">Back to Cart</a>
    </div>
</body>
</html>
