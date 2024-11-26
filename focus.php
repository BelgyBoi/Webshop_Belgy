<?php

require 'vendor/autoload.php';

use WebshopBelgy\Database;
use WebshopBelgy\ProductFetcher;
use WebshopBelgy\CartFunction;
use WebshopBelgy\RatingFetcher;

session_start();
$conn = Database::getConnection();

$product = null;
$product_images = [];
$ratings = [];
$averageRating = 0.0;

if ($conn) {
    $product_id = isset($_GET['product_id']) ? intval($_GET['product_id']) : 0;

    if ($product_id > 0) {
        $productFetcher = new ProductFetcher($conn);
        $product = $productFetcher->getProductById($product_id);
        $product_images = $productFetcher->getProductImages($product_id);

        if ($product) {
            // Fetch ratings
            $ratingFetcher = new RatingFetcher($conn);
            $ratings = $ratingFetcher->getRatingsByProductId($product_id);
            $averageRating = $ratingFetcher->getAverageRating($product_id);
        }
    }
}

if (!$product) {
    echo 'Product not found or connection failed';
    exit;
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
</head>
<body>

<?php 
include_once("classes/nav.php"); 
include_once("classes/widgets.php");
?>

<div class="product-details">
    <h1><?php echo htmlspecialchars($product['name']); ?></h1>
    <div class="carousel">
        <?php foreach ($product_images as $image): ?>
            <div><img class="focus_image" src="<?php echo htmlspecialchars($image); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>"></div>
        <?php endforeach; ?>
    </div>
    <div class="carousel-thumbnails">
        <?php foreach ($product_images as $image): ?>
            <div><img class="small_image" src="<?php echo htmlspecialchars($image); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>"></div>
        <?php endforeach; ?>
    </div>
    <p class="product-description"><?php echo htmlspecialchars($product['description']); ?></p>
    <p class="product-price">€<?php echo htmlspecialchars($product['price']); ?></p>
    <button class="add-to-cart" data-product-id="<?php echo $product_id; ?>">Add to cart</button>

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
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No ratings yet.</p>
        <?php endif; ?>
    </div>

    <!-- Rating Form -->
    <div class="rating-form">
        <h3>Submit Your Rating</h3>
        <form action="submit_rating.php" method="post">
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
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.js"></script>
<script src="js/nav.js"></script>
<script src="js/data.js"></script>
<script src="js/focus.js"></script>
</body>
</html>
