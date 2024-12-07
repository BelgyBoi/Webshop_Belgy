<?php
require 'classes/auth.php';
requireAdmin();
require 'vendor/autoload.php';

use WebshopBelgy\Database;
use WebshopBelgy\ProductFetcher;

$conn = Database::getConnection();
$products = [];
$variants = [];

if ($conn) {
    $productFetcher = new ProductFetcher($conn);
    
    if (isset($_GET['search'])) {
        $search = $_GET['search'];
        $products = $productFetcher->searchProducts($search);
        $variants = $productFetcher->searchVariants($search);
    } else {
        $products = $productFetcher->getAllProducts();
        $variants = $productFetcher->getAllVariants();
    }
} else {
    echo 'Connection failed';
}

// Combine products and variants, and sort by product ID, then variant ID
$items = [];

foreach ($products as $product) {
    $items[] = [
        'id' => $product['id'],
        'main_image_url' => $product['main_image_url'],
        'name' => $product['name'],
        'price' => $product['price'],
        'variant_id' => '/',
    ];
}

foreach ($variants as $variant) {
    $items[] = [
        'id' => $variant['product_id'],
        'main_image_url' => $variant['first_image'] ?: '/',
        'name' => $variant['variant_name'],
        'price' => $variant['price'],
        'variant_id' => $variant['id'],
    ];
}

// Sort items by product ID and then by variant ID
usort($items, function($a, $b) {
    if ($a['id'] === $b['id']) {
        return $a['variant_id'] === '/' ? -1 : ($b['variant_id'] === '/' ? 1 : $a['variant_id'] - $b['variant_id']);
    }
    return $a['id'] - $b['id'];
});
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
    
    <h2>Products and Variants</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Main Image</th>
                <th>Name</th>
                <th>Price</th>
                <th>Variant ID</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($items as $item) : ?>
            <tr>
                <td><?= htmlspecialchars($item['id']) ?></td>
                <td>
                    <?php if ($item['main_image_url'] !== '/') : ?>
                        <img src="<?= htmlspecialchars($item['main_image_url']) ?>" alt="<?= htmlspecialchars($item['name']) ?>" width="100">
                    <?php else : ?>
                        /
                    <?php endif; ?>
                </td>
                <td><?= htmlspecialchars($item['name']) ?></td>
                <td><?= htmlspecialchars($item['price']) ?></td>
                <td><?= htmlspecialchars($item['variant_id']) ?></td>
                <td>
                    <?php if ($item['variant_id'] === '/') : ?>
                        <a class="small_button" href="edit_product.php?id=<?= $item['id'] ?>">Edit</a>
                        <a class="small_button" href="delete_product.php?id=<?= $item['id'] ?>&type=product">Delete</a>
                    <?php else : ?>
                        <a class="small_button" href="edit_product.php?variant_id=<?= $item['variant_id'] ?>">Edit</a>
                        <a class="small_button" href="delete_product.php?id=<?= $item['variant_id'] ?>&type=variant">Delete</a>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const editButtons = document.querySelectorAll('.edit-button');
            editButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const id = this.getAttribute('data-id');
                    const type = this.getAttribute('data-type');
                    const container = document.getElementById('edit-form-container');
                    
                    // Fetch form content via AJAX
                    fetch(`fetch_edit_form.php?id=${id}&type=${type}`)
                        .then(response => response.text())
                        .then(data => {
                            container.innerHTML = data;
                        })
                        .catch(error => {
                            console.error('Error fetching edit form:', error);
                        });
                });
            });
        });
    </script>
</body>
</html>
