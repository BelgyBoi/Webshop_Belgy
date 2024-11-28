<?php

namespace WebshopBelgy;

use PDO;

class CartFunction {
    private $conn;

    public function __construct(PDO $conn) {
        $this->conn = $conn;
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }
    }

    public function getProductById($productId) {
        $stmt = $this->conn->prepare('SELECT * FROM products WHERE id = :id');
        $stmt->execute(['id' => $productId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getVariantById($variantId) {
        $stmt = $this->conn->prepare('
            SELECT v.*, vi.image_url AS image 
            FROM product_variants v 
            JOIN variant_images vi ON v.id = vi.variant_id 
            WHERE v.id = :id 
            LIMIT 1
        ');
        $stmt->execute(['id' => $variantId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function addToCart($productId, $quantity = 1, $variantId = null) {
        $product = $this->getProductById($productId);
        $variant = $variantId ? $this->getVariantById($variantId) : null;
    
        if ($product) {
            $productInCart = false;
            foreach ($_SESSION['cart'] as &$item) {
                if ($item['id'] == $productId && $item['variant_id'] == $variantId) {
                    $item['quantity'] += $quantity;
                    $productInCart = true;
                    break;
                }
            }
    
            if (!$productInCart) {
                $_SESSION['cart'][] = [
                    'id' => $productId,
                    'variant_id' => $variantId,
                    'quantity' => $quantity,
                    'image' => $variant && isset($variant['image']) ? $variant['image'] : $product['main_image_url'],
                    'name' => $variantId ? $variant['variant_name'] : $product['name'], // Use variant name if available
                    'variant_name' => $variant['variant_name'] ?? '',
                    'price' => $variant && isset($variant['price']) ? $variant['price'] : (isset($product['price']) ? $product['price'] : 0)
                ];
            }
    
            // Debugging statement to log the session data
            error_log('Cart after adding item: ' . print_r($_SESSION['cart'], true));
        }
    }
    
    public function getCart() {
        $cart = [];
        foreach ($_SESSION['cart'] as $item) {
            $product = $this->getProductById($item['id']);
            $variant = isset($item['variant_id']) ? $this->getVariantById($item['variant_id']) : null;
            if ($product) {
                $product['quantity'] = $item['quantity'];
                $product['variant_id'] = $item['variant_id'] ?? null;
                $product['image'] = $item['image'] ?? $product['main_image_url'];
                $product['name'] = $item['name']; // Use the name stored in the session, which can be the variant name or product name
                $product['price'] = $item['price'];
                $cart[] = $product;
            }
        }
        return $cart;
    }
    
    
    
    

    public function calculateTotalPrice() {
        $total = 0;
        foreach ($_SESSION['cart'] as $item) {
            $total += $item['price'] * $item['quantity'];
        }
        return $total;
    }

   

    public function updateQuantity($productId, $quantity, $variantId = null) {
        foreach ($_SESSION['cart'] as &$item) {
            if ($item['id'] == $productId && $item['variant_id'] == $variantId) {
                $item['quantity'] = $quantity;
                return true;
            }
        }
        return false;
    }

    public function removeFromCart($productId, $variantId = null) {
        foreach ($_SESSION['cart'] as $key => $item) {
            if ($item['id'] == $productId && (!isset($item['variant_id']) || $item['variant_id'] == $variantId)) {
                unset($_SESSION['cart'][$key]);
                $_SESSION['cart'] = array_values($_SESSION['cart']);
                return true;
            }
        }
        return false;
    }
}
?>
