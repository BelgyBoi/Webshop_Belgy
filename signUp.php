<?php
if(!empty($_POST)){
    $email = $_POST['email'];
    $password = $_POST['password'];

    $options = [
        'cost' => 12,
    ];
    $hash = password_hash($password, PASSWORD_DEFAULT, $options);

    $conn = new PDO('mysql:host=localhost;dbname=webshop_belgy', 'root', '');
    $statement = $conn -> prepare('INSERT INTO users (email, password) VALUES (:email, :password)');
    $statement->bindValue(':email', $email);
    $statement->bindValue(':password', $hash);
    $statement->execute();
}
?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    
</body>
</html>