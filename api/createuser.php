<?php
/**
 * this file will create a new user from the input given
 */

require("../includes.php");

$return = array();
$email = $_POST['email'];
$password = $_POST['password'];
$name = $_POST['name'];

$user = User::createUser($email, $password, $name, 0,0,0,0,0,0,0,0,0,0,0);

if ($user === false) {
    $return['errors'] = "A user with that email already exist";
} else {
    $return['errors'] = "";
    $_SESSION['user'] = $user;
}

echo json_encode($return);
?>

