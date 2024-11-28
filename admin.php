<?php
require 'classes/auth.php';
requireAdmin();
require 'vendor/autoload.php';

use WebshopBelgy\Database;
use WebshopBelgy\ProductFetcher;

$conn = Database::getConnection();
$products = [];

if ($conn) {
    $productFetcher = new ProductFetcher($conn);
    
    if (isset($_GET['search'])) {
        $search = $_GET['search'];
        $products = $productFetcher->searchProducts($search);
    } else {
        $products = $productFetcher->getAllProducts();
    }
} else {
    echo 'Connection failed';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Page</title>
    <link rel="stylesheet" href="css/admin.css">
</head>
<body>
    <h1>Admin Panel</h1>

    <!-- Navigation and Logout Button -->
    <nav class="admin_nav">
        <a href="admin.php">Dashboard</a>
        <a href="add_product.php">Add New Product</a>
        <a href="classes/logout.php">Logout</a>
    </nav>
    
    <form method="GET" action="admin.php">
        <input type="text" name="search" placeholder="Search by name or ID" value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
        <button type="submit">Search</button>
    </form>
    
    <table>s
        <thead>
            <tr>
                <th>ID</th>
                <th>Main Image</th>
                <th>Name</th>
                <th>Price</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($products as $product) : ?>
            <tr>
                <td><?= htmlspecialchars($product['id']) ?></td>
                <td><img src="<?= htmlspecialchars($product['main_image_url']) ?>" alt="<?= htmlspecialchars($product['name']) ?>" width="100"></td>
                <td><?= htmlspecialchars($product['name']) ?></td>
                <td><?= htmlspecialchars($product['price']) ?></td>
                <td>
                    <a class="small_button" href="edit_product.php?id=<?= $product['id'] ?>">Edit</a>
                    <a class="small_button" href="delete_product.php?id=<?= $product['id'] ?>">Delete</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
