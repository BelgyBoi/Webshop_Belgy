<?php
session_start();

require '../vendor/autoload.php';

use WebshopBelgy\CartFunction;
use WebshopBelgy\Database;

$conn = Database::getConnection();
$cartFunction = new CartFunction($conn);

$productId = isset($_POST['product_id']) ? intval($_POST['product_id']) : null;
$variantId = isset($_POST['variant_id']) ? intval($_POST['variant_id']) : null;

if ($productId !== null) {
    // Debugging information
    error_log("Attempting to remove: Product ID: $productId, Variant ID: $variantId");

    if ($cartFunction->removeFromCart($productId, $variantId)) {
        $totalPrice = $cartFunction->calculateTotalPrice();
        echo json_encode(['status' => 'success', 'total' => $totalPrice]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to remove item from cart']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid product or variant ID']);
}
?>
