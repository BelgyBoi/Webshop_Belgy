<?php
session_start();

require '../vendor/autoload.php';

use WebshopBelgy\Database;

$conn = Database::getConnection();
$userId = $_SESSION['user_id'];
$totalPrice = isset($_POST['total_price']) ? intval($_POST['total_price']) : 0;
$cartFunction = new WebshopBelgy\CartFunction($conn);
$cartItems = $cartFunction->getCart();

$orderId = Database::processCheckout($userId, $totalPrice, $cartItems);
if ($orderId) {
    // Store order ID in session to display in success page
    $_SESSION['order_id'] = $orderId;
    // Redirect to success page
    header('Location: success.php');
    exit();
} else {
    // Redirect to error page if not enough currency
    header('Location: error.php');
    exit();
}
?>
