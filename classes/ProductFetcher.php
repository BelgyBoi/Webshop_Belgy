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
        $statement = $this->conn->prepare('SELECT * FROM products WHERE id = :id');
        $statement->execute(['id' => $productId]);
        return $statement->fetch(PDO::FETCH_ASSOC);
    }

    public function getProductImages($productId) {
        $statement = $this->conn->prepare('SELECT id, image_url FROM product_images WHERE product_id = :product_id');
        $statement->execute(['product_id' => $productId]);
        return $statement->fetchAll(PDO::FETCH_ASSOC); // Fetch both ID and URL as an associative array
    }
    
    
    
    public function getProductVariants($productId) {
        $statement = $this->conn->prepare('SELECT * FROM product_variants WHERE product_id = :product_id');
        $statement->execute(['product_id' => $productId]);
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getVariantImages($variantId) {
        $statement = $this->conn->prepare('SELECT id, image_url FROM variant_images WHERE variant_id = :variant_id');
        $statement->execute(['variant_id' => $variantId]);
        return $statement->fetchAll(PDO::FETCH_ASSOC); // Fetch both ID and URL as an associative array
    }
 
    public function getVariantById($variantId) {
        $statement = $this->conn->prepare('
            SELECT v.*, vi.image_url AS image 
            FROM product_variants v 
            LEFT JOIN variant_images vi ON v.id = vi.variant_id 
            WHERE v.id = :id 
            LIMIT 1
        ');
        $statement->execute(['id' => $variantId]);
        return $statement->fetch(PDO::FETCH_ASSOC);
    }

    public function getFirstVariantImage($variantId) {
        $statement = $this->conn->prepare('SELECT image_url FROM variant_images WHERE variant_id = :variant_id LIMIT 1');
        $statement->execute(['variant_id' => $variantId]);
        return $statement->fetch(PDO::FETCH_COLUMN);
    }

    public function getVariantPrice($variantId) {
        $statement = $this->conn->prepare('SELECT price FROM product_variants WHERE id = :id');
        $statement->execute(['id' => $variantId]);
        $variant = $statement->fetch(PDO::FETCH_ASSOC);

        return $variant ? $variant['price'] : null;
    }

    public function searchProducts($search) {
        $statement = $this->conn->prepare('SELECT * FROM products WHERE name LIKE :search OR id LIKE :search');
        $statement->bindValue(':search', '%' . $search . '%');
        $statement->execute();
        $products = $statement->fetchAll(PDO::FETCH_ASSOC);

        foreach ($products as &$product) {
            $product['images'] = $this->getProductImages($product['id']);
        }

        return $products;
    }

    public function getAllVariants() {
        $statement = $this->conn->prepare('SELECT * FROM product_variants');
        $statement->execute();
        $variants = $statement->fetchAll(PDO::FETCH_ASSOC);

        foreach ($variants as &$variant) {
            $variant['first_image'] = $this->getFirstVariantImage($variant['id']);
        }

        return $variants;
    }

    public function searchVariants($search) {
        $statement = $this->conn->prepare('SELECT * FROM product_variants WHERE variant_name LIKE :search OR id LIKE :search');
        $statement->bindValue(':search', '%' . $search . '%');
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }
}
