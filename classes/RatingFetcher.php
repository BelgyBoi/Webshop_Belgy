<?php

namespace WebshopBelgy;

use PDO;

class RatingFetcher {
    private $conn;

    public function __construct(PDO $conn) {
        $this->conn = $conn;
    }

    public function getRatingsByProductId($productId) {
        $stmt = $this->conn->prepare('SELECT * FROM ratings WHERE product_id = :product_id ORDER BY created_at DESC');
        $stmt->execute(['product_id' => $productId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAverageRating($productId) {
        $stmt = $this->conn->prepare('SELECT AVG(rating) as average_rating FROM ratings WHERE product_id = :product_id');
        $stmt->execute(['product_id' => $productId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['average_rating'];
    }

    public function submitRating($productId, $userId, $rating, $review) {
        $stmt = $this->conn->prepare('INSERT INTO ratings (product_id, user_id, rating, review) VALUES (:product_id, :user_id, :rating, :review)');
        $stmt->execute([
            'product_id' => $productId,
            'user_id' => $userId,
            'rating' => $rating,
            'review' => $review
        ]);
    }
}
?>
