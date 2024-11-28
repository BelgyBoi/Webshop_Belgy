<?php
require 'vendor/autoload.php';

use WebshopBelgy\Database;

session_start();

$email = '';
$first_name = '';
$last_name = '';
$password = '';
$confirm_password = '';
$error = '';

if (!empty($_POST)) { 
    $email = $_POST['email'];
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if (!empty($email) && !empty($first_name) && !empty($last_name) && !empty($password) && !empty($confirm_password)) {
        if ($password === $confirm_password) {
            $conn = Database::getConnection();
            
            // Check if the email already exists
            $statement = $conn->prepare('SELECT * FROM accounts WHERE email_address = :email');
            $statement->bindValue(':email', $email);
            $statement->execute();
            $existingUser = $statement->fetch(PDO::FETCH_ASSOC);

            if ($existingUser) {
                $error = 'An account with this email address already exists';
            } else {
                $options = ['cost' => 12];
                $hash = password_hash($password, PASSWORD_DEFAULT, $options);

                $statement = $conn->prepare('INSERT INTO accounts (email_address, Firstname, Lastname, password, currency) VALUES (:email, :first_name, :last_name, :password, 1000)');
                $statement->bindValue(':email', $email);
                $statement->bindValue(':first_name', $first_name);
                $statement->bindValue(':last_name', $last_name);
                $statement->bindValue(':password', $hash);
                $statement->execute();

                // Set session variables
                $_SESSION['loggedin'] = true;
                $_SESSION['email'] = $email;
                $_SESSION['firstname'] = $first_name;
                $_SESSION['lastname'] = $last_name;
                $_SESSION['currency'] = 1000;

                header('Location: login.php');
                exit();  
            }
        } else {
            $error = 'Passwords do not match';
        }
    } else {
        $error = 'Please fill in all fields';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <link rel="stylesheet" href="css/login.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="css/icon-signup.css">
</head>
<body>
<div class="WebshopLogin">
    <div class="form form--login">
        <form action="" method="post">
            <h2 form__title>Sign Up</h2>
            <?php if (isset($error)): ?>
            <div class="form__error">
                <p>
                    <?php echo $error; ?>
                </p>
            </div>
            <?php endif; ?>
            <div class="form__field">
                <label for="Email">Email</label>
                <input class="inpField" type="text" name="email" value="<?php echo htmlspecialchars($email); ?>">
            </div>
            <div class="form__field">
                <label for="first_name">First name</label>
                <input class="inpField" type="text" name="first_name" value="<?php echo htmlspecialchars($first_name); ?>">
            </div>
            <div class="form__field">
                <label for="last_name">Last name</label>
                <input class="inpField" type="text" name="last_name" value="<?php echo htmlspecialchars($last_name); ?>">
            </div> 
            <div class="form__field">
                <label for="Password">Password</label>
                <input class="inpField" type="password" name="password" id="password" required value="<?php echo htmlspecialchars($password); ?>">
                <i class="far fa-eye icon" id="togglePassword1"></i>
            </div>
            <div class="form__field">
                <label id="confirm" for="ConfirmPassword"> Confirm password</label>
                <input class="inpField" type="password" name="confirm_password" id="confirm_password" required value="<?php echo htmlspecialchars($confirm_password); ?>">
                <i class="far fa-eye boo" id="togglePassword2"></i>
            </div>

            <div class="form__field">
                <input id="button" type="submit" value="Sign up" class="btn btn--primary"> 
            </div>
        </form>
    </div>
    <a id="signUpLink" href="login.php">Already have an account? Login instead</a>
</div>

<script src="js/signup.js"></script>

</body>
</html>
