<?php
session_start();

require '../vendor/autoload.php';

use WebshopBelgy\CartFunction;
use WebshopBelgy\Database;

$conn = Database::getConnection();

if ($conn && isset($_POST['product_id'])) {
    $cartFunction = new CartFunction($conn);
    $productId = intval($_POST['product_id']);
    $variantId = isset($_POST['variant_id']) ? intval($_POST['variant_id']) : null;
    $quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1;

    // Debugging statement
    error_log("Product ID: $productId, Variant ID: $variantId, Quantity: $quantity");

    // Add to cart
    $cartFunction->addToCart($productId, $quantity, $variantId);

    echo json_encode(['status' => 'success']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Failed to add item to cart']);
}
?>
