<?php
require 'classes/auth.php';
requireAdmin();
require 'vendor/autoload.php';

use WebshopBelgy\Database;
use WebshopBelgy\ProductFetcher;

$conn = Database::getConnection();
$productFetcher = new ProductFetcher($conn);

$id = $_POST['id'];
$type = $_POST['type'];
$name = $_POST['name'];
$price = $_POST['price'];
$description = $_POST['description'] ?? null;
$main_image_url = $_POST['existing_main_image_url'] ?? null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $conn) {
    // Handle the main image
    if (!empty($_FILES['main_image']['name'])) {
        $main_image_url = 'assets/' . basename($_FILES['main_image']['name']);
        move_uploaded_file($_FILES['main_image']['tmp_name'], $main_image_url);
    }

    // Update the product or variant
    if ($type === 'variant') {
        $statement = $conn->prepare('UPDATE product_variants SET 
            variant_name = COALESCE(NULLIF(:name, ""), variant_name), 
            price = COALESCE(NULLIF(:price, ""), price) 
            WHERE id = :id');
    } else {
        $statement = $conn->prepare('UPDATE products SET 
            name = COALESCE(NULLIF(:name, ""), name), 
            price = COALESCE(NULLIF(:price, ""), price), 
            description = COALESCE(NULLIF(:description, ""), description), 
            main_image_url = COALESCE(NULLIF(:main_image_url, ""), main_image_url)
            WHERE id = :id');
        $statement->bindValue(':description', $description);
        $statement->bindValue(':main_image_url', $main_image_url);
    }

    $statement->bindValue(':id', $id);
    $statement->bindValue(':name', $name);
    $statement->bindValue(':price', $price);

    if ($statement->execute()) {
// Handle removal of images
if (isset($_POST['remove_images'])) {
    foreach ($_POST['remove_images'] as $imageId) {
        try {
            // Prepare the correct query for removing the image from the database
            if ($type === 'variant') {
                $query = 'DELETE FROM variant_images WHERE variant_id = :variant_id AND id = :image_id';
                $stmt = $conn->prepare($query);
                $stmt->bindValue(':variant_id', $id);
            } else {
                $query = 'DELETE FROM product_images WHERE product_id = :product_id AND id = :image_id';
                $stmt = $conn->prepare($query);
                $stmt->bindValue(':product_id', $id);
            }

            $stmt->bindValue(':image_id', $imageId);

            // Execute the query
            if ($stmt->execute()) {
                // Optionally delete the image file from the server
                $imageUrl = $_POST['existing_images'][$imageId]; // Get the URL from existing images array
                if (file_exists($imageUrl)) {
                    unlink($imageUrl);
                }
                error_log("Successfully removed image ID $imageId from the database and file system.");
            } else {
                error_log("Failed to remove image ID $imageId from the database: " . implode(", ", $stmt->errorInfo()));
            }
        } catch (Exception $e) {
            error_log("Exception occurred while removing image ID $imageId: " . $e->getMessage());
        }
    }
}

    
        
// Handle new additional images
if (!empty($_FILES['additional_images']['name'][0])) {
    foreach ($_FILES['additional_images']['tmp_name'] as $index => $tmpName) {
        if (!empty($tmpName)) {
            $newImagePath = 'assets/' . basename($_FILES['additional_images']['name'][$index]);
            if (move_uploaded_file($tmpName, $newImagePath)) {
                // Insert the new image into the database (depending on product or variant)
                if ($type === 'variant') {
                    $insertImageStatement = $conn->prepare('INSERT INTO variant_images (variant_id, image_url) VALUES (:variant_id, :image_url)');
                    $insertImageStatement->bindValue(':variant_id', $id);
                } else {
                    $insertImageStatement = $conn->prepare('INSERT INTO product_images (product_id, image_url) VALUES (:product_id, :image_url)');
                    $insertImageStatement->bindValue(':product_id', $id);
                }
                $insertImageStatement->bindValue(':image_url', $newImagePath);
                $insertImageStatement->execute();
            }
        }
    }
}


        // Handle replacement of existing images
        if (isset($_FILES['replace_images']['tmp_name'])) {
            foreach ($_FILES['replace_images']['tmp_name'] as $index => $tmpName) {
                if (!empty($tmpName)) {
                    $replaceImagePath = 'assets/' . basename($_FILES['replace_images']['name'][$index]);
                    error_log("Processing index $index: target path $replaceImagePath");
        
                    if (move_uploaded_file($tmpName, $replaceImagePath)) {
                        // Retrieve ID and URL
                        $imageId = $_POST['existing_image_ids'][$index] ?? null;
                        $imageUrl = $_POST['existing_images'][$index] ?? '';
                        
                        if (!$imageId) {
                            error_log("No image ID found for index $index");
                            continue;
                        }
        
                        error_log("Updating image ID $imageId with path $replaceImagePath");
        
                        // Prepare SQL query
                        $updateQuery = ($type === 'variant')
                            ? 'UPDATE variant_images SET image_url = :image_url WHERE id = :image_id'
                            : 'UPDATE product_images SET image_url = :image_url WHERE id = :image_id';
        
                        $updateStmt = $conn->prepare($updateQuery);
                        $updateStmt->bindValue(':image_id', $imageId);
                        $updateStmt->bindValue(':image_url', $replaceImagePath);
        
                        // Execute query and log result
                        if (!$updateStmt->execute()) {
                            error_log("Failed to execute query for image ID $imageId: " . implode(", ", $updateStmt->errorInfo()));
                        } else {
                            error_log("Successfully updated image ID $imageId with URL $replaceImagePath");
                        }
                    } else {
                        error_log("Failed to move uploaded file for index $index");
                    }
                } else {
                    error_log("No file uploaded for index $index");
                }
            }
        }
        
        
    }

     header('Location: admin.php');
    exit();
} else {
    echo 'Error updating ' . ($type === 'variant' ? 'variant' : 'product');
}

?>
