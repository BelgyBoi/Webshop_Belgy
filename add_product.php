<?php
require 'classes/auth.php';
requireAdmin();
require 'vendor/autoload.php';

use WebshopBelgy\Database;
use WebshopBelgy\CategoryFetcher;
use WebshopBelgy\ProductFetcher;

$conn = Database::getConnection();
$categories = [];
$products = [];

if ($conn) {
    $categoryFetcher = new CategoryFetcher($conn);
    $categories = $categoryFetcher->getCategories();

    $productFetcher = new ProductFetcher($conn);
    $products = $productFetcher->getAllProducts();

    if (empty($categories)) {
        error_log('No categories found');
    } else {
        error_log('Categories loaded: ' . print_r($categories, true));
    }
} else {
    error_log('Connection failed');
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $price = $_POST['price'] ?? '';
    $description = $_POST['description'] ?? '';
    $category_id = $_POST['category_id'] ?? '';
    $product_type = $_POST['product_type'] ?? 'product';
    $original_product_id = $_POST['original_product_id'] ?? null;
    $main_image_url = '';

    // Validation
    if (empty($name)) $errors[] = 'Name is required';
    if (empty($price)) $errors[] = 'Price is required';
    if (empty($description)) $errors[] = 'Description is required';
    if (empty($category_id)) $errors[] = 'Category is required';

    // Handle main image upload
    if ($product_type === 'product' && isset($_FILES['main_image']) && $_FILES['main_image']['error'] === 0) {
        $target_dir = "assets/";
        $target_file = $target_dir . basename($_FILES["main_image"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Check if image file is an actual image or fake image
        $check = getimagesize($_FILES["main_image"]["tmp_name"]);
        if ($check !== false) {
            if (move_uploaded_file($_FILES["main_image"]["tmp_name"], $target_file)) {
                $main_image_url = $target_file;
            } else {
                $errors[] = "Sorry, there was an error uploading your file.";
            }
        } else {
            $errors[] = "File is not an image.";
        }
    }

    if (empty($errors)) {
        if ($product_type === 'variant' && $original_product_id) {
            // Insert variant
            $statement = $conn->prepare('INSERT INTO product_variants (product_id, variant_name, price) VALUES (:product_id, :variant_name, :price)');
            $statement->bindValue(':product_id', $original_product_id);
            $statement->bindValue(':variant_name', $name);
            $statement->bindValue(':price', $price);
            if ($statement->execute()) {
                $variant_id = $conn->lastInsertId();

                // Handle additional images for the variant
                if (isset($_FILES['additional_images']) && !empty($_FILES['additional_images']['name'][0])) {
                    $additional_images = $_FILES['additional_images'];
                    foreach ($additional_images['name'] as $index => $filename) {
                        $tmp_name = $additional_images['tmp_name'][$index];
                        $target_file = $target_dir . basename($filename);
                        if (move_uploaded_file($tmp_name, $target_file)) {
                            $image_url = $target_file;
                            $stmt = $conn->prepare('INSERT INTO variant_images (variant_id, image_url) VALUES (:variant_id, :image_url)');
                            $stmt->bindValue(':variant_id', $variant_id);
                            $stmt->bindValue(':image_url', $image_url);
                            $stmt->execute();
                        }
                    }
                }

                header('Location: admin.php');
                exit();
            } else {
                $errors[] = 'Failed to add variant';
            }
        } else {
            // Insert into products table
            $statement = $conn->prepare('INSERT INTO products (name, price, description, main_image_url, category_id) VALUES (:name, :price, :description, :main_image_url, :category_id)');
            $statement->bindValue(':name', $name);
            $statement->bindValue(':price', $price);
            $statement->bindValue(':description', $description);
            $statement->bindValue(':main_image_url', $main_image_url);
            $statement->bindValue(':category_id', $category_id);

            if ($statement->execute()) {
                $product_id = $conn->lastInsertId();

                // Handle additional images for the product
                if (isset($_FILES['additional_images']) && !empty($_FILES['additional_images']['name'][0])) {
                    $additional_images = $_FILES['additional_images'];
                    foreach ($additional_images['name'] as $index => $filename) {
                        $tmp_name = $additional_images['tmp_name'][$index];
                        $target_file = $target_dir . basename($filename);
                        if (move_uploaded_file($tmp_name, $target_file)) {
                            $image_url = $target_file;
                            $stmt = $conn->prepare('INSERT INTO product_images (product_id, image_url) VALUES (:product_id, :image_url)');
                            $stmt->bindValue(':product_id', $product_id);
                            $stmt->bindValue(':image_url', $image_url);
                            $stmt->execute();
                        }
                    }
                }

                header('Location: admin.php');
                exit();
            } else {
                $errors[] = 'Failed to add product';
            }
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

    <form method="POST" action="add_product.php" enctype="multipart/form-data">
        <label>
            <input type="radio" name="product_type" value="product" checked> Product
        </label>
        <label>
            <input type="radio" name="product_type" value="variant"> Variant
        </label><br>

        <div id="variant-section" style="display: none;">
            <label>Original Product:
                <select name="original_product_id" id="original-product-id">
                    <option value="">Select a product</option>
                    <?php foreach ($products as $product) : ?>
                        <option value="<?= $product['id'] ?>"><?= $product['name'] ?></option>
                    <?php endforeach; ?>
                </select>
            </label><br>
        </div>

        <label>Name: <input type="text" name="name" id="name" value="<?= htmlspecialchars($_POST['name'] ?? '') ?>" required></label><br>
        <label>Price: <input type="number" name="price" id="price" value="<?= htmlspecialchars($_POST['price'] ?? '') ?>" required></label><br>
        <label>Description: <textarea class="description" name="description" id="description" required><?= htmlspecialchars($_POST['description'] ?? '') ?></textarea></label><br>
        <div id="main-image-url-field">
            <label>Main Image: 
                <input type="file" name="main_image" id="main_image">
                <button type="button" id="clear-main-image-btn" class="clear-image-btn">x</button>
            </label><br>
        </div>
        <div id="additional-images-container">
            <label>Additional Images:</label>
        </div><br>
        <label>Category:
            <select name="category_id" id="category_id" required>
                <?php foreach ($categories as $category) : ?>
                    <option value="<?= htmlspecialchars($category['id']) ?>" <?= (isset($_POST['category_id']) && $_POST['category_id'] == $category['id']) ? 'selected' : '' ?>><?= htmlspecialchars($category['name']) ?></option>
                <?php endforeach; ?>
            </select>
        </label><br>
        <button type="submit">Add Product</button>
    </form>

    <script src="js/admin.js"></script>
</body>
</html>
