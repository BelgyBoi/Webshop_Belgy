<?php
require '../vendor/autoload.php';

use WebshopBelgy\Database;
use WebshopBelgy\ProductFetcher;

header('Content-Type: application/json');

try {
    $conn = Database::getConnection();
    
    if (!$conn) {
        throw new Exception('Database connection failed.');
    }
    
    if (!isset($_GET['id'])) {
        throw new Exception('Invalid product ID.');
    }

    $productFetcher = new ProductFetcher($conn);
    $product = $productFetcher->getProductById($_GET['id']);

    if (!$product) {
        throw new Exception('Product not found.');
    }

    echo json_encode(['status' => 'success', 'product' => $product]);
} catch (Exception $e) {
    error_log($e->getMessage());
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
?>
