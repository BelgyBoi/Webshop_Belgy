<?php
require '../vendor/autoload.php';

use WebshopBelgy\Database;
use WebshopBelgy\ProductFetcher;

$conn = Database::getConnection();

$variantId = isset($_GET['variant_id']) ? intval($_GET['variant_id']) : 0;

if ($conn && $variantId > 0) {
    $productFetcher = new ProductFetcher($conn);
    $price = $productFetcher->getVariantPrice($variantId);

    error_log("Fetching price for variant ID: $variantId, Price: $price");

    if ($price !== null) {
        echo json_encode(['status' => 'success', 'price' => $price]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Variant not found']);
    }
} else {
    error_log('Invalid variant ID or connection failed');
    echo json_encode(['status' => 'error', 'message' => 'Invalid variant ID']);
}
?>

