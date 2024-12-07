<?php
session_start();

require '../vendor/autoload.php';

use WebshopBelgy\Database;

ini_set('error_log', __DIR__ . '/local_error_log.txt');

$conn = Database::getConnection();
$userId = $_SESSION['user_id'];
$totalPrice = 0.0;
$cartFunction = new WebshopBelgy\CartFunction($conn);
$cartItems = $cartFunction->getCart();

// Calculate the total price considering the quantity of each item
foreach ($cartItems as $item) {
    $totalPrice += $item['price'] * $item['quantity'];
}

// Retrieve the user's current currency balance from the accounts table
$walletBalanceQuery = $conn->prepare("SELECT currency FROM accounts WHERE id = :userId");
$walletBalanceQuery->bindParam(':userId', $userId, PDO::PARAM_INT);
$walletBalanceQuery->execute();
$user = $walletBalanceQuery->fetch(PDO::FETCH_ASSOC);

$userCurrency = isset($user['currency']) ? floatval($user['currency']) : 0.0;

// Calculate the new balance after purchase
$newBalance = $userCurrency - $totalPrice;

if ($newBalance >= 0) {
    // Proceed with the checkout process if the new balance is non-negative
    $orderId = Database::processCheckout($userId, $totalPrice, $cartItems);

    if ($orderId) {
        // Update the user's currency balance
        $updateBalanceQuery = $conn->prepare("UPDATE accounts SET currency = :newBalance WHERE id = :userId");
        $updateBalanceQuery->bindParam(':newBalance', $newBalance, PDO::PARAM_STR);
        $updateBalanceQuery->bindParam(':userId', $userId, PDO::PARAM_INT);
        $updateBalanceQuery->execute();

        // Update the session currency to reflect the new balance
        $_SESSION['currency'] = $newBalance;

        // Clear the user's cart after successful transaction
        $cartFunction->clearCart();

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
