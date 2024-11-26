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

    public function addToCart($productId, $quantity = 1) {
        $product = $this->getProductById($productId);

        if ($product) {
            $productInCart = false;
            foreach ($_SESSION['cart'] as &$item) {
                if ($item['id'] == $productId) {
                    $item['quantity'] += $quantity;
                    $productInCart = true;
                    break;
                }
            }

            if (!$productInCart) {
                $_SESSION['cart'][] = [
                    'id' => $productId,
                    'quantity' => $quantity
                ];
            }
        }
    }

    public function calculateTotalPrice() {
        $total = 0;
        foreach ($_SESSION['cart'] as $item) {
            $product = $this->getProductById($item['id']);
            if ($product) {
                $total += $product['price'] * $item['quantity'];
            }
        }
        return $total;
    }

    public function getCart() {
        $cart = [];
        foreach ($_SESSION['cart'] as $item) {
            $product = $this->getProductById($item['id']);
            if ($product) {
                $product['quantity'] = $item['quantity'];
                $cart[] = $product;
            }
        }
        return $cart;
    }

    public function updateQuantity($productId, $quantity) {
        foreach ($_SESSION['cart'] as &$item) {
            if ($item['id'] == $productId) {
                $item['quantity'] = $quantity;
                break;
            }
        }
    }
}
?>
