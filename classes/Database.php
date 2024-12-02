<?php

namespace WebshopBelgy;

use PDO;

class Database {
    private static $conn;

    public static function getConnection() {
        
        if (self::$conn === null) {
            try {
                self::$conn = new PDO('mysql:host=localhost;dbname=webshop_belgy', 'Belgy', '&#ccUgH3esDMe4-_e-^Da4i!WB^4vUks');
                // Set the PDO error mode to exception
                self::$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch(PDOException $e) {
                echo "Connection failed: " . $e->getMessage();
                return null;
            }
        }
        return self::$conn;
    }
    

    public static function processCheckout($userId, $totalPrice, $cartItems) {
        $conn = self::getConnection();
    
        // Fetch user's current currency balance
        $statement = $conn->prepare('SELECT currency FROM accounts WHERE id = :user_id');
        $statement->bindValue(':user_id', $userId);
        $statement->execute();
        $user = $statement->fetch(PDO::FETCH_ASSOC);
        $currentBalance = $user['currency'];
    
        // Check if user has enough currency
        if ($currentBalance >= $totalPrice) {
            // Start transaction
            $conn->beginTransaction();
    
            try {
                // Deduct total price from user's currency
                $newBalance = $currentBalance - $totalPrice;
                $updateStatement = $conn->prepare('UPDATE accounts SET currency = :new_balance WHERE id = :user_id');
                $updateStatement->bindValue(':new_balance', $newBalance);
                $updateStatement->bindValue(':user_id', $userId);
                $updateStatement->execute();
    
                // Store order in orders table
                $orderStatement = $conn->prepare('INSERT INTO orders (user_id, total_price) VALUES (:user_id, :total_price)');
                $orderStatement->bindValue(':user_id', $userId);
                $orderStatement->bindValue(':total_price', $totalPrice);
                $orderStatement->execute();
                $orderId = $conn->lastInsertId();
    
                // Store each product in order_items table
                foreach ($cartItems as $item) {
                    $orderItemStatement = $conn->prepare('INSERT INTO order_items (order_id, product_id, variant_id, product_name, quantity, price, main_image_url) VALUES (:order_id, :product_id, :variant_id, :product_name, :quantity, :price, :main_image_url)');
                    $orderItemStatement->bindValue(':order_id', $orderId);
                    $orderItemStatement->bindValue(':product_id', $item['id']);
                    $orderItemStatement->bindValue(':variant_id', $item['variant_id']); // Handle variant ID
                    $orderItemStatement->bindValue(':product_name', $item['name']);
                    $orderItemStatement->bindValue(':quantity', $item['quantity']);
                    $orderItemStatement->bindValue(':price', $item['price']);
                    $orderItemStatement->bindValue(':main_image_url', $item['image']);
                    $orderItemStatement->execute();
                }
    
                // Clear user's cart from the database (if applicable)
                $clearCartStatement = $conn->prepare('DELETE FROM cart WHERE user_id = :user_id');
                $clearCartStatement->bindValue(':user_id', $userId);
                $clearCartStatement->execute();
    
                // Clear user's cart from the session
                unset($_SESSION['cart']);
    
                // Commit transaction
                $conn->commit();
    
                // Update session currency
                $_SESSION['currency'] = $newBalance;
    
                return $orderId;
            } catch (PDOException $e) {
                // Rollback transaction if something goes wrong
                $conn->rollBack();
                return false;
            }
        } else {
            return false;
        }
    }
    
       
}

?>