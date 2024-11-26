<?php
session_start();

require '../vendor/autoload.php';

use WebshopBelgy\Database;

$conn = Database::getConnection();

if ($conn) {
    $rating_id = isset($_POST['rating_id']) ? intval($_POST['rating_id']) : 0;
    $user_id = isset($_SESSION['user_id']) ? intval($_SESSION['user_id']) : 0;

    if ($rating_id > 0 && $user_id > 0) {
        $stmt = $conn->prepare('DELETE FROM ratings WHERE id = :rating_id AND user_id = :user_id');
        $stmt->execute(['rating_id' => $rating_id, 'user_id' => $user_id]);
        
        if ($stmt->rowCount() > 0) {
            echo 'success';
        } else {
            echo 'error';
        }
    } else {
        echo 'error';
    }
} else {
    echo 'error';
}
?>
