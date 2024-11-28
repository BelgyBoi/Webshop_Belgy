<?php

require '../vendor/autoload.php';

use WebshopBelgy\Database;
use WebshopBelgy\ProductFetcher;

$conn = Database::getConnection();

if ($conn) {
    $productFetcher = new ProductFetcher($conn);
    $categoryId = isset($_GET['category_id']) ? intval($_GET['category_id']) : 0;

    if ($categoryId > 0) {
        $products = $productFetcher->getProductsByCategory($categoryId);
    } else {
        $products = $productFetcher->getAllProducts();
    }

    echo json_encode($products);
} else {
    echo json_encode([]);
}

?>
