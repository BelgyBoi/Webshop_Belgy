<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Log session variables for debugging
error_log('Session Variables in user.php: ' . print_r($_SESSION, true));

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    error_log('User not logged in, redirecting to login.php');
    header('Location: login.php');
    exit();
}

require 'vendor/autoload.php';

use WebshopBelgy\Database;

$conn = Database::getConnection();
$response = [];

if ($conn && isset($_SESSION['email'])) {
    $email = $_SESSION['email'];
    error_log('Email from session: ' . $email); // Debugging line

    $statement = $conn->prepare('SELECT * FROM accounts WHERE email_address = :email');
    $statement->bindValue(':email', $email);
    $statement->execute();
    $user = $statement->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        error_log('User found: ' . print_r($user, true)); // Debugging line
        
        // Correct case sensitivity
        $firstname = $user['Firstname'] ?? 'Unknown';
        $lastname = $user['Lastname'] ?? 'Unknown';
        $_SESSION['currency'] = $user['currency'];

        $response = [
            'success' => true,
            'email' => $email,
            'firstname' => $firstname,
            'lastname' => $lastname,
            'currency' => $_SESSION['currency']
        ];
    } else {
        error_log('User not found for email: ' . $email);
        $response = ['success' => false, 'message' => 'User not found'];
    }
} else {
    error_log('Email not set in session');
    $response = ['success' => false, 'message' => 'Email not set in session'];
}

// Return JSON response only if requested via AJAX
if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
    header('Content-Type: application/json');
    echo json_encode($response);
    exit();
}

// Otherwise, render the HTML page
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
        <p>Currency: <?php echo htmlspecialchars($_SESSION['currency']); ?> BC</p>

        <p id="view_purch">
            <a href="purchaseHistory.php">View Purchase History</a>
        </p> <!-- Added link to purchase history -->

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
