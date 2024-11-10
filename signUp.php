<?php
include_once 'classes/db.php';

if(!empty($_POST)){
    $email = $_POST['email'];
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $password = $_POST['password'];

    $options = [
        'cost' => 12,
    ];
    $hash = password_hash($password, PASSWORD_DEFAULT, $options);

    $conn = getDBConnection();
    $statement = $conn->prepare('INSERT INTO accounts (email_address, Firstname, Lastname, password) VALUES (:email, :first_name, :last_name, :password)');
    $statement->bindValue(':email', $email);
    $statement->bindValue(':first_name', $first_name);
    $statement->bindValue(':last_name', $last_name);
    $statement->bindValue(':password', $hash);
    $statement->execute();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="css/login.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
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
						<input class="inpField" type="text" name="email">
					</div>
                    <div class="form__field">
						<label for="Email">First name</label>
						<input class="inpField" type="text" name="first_name">
					</div>
                    <div class="form__field">
						<label for="Email">Last name</label>
						<input class="inpField" type="text" name="last_name">
					</div> 
					<div class="form__field">
						<label for="Password">Password</label>
						<input class="inpField" type="password" name="password">
					</div>
	
					<div class="form__field">
						<input id="button" type="submit" value="Sign up" class="btn btn--primary">	
					</div>
				</form>
			</div>
			<a id="signUpLink" href="login.php">Already have an account? Login instead</a>
		</div>
</body>
</html>