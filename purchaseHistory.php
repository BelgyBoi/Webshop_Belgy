<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.php');
    exit();
}

require 'vendor/autoload.php';

use WebshopBelgy\Database;

$conn = Database::getConnection();
$userId = $_SESSION['user_id'];

// Fetch user's orders
$ordersStatement = $conn->prepare('SELECT * FROM orders WHERE user_id = :user_id ORDER BY created_at DESC');
$ordersStatement->bindValue(':user_id', $userId);
$ordersStatement->execute();
$orders = $ordersStatement->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Purchase History</title>
    <link rel="stylesheet" href="css/normalize.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <?php include_once("classes/nav.php"); ?>

    <div class="container">
        <h1>Purchase History</h1>
        <?php if (!empty($orders)): ?>
            <?php foreach ($orders as $order): ?>
                <h2 class="history_title"><?php echo htmlspecialchars($order['created_at']); ?></h2>
                <?php
                // Fetch order items for each order
                $orderItemsStatement = $conn->prepare('SELECT * FROM order_items WHERE order_id = :order_id');
                $orderItemsStatement->bindValue(':order_id', $order['id']);
                $orderItemsStatement->execute();
                $orderItems = $orderItemsStatement->fetchAll(PDO::FETCH_ASSOC);
                ?>
                <?php foreach ($orderItems as $item): ?>
                    <div class="product-item">
                        <img src="<?php echo htmlspecialchars($item['main_image_url']); ?>" alt="<?php echo htmlspecialchars($item['product_name']); ?>" class="product-image">
                        <div>
                            <h3><a class="history_name" href="focus.php?product_id=<?php echo htmlspecialchars($item['product_id']); ?>"><?php echo htmlspecialchars($item['product_name']); ?></a></h3>
                            <p>Quantity: <?php echo htmlspecialchars($item['quantity']); ?></p>
                            <p>Price: BC<?php echo htmlspecialchars($item['price']); ?> (Total: BC<?php echo htmlspecialchars($item['price'] * $item['quantity']); ?>)</p>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endforeach; ?>
        <?php else: ?>
            <p>You have no previous orders.</p>
        <?php endif; ?>
    </div>
</body>
</html>
