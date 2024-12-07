<?php
session_start();

require 'vendor/autoload.php';

use WebshopBelgy\Database;
use WebshopBelgy\ProductFetcher;
use WebshopBelgy\RatingFetcher;

$conn = Database::getConnection();

$product = null;
$product_images = [];
$product_variants = [];
$variant_images = [];
$ratings = [];
$averageRating = 0.0;

if ($conn) {
    $product_id = isset($_GET['product_id']) ? intval($_GET['product_id']) : 0;
    $variant_id = isset($_GET['variant_id']) ? intval($_GET['variant_id']) : 0;

    $productFetcher = new ProductFetcher($conn);
    if ($product_id > 0) {
        $product = $productFetcher->getProductById($product_id);
        $product_images = $productFetcher->getProductImages($product_id);
        $product_variants = $productFetcher->getProductVariants($product_id);

        foreach ($product_variants as $variant) {
            $variant_images[$variant['id']] = $productFetcher->getVariantImages($variant['id']);
        }

        if ($product) {
            $ratingFetcher = new RatingFetcher($conn);
            $ratings = $ratingFetcher->getRatingsByProductId($product_id);
            $averageRating = $ratingFetcher->getAverageRating($product_id);
        } else {
            echo 'Product not found';
            exit;
        }
    } else {
        echo 'Product ID is invalid';
        exit;
    }
} else {
    echo 'Connection failed';
    exit;
}

if (!$product) {
    echo 'Product not found or connection failed';
    exit;
}

$price = $product['price'];
if ($variant_id) {
    $selected_variant = $productFetcher->getVariantById($variant_id);
    if ($selected_variant) {
        $price = $selected_variant['price'];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($product['name']); ?></title>
    <link rel="stylesheet" href="css/normalize.css">
    <link rel="stylesheet" href="css/style.css"> 
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.css">
    <style>
        .hidden {
            display: none;
        }
    </style>
</head>
<body>

<?php 
include_once("classes/nav.php"); 
include_once("classes/widgets.php");
?>

<div class="product-details">
    <div id="main_content">
        <h1><?php echo htmlspecialchars($product['name']); ?></h1>

        <!-- Product Images Carousel -->
        <div class="carousel product-carousel">
            <?php foreach ($product_images as $image): ?>
                <div><img class="focus_image" src="<?php echo htmlspecialchars($image['image_url']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>"></div>
            <?php endforeach; ?>
        </div>
        
        <!-- Variant Images Carousel -->
        <div class="carousel variant-carousel hidden">
            <?php foreach ($variant_images as $variant_id => $images): ?>
                <?php foreach ($images as $image): ?>
                    <div><img class="focus_image" src="<?php echo htmlspecialchars($image['image_url']); ?>" alt=""></div>
                <?php endforeach; ?>
            <?php endforeach; ?>
        </div>

        <div class="carousel-thumbnails product-thumbnails">
            <?php foreach ($product_images as $image): ?>
                <div><img class="small_image" src="<?php echo htmlspecialchars($image['image_url']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>"></div>
            <?php endforeach; ?>
        </div>
        
        <div class="carousel-thumbnails variant-thumbnails hidden">
            <?php foreach ($variant_images as $variant_id => $images): ?>
                <?php foreach ($images as $image): ?>
                    <div><img class="small_image" src="<?php echo htmlspecialchars($image['image_url']); ?>" alt=""></div>
                <?php endforeach; ?>
            <?php endforeach; ?>
        </div>
        
        <!-- Variant Links -->
        <?php if (!empty($product_variants)) : ?>
            <div class="product-variants">
                <?php foreach ($product_variants as $variant) : ?>
                    <?php if (!empty($variant_images[$variant['id']])) : ?>
                        <a href="#" class="variant-link link" data-variant-id="<?php echo $variant['id']; ?>">
                            <img class="variant-image link_image" src="<?php echo htmlspecialchars($variant_images[$variant['id']][0]['image_url']); ?>" alt="<?php echo htmlspecialchars($variant['variant_name']); ?>">
                        </a>
                    <?php endif; ?>
                <?php endforeach; ?>
                <a href="#" class="product-link link">
                    <img class="variant-image link_image" src="<?php echo htmlspecialchars($product_images[0]['image_url']); ?>" alt="Back to Product Images">
                </a>
            </div>
        <?php endif; ?>

        <p class="product-price">BC<?php echo htmlspecialchars($price); ?></p>
        <button class="add-to-cart product_button" data-product-id="<?php echo $product_id; ?>">Add to cart</button>
        <p class="product-description"><?php echo htmlspecialchars($product['description']); ?></p>

        <!-- Rating Display -->
        <div class="product-ratings">
            <h3>Average Rating: <?php echo number_format($averageRating, 1); ?> / 5</h3>
            <div class="average-rating-stars">
                <?php for ($i = 1; $i <= 5; $i++): ?>
                    <?php if ($i <= round($averageRating)): ?>
                        <i class="fas fa-star"></i>
                    <?php else: ?>
                        <i class="far fa-star"></i>
                    <?php endif; ?>
                <?php endfor; ?>
            </div>
            <?php if (!empty($ratings)): ?>
                <?php foreach ($ratings as $rating): ?>
                    <div class="rating">
                        <p><b><?php echo htmlspecialchars($rating['firstname']) . ' ' . htmlspecialchars($rating['lastname']); ?></b></p>
                        <p>Rating: 
                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                <?php if ($i <= $rating['rating']): ?>
                                    <i class="fas fa-star"></i>
                                <?php else: ?>
                                    <i class="far fa-star"></i>
                                <?php endif; ?>
                            <?php endfor; ?>
                        </p>
                        <p><?php echo htmlspecialchars($rating['review']); ?></p>
                        <p><small><?php echo htmlspecialchars($rating['created_at']); ?></small></p>
                        <?php if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $rating['user_id']): ?>
                            <button class="delete-rating" data-rating-id="<?php echo $rating['id']; ?>"><i class="fas fa-trash-alt"></i></button>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No ratings yet.</p>
            <?php endif; ?>
        </div>
    </div>

    <!-- Rating Form -->
    <?php if (isset($_SESSION['user_id'])): ?>
        <div class="rating-form">
            <h3>Submit Your Rating</h3>
            <form action="classes/submit_rating.php" method="post">
                <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">
                <input type="hidden" id="rating" name="rating" value="">
                <label for="rating">Rating:</label>
                <div class="star-rating">
                    <i class="fas fa-star" data-value="1"></i>
                    <i class="fas fa-star" data-value="2"></i>
                    <i class="fas fa-star" data-value="3"></i>
                    <i class="fas fa-star" data-value="4"></i>
                    <i class="fas fa-star" data-value="5"></i>
                </div>
                <br>
                <label for="review">Review:</label>
                <textarea name="review" id="review"></textarea>
                <br>
                <button type="submit">Submit Rating</button>
            </form>
        </div>
    <?php else: ?>
        <p>Please <a class="login_link" href="login.php">log in</a> to submit a rating.</p>
    <?php endif; ?>
</div>

<div id="popup-overlay" class="popup-overlay">
    <div class="popup">
        <p>Product has been added to your cart.</p>
        <button id="go-to-cart">Go to Cart</button>
        <button id="keep-shopping">Keep Shopping</button>
    </div>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.js"></script>
<script src="js/nav.js"></script>
<script src="js/data.js"></script>
<script src="js/focus.js"></script>
<script src="js/popup.js"></script>
</body>
</html>
