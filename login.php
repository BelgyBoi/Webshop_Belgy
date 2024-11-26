<?php

require 'vendor/autoload.php';

use WebshopBelgy\Database;

session_start();

function canLogin($p_email, $p_password) {
    $conn = Database::getConnection();
    if ($conn) {
        $statement = $conn->prepare('SELECT * FROM accounts WHERE email_address = :email');
        $statement->bindValue(':email', $p_email);
        $statement->execute();
        
        $user = $statement->fetch(PDO::FETCH_ASSOC);
        if($user){
            $hash = $user['password'];
            if(password_verify($p_password, $hash)){
                return $user; // Return user data on successful login
            }
        }
    }
    return false;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') { 
    $email = $_POST['email']; 
    $password = $_POST['password']; 

    $user = canLogin($email, $password); // Get user data

    if ($user) { 
        $_SESSION['loggedin'] = true; 
        $_SESSION['user_id'] = $user['id']; // Store user_id in session
        $_SESSION['firstname'] = $user['firstname']; // Store firstname in session
        $_SESSION['lastname'] = $user['lastname']; // Store lastname in session
        $_SESSION['email'] = $user['email_address']; 

        header('Location: index.php'); 
        exit(); 
    } else { 
        $error = true; 
    }

    if (isset($_POST['rememberMe'])) { 
        setcookie('email', $email, time() + 60 * 60 * 24 * 30); 
        setcookie('password', $password, time() + 60 * 60 * 24 * 30);
    }
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
        <a id="signUpLink" href="signUp.php">Don't have an account yet? Sing up here</a>
    </div>

    <script src="js/login.js"></script>
</body>
</html>
