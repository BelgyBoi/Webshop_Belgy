<?php
session_start();

require '../vendor/autoload.php';

use WebshopBelgy\Database;

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.php');
    exit();
}

$conn = Database::getConnection();
$email = $_SESSION['email'];
$newPassword = $_POST['new_password'];
$confirmPassword = $_POST['confirm_password'];

$response = [];

if ($conn && !empty($newPassword) && $newPassword === $confirmPassword) {
    // Hash the new password
    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

    $statement = $conn->prepare('UPDATE accounts SET password = :password WHERE email_address = :email');
    $statement->bindValue(':password', $hashedPassword);
    $statement->bindValue(':email', $email);
    
    if ($statement->execute()) {
        $response = ['status' => 'success', 'message' => 'Password updated successfully'];
    } else {
        $response = ['status' => 'error', 'message' => 'Failed to update password'];
    }
} else {
    $response = ['status' => 'error', 'message' => 'Passwords do not match or new password is empty'];
}

echo json_encode($response);
?>

