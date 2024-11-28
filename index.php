<?php
session_start();

require 'vendor/autoload.php';

use WebshopBelgy\Database;
use WebshopBelgy\CategoryFetcher;
use WebshopBelgy\ProductFetcher;

$conn = Database::getConnection();
$products = [];
$categories = [];

if ($conn) {
    // Fetch categories
    $fetcher = new CategoryFetcher($conn);
    $categories = $fetcher->getCategories();
    
    // Fetch products
    $productFetcher = new ProductFetcher($conn);
    $products = $productFetcher->getAllProducts();
} else {
    echo 'Connection failed';
    $products = [];
    $categories = [];
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Webshop</title>
    <link rel="stylesheet" href="css/normalize.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>

<?php include_once("classes/nav.php"); ?>
<?php include_once("classes/widgets.php"); ?>

<div id="indexBody">
    <div class="centered-container">

    
    <!-- All Products -->
    <div id="all-products" class="products-list">
        <?php if (!empty($products)) {
            foreach ($products as $product) {
                echo '<div class="product_item" data-category-id="' . $product['category_id'] . '">';
                echo '<a class="product_link" href="focus.php?product_id=' . $product['id'] . '">';
                echo '<img class="product_image" src="' . $product['main_image_url'] . '" alt="' . $product['name'] . '">';
                echo '<div class="product_data">';
                echo '<h2 class="product_name">' . $product['name'] . '</h2>';
                echo '<p class="product_price">BC' . $product['price'] . '</p>';
                echo '</div>';
                echo '</a>';
                echo '<button class="product_button add-to-cart" data-product-id="' . $product['id'] . '" data-name="' . $product['name'] . '" data-price="' . $product['price'] . '" data-image="' . $product['main_image_url'] . '">Add to cart</button>';
                echo '</div>';
            }
        } else {
            echo '<p>No products available.</p>';
        } ?>
    </div>
    <div id="filtered-products" class="products-list hidden">
    </div>
    </div>
</div>

<div id="popup-overlay" class="popup-overlay">
    <div class="popup">
        <p>Product has been added to your cart.</p>
        <button id="go-to-cart">Go to Cart</button>
        <button id="keep-shopping">Keep Shopping</button>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="js/nav.js"></script>
<script src="js/data.js"></script>
<script src="js/index.js"></script>
<script src="js/popup.js"></script>
</body>
</html>
