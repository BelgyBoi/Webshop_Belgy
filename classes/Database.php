<?php

namespace WebshopBelgy;

class Database {
    public static function getConnection() {
        try {
            $conn = new \PDO('mysql:host=localhost;dbname=webshop_belgy', 'root', '');
            // Set the PDO error mode to exception
            $conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            return $conn;
        } catch(\PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
            return null;
        }
    }
}

