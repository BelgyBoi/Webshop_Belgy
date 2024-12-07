<?php
require 'classes/auth.php';
requireAdmin();
require 'vendor/autoload.php';

use WebshopBelgy\Database;
use WebshopBelgy\ProductFetcher;

$conn = Database::getConnection();
$productFetcher = new ProductFetcher($conn);

$product = null;
$variant = null;
$productImages = [];
$variantImages = [];
$isVariant = false;

if (isset($_GET['variant_id']) && $conn) {
    $variant = $productFetcher->getVariantById($_GET['variant_id']);
    $variantImages = $productFetcher->getVariantImages($_GET['variant_id']);
    $product = $productFetcher->getProductById($variant['product_id']);
    $productImages = $productFetcher->getProductImages($variant['product_id']);
    $isVariant = true;
} elseif (isset($_GET['id']) && $conn) {
    $product = $productFetcher->getProductById($_GET['id']);
    $productImages = $productFetcher->getProductImages($_GET['id']);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit <?= $isVariant ? 'Variant' : 'Product' ?></title>
    <link rel="stylesheet" href="css/admin.css">
</head>
<body>
    <h1>Edit <?= $isVariant ? 'Variant' : 'Product' ?></h1>

    <!-- Dashboard Button -->
    <nav class="dashboard">
        <a href="admin.php" class="button">Dashboard</a>
    </nav>

    <?php if ($product || $variant) : ?>
    <form id="edit-form" method="POST" enctype="multipart/form-data" action="update_item.php">
        <input type="hidden" name="id" value="<?= htmlspecialchars($isVariant ? $variant['id'] : $product['id']) ?>">
        <input type="hidden" name="type" value="<?= htmlspecialchars($isVariant ? 'variant' : 'product') ?>">
        <label>Name: <input type="text" name="name" value="<?= htmlspecialchars($isVariant ? $variant['variant_name'] : $product['name']) ?>" required></label><br>
        <label>Price: <input type="number" name="price" value="<?= htmlspecialchars($isVariant ? $variant['price'] : $product['price']) ?>" required></label><br>
        <label>Description: <textarea class="description" name="description" required><?= htmlspecialchars($product['description']) ?></textarea></label><br>

        <!-- Main Image for Product only -->
        <?php if (!$isVariant): ?>
        <label>Main Image: 
            <input type="file" name="main_image" id="main_image">
            <input type="hidden" name="existing_main_image_url" value="<?= htmlspecialchars($product['main_image_url']) ?>">
            <?php if ($product['main_image_url']) : ?>
                <img src="<?= htmlspecialchars($product['main_image_url']) ?>" alt="Main Image" style="max-width: 200px; margin-top: 10px;">
            <?php endif; ?>
        </label><br>
        <?php endif; ?>

        <div id="edit-additional-images-container">
    <label>Additional Images:</label>
    <?php 
    $images = $isVariant ? $variantImages : $productImages;
    if (empty($images)) {
        echo "<p>No additional images available</p>";
    } else {
        foreach ($images as $index => $image) : ?>
            <div class="additional-image-wrapper">
                <img src="<?= htmlspecialchars($image['image_url']) ?>" alt="Product Image" style="max-width: 100px; max-height: 100px;">
                <input type="hidden" name="existing_images[<?= $index ?>]" value="<?= htmlspecialchars($image['image_url']) ?>">
                <input type="hidden" name="existing_image_ids[<?= $index ?>]" value="<?= htmlspecialchars($image['id']) ?>">
                <input type="file" name="replace_images[<?= $index ?>]" class="choose-file-input">
                <button type="button" class="remove-image-btn custom-remove-image-btn">x</button>
            </div>
        <?php endforeach; 
    } ?>
</div>


            <!-- File input for uploading new images -->
            <!-- <div class="additional-image-wrapper">
                <input type="file" name="additional_images[]" class="choose-file-input new-image-input" id="new-image-input">
                <button type="button" class="remove-image-btn custom-remove-image-btn">x</button>
            </div>
        </div> -->

        <button type="submit">Update <?= $isVariant ? 'Variant' : 'Product' ?></button>
    </form>
    <?php else : ?>
    <p><?= $isVariant ? 'Variant' : 'Product' ?> not found.</p>
    <?php endif; ?>

    <script>
document.addEventListener("DOMContentLoaded", function () {
    const container = document.getElementById("edit-additional-images-container");
    let imageIndex = document.querySelectorAll('.additional-image-wrapper').length;
    const form = document.getElementById("edit-form");

    if (!form) {
        console.error("Form not found!");
        return;
    }

    // Function to add new image input
    function addImageInput() {
        const wrapper = document.createElement("div");
        wrapper.classList.add("additional-image-wrapper");

        const uniqueId = `new-image-${imageIndex}`;
        wrapper.innerHTML = `
            <input type="file" name="additional_images[]" class="choose-file-input new-image-input" id="${uniqueId}" accept="image/*">
            <button type="button" class="remove-image-btn custom-remove-image-btn">x</button>
        `;

        container.appendChild(wrapper);

        // Event listener for the remove button
        const removeButton = wrapper.querySelector(".remove-image-btn.custom-remove-image-btn");
        removeButton.addEventListener("click", function () {
            // If the image is an existing one, mark it for removal
            const hiddenInput = wrapper.querySelector('input[type="hidden"][name^="existing_image_ids"]');
            if (hiddenInput) {
                const removeInput = document.createElement('input');
                removeInput.type = 'hidden';
                removeInput.name = 'remove_images[]';
                removeInput.value = hiddenInput.value;
                form.appendChild(removeInput);

                // Log the removal action
                console.log(`Marked for removal: ${hiddenInput.value}`);
            }

            wrapper.remove(); // Remove the wrapper
        });

        imageIndex++; // Increment the index
    }

    // Add initial empty input for new images
    addImageInput();

    // Event listener for dynamically adding new inputs
    container.addEventListener("change", function (event) {
        if (event.target.classList.contains("new-image-input")) {
            const fileInput = event.target;
            if (fileInput.files && fileInput.files.length > 0) {
                addImageInput(); // Add another input after selecting a file
            }
        }
    });

    // Remove existing image handler
    container.addEventListener("click", function (event) {
        if (event.target.classList.contains("remove-image-btn") && event.target.classList.contains("custom-remove-image-btn")) {
            const wrapper = event.target.closest(".additional-image-wrapper");

            // If the image is an existing one, mark it for removal
            const hiddenInput = wrapper.querySelector('input[type="hidden"][name^="existing_image_ids"]');
            if (hiddenInput) {
                const removeInput = document.createElement('input');
                removeInput.type = 'hidden';
                removeInput.name = 'remove_images[]';
                removeInput.value = hiddenInput.value;
                form.appendChild(removeInput);

                // Log the removal action
                console.log(`Marked for removal: ${hiddenInput.value}`);
            }

            wrapper.remove(); // Remove the wrapper
        }
    });

    // Validate form before submission (optional)
    form.addEventListener("submit", function (event) {
        // Log form submission for debugging
        console.log("Form is being submitted with the following removal inputs:");
        document.querySelectorAll('input[name="remove_images[]"]').forEach(input => {
            console.log(`Removing: ${input.value}`);
        });
    });
});

    </script>
</body>
</html>
