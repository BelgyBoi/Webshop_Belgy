<?php
session_start();

require '../vendor/autoload.php';

use WebshopBelgy\Database;
use WebshopBelgy\CartFunction;

$conn = Database::getConnection();

if ($conn && isset($_POST['product_id']) && isset($_POST['quantity'])) {
    $cartFunction = new CartFunction($conn);
    $productId = intval($_POST['product_id']);
    $quantity = intval($_POST['quantity']);
    $variantId = isset($_POST['variant_id']) ? intval($_POST['variant_id']) : null;

    error_log("Updating cart: Product ID = $productId, Quantity = $quantity, Variant ID = " . ($variantId ? $variantId : 'null'));

    if ($quantity == 0) {
        $cartFunction->removeFromCart($productId, $variantId);
    } else {
        if ($variantId) {
            $cartFunction->updateQuantity($productId, $quantity, $variantId);
        } else {
            $cartFunction->updateQuantity($productId, $quantity);
        }
    }

    $cart = $cartFunction->getCart();
    $totalPrice = $cartFunction->calculateTotalPrice();
    error_log("Total price after update: $totalPrice");

    $updatedProduct = null;
    foreach ($cart as $item) {
        if ($item['id'] == $productId && ($variantId === null || $item['variant_id'] == $variantId)) {
            $updatedProduct = $item;
            break;
        }
    }

    header('Content-Type: application/json');
    echo json_encode([
        'status' => 'success',
        'total' => $totalPrice,
        'product_price' => $updatedProduct ? $updatedProduct['price'] * $updatedProduct['quantity'] : 0,
        'image' => $updatedProduct ? $updatedProduct['image'] : null
    ]);
} else {
    error_log('Error: Invalid POST data');
    header('Content-Type: application/json');
    echo json_encode(['status' => 'error']);
}
?>
