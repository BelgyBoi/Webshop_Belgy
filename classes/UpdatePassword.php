<?php
namespace WebshopBelgy;

error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
require '../vendor/autoload.php';

use WebshopBelgy\Database;

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.php');
    exit();
}

class UpdatePassword {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function update($email, $new_password, $confirm_password) {
        if ($new_password == $confirm_password) {
            $hash = password_hash($new_password, PASSWORD_DEFAULT);
            $statement = $this->conn->prepare('UPDATE accounts SET password = :password WHERE email_address = :email');
            $statement->bindValue(':password', $hash);
            $statement->bindValue(':email', $email);
            $statement->execute();
            return ['status' => 'success', 'message' => 'Password updated successfully!'];
        } else {
            return ['status' => 'error', 'message' => 'New passwords do not match!'];
        }
    }
}

$conn = Database::getConnection();
$email = $_SESSION['email'];
$new_password = $_POST['new_password'];
$confirm_password = $_POST['confirm_password'];

$updatePassword = new UpdatePassword($conn);
$response = $updatePassword->update($email, $new_password, $confirm_password);

echo json_encode($response);
?>
