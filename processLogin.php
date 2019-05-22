<?php 

if($_SERVER['REQUEST_METHOD'] !== 'POST'){

    die();

}

if(!isset($_POST['username'])){

    die();

}

if(!isset($_POST['password'])){

    die();

}


require('conf.php');

$username = $_POST['username'];
$password = $_POST['password'];

$qry = '
SELECT id, password, is_admin
FROM users 
WHERE username = :username
';
$getUser = $conn->prepare($qry);
$getUser->execute([
    'username' => $username
]);
$result = $getUser->fetch(PDO::FETCH_OBJ);

if(!$result){

    die();

} 


if(!password_verify($password, $result->password)){


    die();


} else {

    $_SESSION['uid'] = $result->id;
    $_SESSION['uname'] = $username;
    $_SESSION['admin'] = $result->is_admin;

    echo 1;

}

