<?php
require 'classes/auth.php';
requireAdmin();
require 'vendor/autoload.php';

use WebshopBelgy\Database;

$conn = Database::getConnection();

if ($conn && isset($_GET['id'])) {
    $id = $_GET['id'];
    $type = $_GET['type'] ?? 'product'; // Default to 'product' if type is not set

    try {
        // Start transaction
        $conn->beginTransaction();

        if ($type === 'variant') {
            // Delete related images from variant_images
            $deleteImagesStmt = $conn->prepare('DELETE FROM variant_images WHERE variant_id = :id');
            $deleteImagesStmt->bindValue(':id', $id, PDO::PARAM_INT);
            $deleteImagesStmt->execute();

            // Delete variant from product_variants
            $deleteVariantStmt = $conn->prepare('DELETE FROM product_variants WHERE id = :id');
            $deleteVariantStmt->bindValue(':id', $id, PDO::PARAM_INT);
            $deleteVariantStmt->execute();
        } else {
            // Delete related images from product_images
            $deleteImagesStmt = $conn->prepare('DELETE FROM product_images WHERE product_id = :id');
            $deleteImagesStmt->bindValue(':id', $id, PDO::PARAM_INT);
            $deleteImagesStmt->execute();

            // Delete product from products
            $deleteProductStmt = $conn->prepare('DELETE FROM products WHERE id = :id');
            $deleteProductStmt->bindValue(':id', $id, PDO::PARAM_INT);
            $deleteProductStmt->execute();
        }

        // Commit transaction
        $conn->commit();

        header('Location: admin.php');
        exit();
    } catch (PDOException $e) {
        // Rollback transaction if something went wrong
        $conn->rollBack();
        echo 'Error deleting ' . $type . ': ' . $e->getMessage();
    }
} else {
    echo 'Invalid ID or database connection failed';
}
?>
