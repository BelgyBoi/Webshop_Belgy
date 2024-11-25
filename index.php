<?php

session_start();

require 'vendor/autoload.php';

use WebshopBelgy\Database;
use WebshopBelgy\CategoryFetcher;
use WebshopBelgy\ProductFetcher;

$conn = Database::getConnection();
$products = [];

if ($conn) {
    $fetcher = new CategoryFetcher($conn);
    $categories = $fetcher->getCategories();
    
    // Fetch all products - you can change this to fetch by category if needed
    $productFetcher = new ProductFetcher($conn);
    $products = $productFetcher->getAllProducts();
} else {
    echo 'Connection failed';
    $products = [];
}

error_log('Session Variables: ' . print_r($_SESSION, true));


?>
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

<?php 
include_once("classes/nav.php"); 
include_once("classes/widgets.php");
?>

<div class="product_list">
    <?php
        if (!empty($products)) {
            foreach ($products as $product) {
                echo '<a class="product_item" href="focus.php?product_id=' . $product['id'] . '">'; // Wrap the whole item with <a>
                echo '<img class="product_image" src="' . $product['main_image_url'] . '" alt="' . $product['name'] . '">';
                echo '<h2 class="product_name product_data">' . $product['name'] . '</h2>';
                echo '<p class="product_price product_data">€' . $product['price'] . '</p>';
                echo '<div';
                echo '<button class="product_button">Add to cart</button>';
                echo '</div>';
                echo '</a>'; // Close the <a> tag
            }
        } else {
            echo '<p>No products available.</p>';
        }
    ?>
</div>


</div>

<script src="js/nav.js"></script>
<script src="js/data.js"></script>
</body>
</html>
