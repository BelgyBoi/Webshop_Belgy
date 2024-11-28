<?php
session_start();

require 'vendor/autoload.php';

use WebshopBelgy\Database;
use WebshopBelgy\CartFunction;

$conn = Database::getConnection();
$cartFunction = new CartFunction($conn);
$cart = $cartFunction->getCart();
$totalPrice = $cartFunction->calculateTotalPrice();

// Debugging statement to log the cart data
error_log('Cart data: ' . print_r($cart, true));
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
                <div class="product-item" data-product-id="<?php echo htmlspecialchars($item['id']); ?>" data-variant-id="<?php echo htmlspecialchars($item['variant_id'] ?? ''); ?>">
                    <div class="product-info">
                        <img src="<?php echo htmlspecialchars($item['image']); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>" class="product-image">
                        <div>
                            <h3><?php echo htmlspecialchars($item['name']); ?></h3> <!-- This will display either the product name or variant name -->
                        </div>
                    </div>
                    <div class="product-quantity">
                        <button class="quantity-decrease" data-product-id="<?php echo htmlspecialchars($item['id']); ?>" data-variant-id="<?php echo htmlspecialchars($item['variant_id'] ?? ''); ?>">-</button>
                        <input type="text" value="<?php echo htmlspecialchars($item['quantity']); ?>" class="quantity-input" data-product-id="<?php echo htmlspecialchars($item['id']); ?>" data-variant-id="<?php echo htmlspecialchars($item['variant_id'] ?? ''); ?>">
                        <button class="quantity-increase" data-product-id="<?php echo htmlspecialchars($item['id']); ?>" data-variant-id="<?php echo htmlspecialchars($item['variant_id'] ?? ''); ?>">+</button>
                    </div>
                    <div class="product-price" data-product-id="<?php echo htmlspecialchars($item['id']); ?>" data-variant-id="<?php echo htmlspecialchars($item['variant_id'] ?? ''); ?>">BC<?php echo htmlspecialchars($item['price'] * $item['quantity']); ?></div>
                    <button class="remove-item" data-product-id="<?php echo htmlspecialchars($item['id']); ?>" data-variant-id="<?php echo htmlspecialchars($item['variant_id'] ?? ''); ?>">Remove</button>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Your cart is empty.</p>
        <?php endif; ?>
    </div>
    <div class="cart-summary">
        <h2>Order Summary</h2>
        <div class="summary-total">
            Total: BC<?php echo number_format($totalPrice, 2); ?>
        </div>
        <?php if (!empty($cart)): ?>
            <form action="classes/checkout.php" method="post">
                <input type="hidden" name="total_price" value="<?php echo htmlspecialchars($totalPrice); ?>">
                <button type="submit">Proceed to Checkout</button>
            </form>
        <?php endif; ?>
    </div>
</div>

<div id="popup-overlay" class="popup-overlay">
    <div class="popup">
        <p>Are you sure you want to remove this item from your cart?</p>
        <button id="confirm-remove">Yes</button>
        <button id="cancel-remove">No</button>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="js/nav.js"></script>
<script src="js/data.js"></script>
<script src="js/cart.js"></script>

</body>
</html>
