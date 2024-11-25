<?php

require 'vendor/autoload.php';

use WebshopBelgy\Database;
use WebshopBelgy\ProductFetcher;

$conn = Database::getConnection();

if ($conn) {
    $product_id = isset($_GET['product_id']) ? intval($_GET['product_id']) : 0;

    if ($product_id > 0) {
        $productFetcher = new ProductFetcher($conn);
        $product = $productFetcher->getProductById($product_id); // Assuming you have a method to fetch a single product
        $product_images = $productFetcher->getProductImages($product_id);
    } else {
        echo 'Product not found';
        exit;
    }
} else {
    echo 'Connection failed';
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($product['name']); ?></title>
    <link rel="stylesheet" href="css/normalize.css">
    <link rel="stylesheet" href="css/style.css"> 
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.css">
    <link rel="stylesheet" href="css/focus.css"> <!-- Include custom CSS -->
</head>
<body>

<?php 
include_once("classes/nav.php"); 
include_once("classes/widgets.php");
?>

<div class="product-details">
    <h1><?php echo htmlspecialchars($product['name']); ?></h1>
    <div class="carousel">
        <?php foreach ($product_images as $image): ?>
            <div><img class="focus_image" src="<?php echo htmlspecialchars($image); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>"></div>
        <?php endforeach; ?>
    </div>
    <div class="carousel-thumbnails">
        <?php foreach ($product_images as $image): ?>
            <div><img class="small_image" src="<?php echo htmlspecialchars($image); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>"></div>
        <?php endforeach; ?>
    </div>
    <p class="product-description"><?php echo htmlspecialchars($product['description']); ?></p>
    <p class="product-price">€<?php echo htmlspecialchars($product['price']); ?></p>
    <button class="add-to-cart">Add to cart</button>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.js"></script>
<script src="js/nav.js"></script>
<script src="js/data.js"></script>
<script src="js/focus.js"></script> <!-- Include the new focus.js script -->
</body>
</html>
