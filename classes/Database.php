<?php

namespace WebshopBelgy;

use PDO;

class Database {
    private static $conn;

    private static $host = 'localhost';
    private static $db_name = 'webshop_belgy';
    private static $username = 'Belgy';
    private static $password = '&#ccUgH3esDMe4-_e-^Da4i!WB^4vUks';

    public static function getConnection() {
        
        if (self::$conn === null) {
            try {
                self::$conn = new PDO("mysql:host=" . self::$host . ";dbname=" . self::$db_name, self::$username, self::$password);
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
        
        try {
            $conn->beginTransaction();
    
            // Insert order into orders table
            $orderQuery = $conn->prepare("INSERT INTO orders (user_id, total_price) VALUES (:user_id, :total_price)");
            $orderQuery->bindParam(':user_id', $userId, PDO::PARAM_INT);
            $orderQuery->bindParam(':total_price', $totalPrice, PDO::PARAM_STR); // Use PDO::PARAM_STR for decimal values
            $orderQuery->execute();
    
            // Get the last inserted order ID
            $orderId = $conn->lastInsertId();
    
            // Insert order items into order_items table
            $orderItemQuery = $conn->prepare("INSERT INTO order_items (order_id, product_id, variant_id, quantity, price) VALUES (:order_id, :product_id, :variant_id, :quantity, :price)");
    
            foreach ($cartItems as $item) {
                $orderItemQuery->bindParam(':order_id', $orderId, PDO::PARAM_INT);
                $orderItemQuery->bindParam(':product_id', $item['id'], PDO::PARAM_INT);
                $orderItemQuery->bindParam(':variant_id', $item['variant_id'], PDO::PARAM_INT);
                $orderItemQuery->bindParam(':quantity', $item['quantity'], PDO::PARAM_INT);
                $orderItemQuery->bindParam(':price', $item['price'], PDO::PARAM_STR); // Use PDO::PARAM_STR for decimal values
                $orderItemQuery->execute();
            }
    
            // Commit the transaction
            $conn->commit();
    
            return $orderId;
        } catch (Exception $e) {
            // Rollback the transaction in case of error
            $conn->rollBack();
            error_log("Error processing checkout: " . $e->getMessage());
            return false;
        }
    } 
       
}

?>