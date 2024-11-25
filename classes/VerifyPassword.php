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

class VerifyPassword {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function verify($email, $current_password) {
        $statement = $this->conn->prepare('SELECT * FROM accounts WHERE email_address = :email');
        $statement->bindValue(':email', $email);
        $statement->execute();
        $user = $statement->fetch(\PDO::FETCH_ASSOC);

        if ($user && password_verify($current_password, $user['password'])) {
            return ['status' => 'success', 'message' => 'Current password verified!'];
        } else {
            return ['status' => 'error', 'message' => 'Current password is incorrect!'];
        }
    }
}

$conn = Database::getConnection();
$email = $_SESSION['email'];
$current_password = $_POST['current_password'];

$verifyPassword = new VerifyPassword($conn);
$response = $verifyPassword->verify($email, $current_password);

echo json_encode($response);
?>
