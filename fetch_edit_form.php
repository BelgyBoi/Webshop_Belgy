<?php
require 'classes/auth.php';
requireAdmin();
require 'vendor/autoload.php';

use WebshopBelgy\Database;
use WebshopBelgy\ProductFetcher;

$conn = Database::getConnection();
$productFetcher = new ProductFetcher($conn);

$id = $_GET['id'];
$type = $_GET['type'];

if ($type === 'variant') {
    $variant = $productFetcher->getVariantById($id);
    $variantImages = $productFetcher->getVariantImages($id);
    $isVariant = true;
    $item = $variant;
    $images = $variantImages;
} else {
    $product = $productFetcher->getProductById($id);
    $productImages = $productFetcher->getProductImages($id);
    $isVariant = false;
    $item = $product;
    $images = $productImages;
}
?>

<form method="POST" enctype="multipart/form-data" action="update_item.php">
    <input type="hidden" name="id" value="<?= htmlspecialchars($item['id']) ?>">
    <input type="hidden" name="type" value="<?= htmlspecialchars($type) ?>">
    <label>Name: <input type="text" name="name" value="<?= htmlspecialchars($item[$isVariant ? 'variant_name' : 'name']) ?>" required></label><br>
    <label>Price: <input type="number" name="price" value="<?= htmlspecialchars($item['price']) ?>" required></label><br>
    <label>Description: <textarea name="description" required><?= htmlspecialchars($item['description']) ?></textarea></label><br>
    
    <!-- Main Image for Product only -->
    <?php if (!$isVariant): ?>
    <label>Main Image: 
        <input type="file" name="main_image" id="main_image">
        <?php if ($item['main_image_url']) : ?>
            <img src="<?= htmlspecialchars($item['main_image_url']) ?>" alt="Main Image" style="max-width: 200px; margin-top: 10px;">
        <?php endif; ?>
    </label><br>
    <?php endif; ?>

    <div id="edit-additional-images-container">
        <label>Additional Images:</label>
        <?php 
        if (empty($images)) {
            echo "<p>No additional images available</p>";
        } else {
            foreach ($images as $index => $image) : ?>
                <div class="additional-image-wrapper">
                    <img src="<?= htmlspecialchars($image) ?>" alt="Product Image" style="max-width: 100px; max-height: 100px;">
                    <input type="hidden" name="existing_images[<?= $index ?>]" value="<?= htmlspecialchars($image) ?>">
                    <input type="file" name="replace_images[<?= $index ?>]" class="choose-file-input">
                </div>
            <?php endforeach;
        }
        ?>
    </div>

    <?php if ($isVariant): ?>
    <div>
        <label>Variant Images (you can upload new ones):</label>
        <input type="file" name="variant_images[]" multiple><br>
    </div>
    <?php endif; ?>

    <button type="submit">Update <?= $isVariant ? 'Variant' : 'Product' ?></button>
</form>
