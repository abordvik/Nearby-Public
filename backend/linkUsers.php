<?php
 header('Access-Control-Allow-Origin: *');  


 $username = $_POST['username'];
	//Importing the nearby function class
	require_once("functions.php");
	//Creation a nearby object	
	$nearby = new nearby();
	//Checks if the user is logged in
	$nearby -> check(); 

$nearby -> linkUsers($username);


//header('Location: http://dev1.intelli.dk');
?>