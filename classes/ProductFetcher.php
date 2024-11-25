<?php

namespace WebshopBelgy;

use PDO;

class ProductFetcher {
    private $conn;

    public function __construct(PDO $conn) {
        $this->conn = $conn;
    }

    public function getAllProducts() {
        $statement = $this->conn->prepare('SELECT * FROM products');
        $statement->execute();
        $products = $statement->fetchAll(PDO::FETCH_ASSOC);

        foreach ($products as &$product) {
            $product['images'] = $this->getProductImages($product['id']);
        }

        return $products;
    }

    public function getProductsByCategory($categoryId) {
        $statement = $this->conn->prepare('SELECT * FROM products WHERE category_id = :category_id');
        $statement->execute(['category_id' => $categoryId]);
        $products = $statement->fetchAll(PDO::FETCH_ASSOC);

        foreach ($products as &$product) {
            $product['images'] = $this->getProductImages($product['id']);
        }

        return $products;
    }

    public function getProductById($productId) {
        $statement = $this->conn->prepare('SELECT * FROM products WHERE id = :product_id');
        $statement->execute(['product_id' => $productId]);
        $product = $statement->fetch(PDO::FETCH_ASSOC);

        if ($product) {
            $product['images'] = $this->getProductImages($productId);
        }

        return $product;
    }

    public function getProductImages($productId) { // Changed to public
        $statement = $this->conn->prepare('SELECT image_url FROM product_images WHERE product_id = :product_id');
        $statement->execute(['product_id' => $productId]);
        return $statement->fetchAll(PDO::FETCH_COLUMN);
    }
}

?>
