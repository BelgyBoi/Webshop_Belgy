<?php
session_start();

require 'vendor/autoload.php';

use WebshopBelgy\Database;
use WebshopBelgy\CartFunction;

$conn = Database::getConnection();
$cartFunction = new CartFunction($conn);
$cart = $cartFunction->getCart();
$totalPrice = $cartFunction->calculateTotalPrice();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart</title>
    <link rel="stylesheet" href="css/normalize.css">
    <link rel="stylesheet" href="css/style.css"> 
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>

<?php include_once("classes/nav.php"); ?>
<?php include_once("classes/widgets.php"); ?>
<div class="cart-container">
    <div class="cart-products">
        <h2>Your Cart</h2>
        <?php if (!empty($cart)): ?>
            <?php foreach ($cart as $item): ?>
                <div class="product-item">
                    <div class="product-info">
                        <img src="<?php echo $item['main_image_url']; ?>" alt="<?php echo $item['name']; ?>" class="product-image">
                        <div>
                            <h3><?php echo $item['name']; ?></h3>
                        </div>
                    </div>
                    <div class="product-quantity">
                        <button class="quantity-decrease" data-product-id="<?php echo $item['id']; ?>">-</button>
                        <input type="text" value="<?php echo $item['quantity']; ?>" class="quantity-input" data-product-id="<?php echo $item['id']; ?>">
                        <button class="quantity-increase" data-product-id="<?php echo $item['id']; ?>">+</button>
                    </div>
                    <div class="product-price" data-product-id="<?php echo $item['id']; ?>">€<?php echo $item['price'] * $item['quantity']; ?></div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Your cart is empty.</p>
        <?php endif; ?>
    </div>
    <div class="cart-summary">
        <h2>Order Summary</h2>
        <div class="summary-total">
            Total: €<?php echo number_format($totalPrice, 2); ?>
        </div>
    </div>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="js/nav.js"></script>
<script src="js/data.js"></script>
<script src="js/cart.js"></script>

</body>
</html>
