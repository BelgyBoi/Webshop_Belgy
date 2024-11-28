<?php
require 'classes/auth.php';
requireAdmin();
require 'vendor/autoload.php';

use WebshopBelgy\Database;
use WebshopBelgy\CategoryFetcher;

$conn = Database::getConnection();
$categories = [];

if ($conn) {
    $categoryFetcher = new CategoryFetcher($conn);
    $categories = $categoryFetcher->getCategories();

    if (empty($categories)) {
        error_log('No categories found');
    } else {
        error_log('Categories loaded: ' . print_r($categories, true));
    }
} else {
    error_log('Connection failed');
}

$errors = []; // Assuming you have error handling somewhere

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $price = $_POST['price'] ?? '';
    $description = $_POST['description'] ?? '';
    $main_image_url = $_POST['main_image_url'] ?? '';
    $additional_images = $_POST['additional_images'] ?? '';
    $category_id = $_POST['category_id'] ?? '';

    // Validation
    if (empty($name)) $errors[] = 'Name is required';
    if (empty($price)) $errors[] = 'Price is required';
    if (empty($description)) $errors[] = 'Description is required';
    if (empty($main_image_url)) $errors[] = 'Main image URL is required';
    if (empty($category_id)) $errors[] = 'Category is required';

    if (empty($errors)) {
        // Insert into products table
        $statement = $conn->prepare('INSERT INTO products (name, price, description, main_image_url, category_id) VALUES (:name, :price, :description, :main_image_url, :category_id)');
        $statement->bindValue(':name', $name);
        $statement->bindValue(':price', $price);
        $statement->bindValue(':description', $description);
        $statement->bindValue(':main_image_url', $main_image_url);
        $statement->bindValue(':category_id', $category_id);

        if ($statement->execute()) {
            // Get the last inserted product ID
            $product_id = $conn->lastInsertId();

            // Insert additional images into product_images table
            $additional_images_array = explode(',', $additional_images);
            foreach ($additional_images_array as $image_url) {
                $image_url = trim($image_url);
                if (!empty($image_url)) {
                    $image_statement = $conn->prepare('INSERT INTO product_images (product_id, image_url) VALUES (:product_id, :image_url)');
                    $image_statement->bindValue(':product_id', $product_id);
                    $image_statement->bindValue(':image_url', $image_url);
                    $image_statement->execute();
                }
            }

            // Redirect to admin.php after successful addition
            header('Location: admin.php');
            exit();
        } else {
            $errors[] = 'Failed to add product';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product</title>
    <link rel="stylesheet" href="css/admin.css">
</head>
<body>
    <h1>Add New Product</h1>
    
    <!-- Dashboard Button -->
    <nav class="dashboard">
        <a href="admin.php" class="button">Dashboard</a>
    </nav>
    
    <?php if (!empty($errors)) : ?>
        <div class="errors">
            <ul>
                <?php foreach ($errors as $error) : ?>
                    <li><?php echo htmlspecialchars($error); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>
    
    <form method="POST" action="add_product.php">
        <label>Name: <input type="text" name="name" value="<?= htmlspecialchars($_POST['name'] ?? '') ?>" required></label><br>
        <label>Price: <input type="number" name="price" value="<?= htmlspecialchars($_POST['price'] ?? '') ?>" required></label><br>
        <label>Description: <textarea class="description" name="description" required><?= htmlspecialchars($_POST['description'] ?? '') ?></textarea></label><br>
        <label>Main Image URL: <input type="text" name="main_image_url" value="<?= htmlspecialchars($_POST['main_image_url'] ?? '') ?>" required></label><br>
        <label>Additional Images (comma-separated URLs): <textarea class="more_urls" name="additional_images" rows="3"><?= htmlspecialchars($_POST['additional_images'] ?? '') ?></textarea></label><br>
        <label>Category:
            <select name="category_id" required>
                <?php foreach ($categories as $category) : ?>
                    <option value="<?= htmlspecialchars($category['id']) ?>" <?= (isset($_POST['category_id']) && $_POST['category_id'] == $category['id']) ? 'selected' : '' ?>><?= htmlspecialchars($category['name']) ?></option>
                <?php endforeach; ?>
            </select>
        </label><br>
        <button type="submit">Add Product</button>
    </form>
</body>
</html>
