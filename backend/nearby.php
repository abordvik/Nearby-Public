<?php
 header('Access-Control-Allow-Origin: *');  


	//Importing the nearby function class
	require_once("functions.php");
	//Creation a nearby object	
	$nearby = new nearby();
	//Checks if the user is logged in
	$nearby -> check(); 
	
	//Debug enviorment
	$errors; $i = 0;
	
	//GETting the GET data from AJAX, or sets default settings
	$locX = isset($_POST["locX"]) ? $_POST["locX"] : null;
	$locY = isset($_POST["locY"]) ? $_POST["locY"] : null;
	$range = isset($_POST["range"]) ? $_POST["range"] : 200;
	
	if($locX != null && $locY  != null){
		
		
		$nearby -> updateCoords($locX,$locY); //Updating the users coordinates
		$arr = $nearby -> nearbyMe($range);
		echo $arr;
	}
	
	// Section to show errors
	if($locX == null){ $errors[$i] = "locX not set"; $i++; }
	if($locY == null){ $errors[$i] = "locY not set"; $i++; }
	

	$nearby -> close();
?>