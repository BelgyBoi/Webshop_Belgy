<?php
session_start();

$product_id = isset($_POST['id']) ? intval($_POST['id']) : 0;
$quantity = 1; // Default quantity to 1

if ($product_id > 0) {
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    $found = false;
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $product_id) {
            $item['quantity'] += $quantity;
            $found = true;
            break;
        }
    }

    if (!$found) {
        $_SESSION['cart'][] = [
            'id' => $product_id,
            'quantity' => $quantity
        ];
    }

    echo json_encode(['status' => 'success', 'cart' => $_SESSION['cart']]);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid product ID']);
}
?>
