<?php
 header('Access-Control-Allow-Origin: *');  


	//Importing the nearby function class
	require_once("functions.php");
	//Creation a nearby object	
	$nearby = new nearby();
	//Checks if the user is logged in
	$nearby -> check(); 
	

?>