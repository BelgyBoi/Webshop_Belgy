<?php
require 'classes/auth.php';
requireAdmin();
require 'vendor/autoload.php';

use WebshopBelgy\Database;
use WebshopBelgy\ProductFetcher;

$conn = Database::getConnection();
$product = null;

if (isset($_GET['id']) && $conn) {
    $productFetcher = new ProductFetcher($conn);
    $product = $productFetcher->getProductById($_GET['id']);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && $conn) {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $price = $_POST['price'];
    $description = $_POST['description'];
    $main_image_url = $_POST['main_image_url'];

    $statement = $conn->prepare('UPDATE products SET name = :name, price = :price, description = :description, main_image_url = :main_image_url WHERE id = :id');
    $statement->bindValue(':id', $id);
    $statement->bindValue(':name', $name);
    $statement->bindValue(':price', $price);
    $statement->bindValue(':description', $description);
    $statement->bindValue(':main_image_url', $main_image_url);

    if ($statement->execute()) {
        header('Location: admin.php');
        exit();
    } else {
        echo 'Error updating product';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product</title>
    <link rel="stylesheet" href="css/admin.css">
</head>
<body>
    <h1>Edit Product</h1>
    
    <!-- Dashboard Button -->
    <nav class="dashboard">
        <a href="admin.php" class="button">Dashboard</a>
    </nav>

    <?php if ($product) : ?>
    <form method="POST">
        <input type="hidden" name="id" value="<?= htmlspecialchars($product['id']) ?>">
        <label>Name: <input type="text" name="name" value="<?= htmlspecialchars($product['name']) ?>" required></label><br>
        <label>Price: <input type="number" name="price" value="<?= htmlspecialchars($product['price']) ?>" required></label><br>
        <label>Description: <textarea class="description" name="description" required><?= htmlspecialchars($product['description']) ?></textarea></label><br>
        <label>Main Image URL: <input type="text" name="main_image_url" value="<?= htmlspecialchars($product['main_image_url']) ?>" required></label><br>
        <button type="submit">Update Product</button>
    </form>
    <?php else : ?>
    <p>Product not found.</p>
    <?php endif; ?>
</body>
</html>
