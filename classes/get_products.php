<?php

require '../vendor/autoload.php';

use WebshopBelgy\Database;
use WebshopBelgy\ProductFetcher;

$conn = Database::getConnection();

if ($conn) {
    $categoryId = isset($_GET['category_id']) ? intval($_GET['category_id']) : 0;

    if ($categoryId > 0) {
        $productFetcher = new ProductFetcher($conn);
        $products = $productFetcher->getProductsByCategory($categoryId);
        echo json_encode($products);
    } else {
        echo json_encode([]);
    }
} else {
    echo json_encode([]);
}

?>
