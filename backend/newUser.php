<?php
require_once("functions.php");
$username = $_POST["username"];
$password = $_POST["password"];
$telephone = $_POST["telephone"];
$imageurl = $_POST["imgurl"];
if ($imageurl == "null") {
	$imageurl = "http://dev1.intelli.dk/backend/uploads/test.jpg";
}
else {
	$imageurl = "http://dev1.intelli.dk/backend/uploads/" . $imageurl;
}

//Dette er sdflsdfjkdsklfj


$nearby = new nearby();
$nearby -> newUser($username, $password, $telephone, $imageurl);
//header('Location: http://dev1.intelli.dk');
?>

