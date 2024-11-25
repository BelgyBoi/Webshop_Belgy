<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.php');
    exit();
}

require 'vendor/autoload.php';

use WebshopBelgy\Database;

$conn = Database::getConnection();
$email = $_SESSION['email'];
$statement = $conn->prepare('SELECT * FROM accounts WHERE email_address = :email');
$statement->bindValue(':email', $email);
$statement->execute();
$user = $statement->fetch(PDO::FETCH_ASSOC);
$firstname = $user['Firstname'];
$lastname = $user['Lastname'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="css/normalize.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/user.css"> <!-- Include custom CSS -->
</head>
<body>
    <?php include_once("classes/nav.php"); ?>
    <div class="container">
        <h1>User Profile</h1>
        <p>Email: <?php echo htmlspecialchars($email); ?></p>
        <p>First Name: <?php echo htmlspecialchars($firstname); ?></p>
        <p>Last Name: <?php echo htmlspecialchars($lastname); ?></p>

        <div id="form-container">
            <div id="message"></div>
            <form id="password-form">
                <div class="form-group form__field">
                    <label for="current_password">Current Password:</label>
                    <input type="password" id="current_password" name="current_password" class="inpField" required>
                    <i class="far fa-eye icon" id="toggleCurrentPassword"></i>
                </div>
                <div class="form-group form__field">
                    <label for="new_password">New Password:</label>
                    <input type="password" id="new_password" name="new_password" class="inpField" required>
                    <i class="far fa-eye icon" id="toggleNewPassword"></i>
                </div>
                <div class="form-group form__field">
                    <label for="confirm_password">Confirm New Password:</label>
                    <input type="password" id="confirm_password" name="confirm_password" class="inpField" required>
                    <i class="far fa-eye icon" id="toggleConfirmPassword"></i>
                </div>
                <button type="submit" id="update-password-btn">Update Password</button>
            </form>
        </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="js/user.js"></script>
    <script src="js/nav.js"></script>
</body>
</html>
