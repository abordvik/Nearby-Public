<?php
 header('Access-Control-Allow-Origin: *');  


require_once("functions.php");
$username = $_GET["username"];
$password = $_GET["password"];
$nearby = new nearby();
$nearby -> login($username, $password);
// header('Location: http://dev1.intelli.dk'); 
?>

