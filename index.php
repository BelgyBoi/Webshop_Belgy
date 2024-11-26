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
    
    $productFetcher = new ProductFetcher($conn);
    $products = $productFetcher->getAllProducts();
} else {
    echo 'Connection failed';
    $products = [];
}

error_log('Session Variables: ' . print_r($_SESSION, true));

?><!DOCTYPE html>
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
<div id="indexBody">
    <div class="product_list">
        <?php
        if (!empty($products)) {
            foreach ($products as $product) {
                echo '<div class="product_item">';
                echo '<a class="product_link" href="focus.php?product_id=' . $product['id'] . '">';
                echo '<img class="product_image" src="' . $product['main_image_url'] . '" alt="' . $product['name'] . '">';
                echo '<div class="product_data">';
                echo '<h2 class="product_name">' . $product['name'] . '</h2>';
                echo '<p class="product_price">€' . $product['price'] . '</p>';
                echo '</div>';
                echo '</a>';
                echo '<button class="product_button add-to-cart" data-id="' . $product['id'] . '" data-name="' . $product['name'] . '" data-price="' . $product['price'] . '" data-image="' . $product['main_image_url'] . '">Add to cart</button>';
                echo '</div>';
            }
        } else {
            echo '<p>No products available.</p>';
        }
        ?>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="js/nav.js"></script>
<script src="js/data.js"></script>
<script src="js/index.js"></script>
</body>
</html>
