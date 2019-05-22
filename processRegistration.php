<?php 

if($_SERVER['REQUEST_METHOD'] !== 'POST'){

    die('not equal post');

}

if(!isset($_POST['username'])){

    die('username not set');

}

if(!isset($_POST['email'])){

    die();

}

if(!isset($_POST['password'])){

    die();

}


require('conf.php');

$userName = $_POST['username'];
$email = $_POST['email'];
$password = $_POST['password'];

$qry = '
SELECT COUNT(id) AS count FROM users 
WHERE username = :username || email = :email
';

$checkIfExists = $conn->prepare($qry);
$checkIfExists->execute([
    'email' => $email, 
    'username' => $userName
]);
$result = $checkIfExists->fetch(PDO::FETCH_OBJ);


if($result->count != 0){

    echo 2;
    die();

}

$password = password_hash($password, PASSWORD_DEFAULT);


$qry = '
INSERT INTO users(username,email,password)
VALUES(:username,:email,:password)
';

$addUser = $conn->prepare($qry);
$addUser->execute([
    'username' => $userName, 
    'email' => $email, 
    'password' => $password
]);
$count = $addUser->rowCount();

if($count){ 

    echo 1;

}