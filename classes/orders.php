<?php
session_start();

require '../vendor/autoload.php';

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
    <title>Your Orders</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <?php include_once("classes/nav.php"); ?>

    <div class="container">
        <h1>Your Orders</h1>
        <?php if (!empty($orders)): ?>
            <?php foreach ($orders as $order): ?>
                <h2>Order #<?php echo $order['id']; ?> - Total: €<?php echo $order['total_price']; ?> - Date: <?php echo $order['created_at']; ?></h2>
                <?php
                // Fetch order items for each order
                $orderItemsStatement = $conn->prepare('SELECT * FROM order_items WHERE order_id = :order_id');
                $orderItemsStatement->bindValue(':order_id', $order['id']);
                $orderItemsStatement->execute();
                $orderItems = $orderItemsStatement->fetchAll(PDO::FETCH_ASSOC);
                ?>
                <?php foreach ($orderItems as $item): ?>
                    <div class="product-item">
                        <img src="<?php echo $item['main_image_url']; ?>" alt="<?php echo $item['product_name']; ?>" class="product-image">
                        <div>
                            <h3><?php echo $item['product_name']; ?></h3>
                            <p>Quantity: <?php echo $item['quantity']; ?></p>
                            <p>Price: €<?php echo $item['price']; ?> (Total: €<?php echo $item['price'] * $item['quantity']; ?>)</p>
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
