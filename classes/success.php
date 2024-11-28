<?php
session_start();

require '../vendor/autoload.php';

use WebshopBelgy\Database;

$conn = Database::getConnection();
$orderId = $_SESSION['order_id'];

// Fetch order items from the database
$orderItemsStatement = $conn->prepare('SELECT * FROM order_items WHERE order_id = :order_id');
$orderItemsStatement->bindValue(':order_id', $orderId);
$orderItemsStatement->execute();
$orderItems = $orderItemsStatement->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Checkout Success</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

    <div class="container">
        <h1>Checkout Success</h1>
        <p>Your purchase was successful!</p>
        
        <h2>Purchased Items</h2>
        <?php foreach ($orderItems as $item): ?>
            <div class="product-item">
                <img src="../<?php echo htmlspecialchars($item['main_image_url']); ?>" alt="<?php echo htmlspecialchars($item['product_name']); ?>" class="product-image">
                <div>
                    <h3><?php echo htmlspecialchars($item['product_name']); ?></h3>
                    <p>Quantity: <?php echo htmlspecialchars($item['quantity']); ?></p>
                    <p>Price: €<?php echo htmlspecialchars($item['price']); ?> (Total: €<?php echo htmlspecialchars($item['price'] * $item['quantity']); ?>)</p>
                    <p>Image URL: ../<?php echo htmlspecialchars($item['main_image_url']); ?></p> <!-- Debugging info -->
                </div>
            </div>
        <?php endforeach; ?>
        <div class="footer">
            <p>You will be redirected to the homepage in <span id="countdown">10</span> seconds...</p>
            <p>If you have issues going back to homescreen please click this link<a href="../index.php">Go to Homepage</a></p>
        </div>
    </div>

    <script src="../js/success.js"></script>
</body>
</html>
