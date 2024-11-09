<?php

	function canLogin($p_email, $p_password){
		$conn = new PDO('mysql:host=localhost;dbname=webshop_belgy', 'root', '');
		$statement = $conn -> prepare('SELECT * FROM users WHERE email = :email)');
		$statement->bindValue(':email', $p_email);

		$user = $statement->fetch(PDO::FETCH_ASSOC);
		if($user){
			$hash = $user['password'];
			if(password_verify ($p_password, $hash)){
				return true;
			}
		} else {
			//not found
			return false;
		}
	}

	// wanneer gaan we pas inloggen
	if(!empty($_POST)){
		$email = $_POST['email'];
		$password = $_POST['password'];

		if(canLogin($email, $password)){
			session_start();
			$_SESSION['loggedin'] = true;
			$_SESSION['email'] = $email;
		} else {
			$error = true;
		}
	}

?><!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>IMDFlix</title>
  <link rel="stylesheet" href="css/login.css">
  <link rel="stylesheet" href="css/style.css">
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
						<input class="inpField" type="text" name="email">
					</div>
					<div class="form__field">
						<label for="Password">Password</label>
						<input class="inpField" type="password" name="password">
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
</body>
</html>