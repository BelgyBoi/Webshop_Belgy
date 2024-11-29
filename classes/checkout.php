<?php
session_start();

require '../vendor/autoload.php';

use WebshopBelgy\Database;

$conn = Database::getConnection();
$userId = $_SESSION['user_id'];
$totalPrice = isset($_POST['total_price']) ? intval($_POST['total_price']) : 0;
$cartFunction = new WebshopBelgy\CartFunction($conn);
$cartItems = $cartFunction->getCart();

// Retrieve the user's current currency balance from the accounts table
$walletBalanceQuery = $conn->prepare("SELECT currency FROM accounts WHERE id = :userId");
$walletBalanceQuery->bindParam(':userId', $userId, PDO::PARAM_INT);
$walletBalanceQuery->execute();
$user = $walletBalanceQuery->fetch(PDO::FETCH_ASSOC);

if ($user && $user['currency'] >= $totalPrice) {
    // Proceed with the checkout process if the user has enough funds
    $orderId = Database::processCheckout($userId, $totalPrice, $cartItems);
    if ($orderId) {
        // Deduct the total price from the user's currency balance
        $updateBalanceQuery = $conn->prepare("UPDATE accounts SET currency = currency - :totalPrice WHERE id = :userId");
        $updateBalanceQuery->bindParam(':totalPrice', $totalPrice, PDO::PARAM_INT);
        $updateBalanceQuery->bindParam(':userId', $userId, PDO::PARAM_INT);
        $updateBalanceQuery->execute();

        // Store order ID in session to display on success page
        $_SESSION['order_id'] = $orderId;
        // Redirect to success page
        header('Location: success.php');
        exit();
    } else {
        // Redirect to error page if checkout processing fails
        header('Location: classes/error.php');
        exit();
    }
} else {
    // Redirect to error page if not enough funds in the wallet
    header('Location: error.php?reason=insufficient_funds');
    exit();
}
?>
