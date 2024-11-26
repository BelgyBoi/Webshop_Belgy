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
    $cartFunction->updateQuantity($productId, $quantity);
    
    // Get updated cart and total price
    $cart = $cartFunction->getCart();
    $totalPrice = $cartFunction->calculateTotalPrice();
    
    // Find the updated product price
    $updatedProduct = null;
    foreach ($cart as $item) {
        if ($item['id'] == $productId) {
            $updatedProduct = $item;
            break;
        }
    }
    
    header('Content-Type: application/json'); // Ensure JSON response
    echo json_encode([
        'status' => 'success', 
        'total' => $totalPrice, 
        'product_price' => $updatedProduct ? $updatedProduct['price'] * $updatedProduct['quantity'] : 0
    ]);
} else {
    header('Content-Type: application/json');
    echo json_encode(['status' => 'error']);
}
?>
