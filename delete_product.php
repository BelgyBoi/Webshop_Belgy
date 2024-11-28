<?php
require 'classes/auth.php';
requireAdmin();
require 'vendor/autoload.php';

use WebshopBelgy\Database;

$conn = Database::getConnection();

if ($conn && isset($_GET['id'])) {
    $productId = $_GET['id'];

    try {
        // Start transaction
        $conn->beginTransaction();

        // Delete related images from product_images
        $deleteImagesStmt = $conn->prepare('DELETE FROM product_images WHERE product_id = :product_id');
        $deleteImagesStmt->bindValue(':product_id', $productId, PDO::PARAM_INT);
        $deleteImagesStmt->execute();

        // Delete product from products
        $deleteProductStmt = $conn->prepare('DELETE FROM products WHERE id = :id');
        $deleteProductStmt->bindValue(':id', $productId, PDO::PARAM_INT);
        $deleteProductStmt->execute();

        // Commit transaction
        $conn->commit();

        header('Location: admin.php');
        exit();
    } catch (PDOException $e) {
        // Rollback transaction if something went wrong
        $conn->rollBack();
        echo 'Error deleting product: ' . $e->getMessage();
    }
} else {
    echo 'Invalid product ID or database connection failed';
}
?>
