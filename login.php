<?php
require 'vendor/autoload.php';
use WebshopBelgy\Database;

session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

function canLogin($p_email, $p_password){
    $conn = Database::getConnection();
    if ($conn) {
        $statement = $conn->prepare('SELECT * FROM accounts WHERE email_address = :email');
        $statement->bindValue(':email', $p_email);
        $statement->execute();
        
        $user = $statement->fetch(PDO::FETCH_ASSOC);
        if($user){
            $hash = $user['password'];
            if(password_verify($p_password, $hash)){
                // Store user details in session
                $_SESSION['loggedin'] = true;
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['email'] = $user['email_address']; // Ensure email is set in session
                $_SESSION['firstname'] = $user['firstname'] ?? 'Unknown';
                $_SESSION['lastname'] = $user['lastname'] ?? 'Unknown';
                $_SESSION['currency'] = $user['currency'] ?? 'EUR';
                $_SESSION['admin'] = $user['admin'];
                return true;
            }
        }
    }
    return false;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    $email = $input['email'];
    $password = $input['password'];

    if (canLogin($email, $password)) {
        $isAdmin = $_SESSION['admin'];
        echo json_encode(['success' => true, 'isAdmin' => $isAdmin]);
    } else {
        echo json_encode(['success' => false]);
    }

    if (isset($input['rememberMe'])) {
        setcookie('email', $email, time() + 60 * 60 * 24 * 30);
        setcookie('password', $password, time() + 60 * 60 * 24 * 30);
    }
    exit();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>IMDFlix</title>
    <link rel="stylesheet" href="css/login.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="css/icon-login.css">
</head>
<body class="container">
    <div class="WebshopLogin">
        <div class="form form--login">
            <form action="" method="post">
                <h2 form__title>Sign In</h2>

                <?php if( isset($error) ): ?>
                <div class="form__error">
                    <p>
                        Sorry, we can't log you in with that email address and password. Can you try again?
                    </p>
                </div>
                <?php endif; ?>
                <div class="form__field">
                    <label for="Email">Email</label>
                    <input class="inpField" type="text" name="email" required>
                </div>
                <div class="form__field">
                    <label for="Password">Password</label>
                    <input  class="inpField" type="password" name="password" id="current_password" required>
                    <i class="far fa-eye icon" id="togglePassword"></i>
                </div>

                <div class="form__field">
                    <input id="button" type="submit" value="Sign in" class="btn btn--primary">    
                    <div id="rememberMe">
                        <input type="checkbox" ><label for="rememberMe" class="label__inline">Remember me</label>
                    </div>
                </div>
            </form>
        </div>
        <a id="signUpLink" href="signup.php">Don't have an account yet? Sing up here</a>
    </div>

    <script src="js/login.js"></script>
</body>
</html>
