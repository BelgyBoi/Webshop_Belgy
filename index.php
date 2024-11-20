<?php

require 'vendor/autoload.php';

use WebshopBelgy\Database;
use WebshopBelgy\CategoryFetcher;

$conn = Database::getConnection();

if ($conn) {
    $fetcher = new CategoryFetcher($conn);
    $categories = $fetcher->getCategories();
} else {
    echo 'Connection failed';
    $products = [];
}


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

<div class="list">
    <?php
        if (!empty($categories)) {
            foreach ($products as $product) {
                echo '<div class="product-item">';
                echo '<img src="' . $product['main_image_image'] . '" alt="' . $product['name'] . '">';
                echo '<h2>' . $product['name'] . '</h2>';
                echo '<p>' . $product['description'] . '</p>';
                echo '<p>€' . $product['price'] . '</p>';
                echo '</div>';
            }
        }
    ?>
</div>




    <script src="js/nav.js"></script>
    <script src="js/data.js"></script>
</body>
</html>
