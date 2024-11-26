<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    echo 'You must be logged in to submit a rating.';
    exit;
}

require '../vendor/autoload.php';

use WebshopBelgy\Database;
use WebshopBelgy\RatingFetcher;

$conn = Database::getConnection();

if ($conn) {
    $product_id = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;
    $rating = isset($_POST['rating']) ? intval($_POST['rating']) : 0;
    $review = isset($_POST['review']) ? trim($_POST['review']) : '';
    $user_id = isset($_SESSION['user_id']) ? intval($_SESSION['user_id']) : null;

    if ($product_id > 0 && $rating >= 0 && $rating <= 5) {
        $ratingFetcher = new RatingFetcher($conn);
        $ratingFetcher->submitRating($product_id, $user_id, $rating, $review);
    }
}

header('Location: ../focus.php?product_id=' . $product_id);
?>
