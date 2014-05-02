<?php
	//Importing the nearby function class
	require_once("functions.php");
	//Creation a nearby object	
	$nearby = new nearby();
	//Checks if the user is logged in
	$nearby -> check(); 
	
	//Debug enviorment
	$errors; $i = 0;
	
	//GETting the GET data from AJAX, or sets default settings
/*	$locX = isset($_GET["locX"]) ? $_GET["locX"] : null;
	$locY = isset($_GET["locY"]) ? $_GET["locY"] : null;
	$range = isset($_GET["range"]) ? $_GET["range"] : 2000000;
	
	
	if($locX != null && $locY  != null){
		
		
		$nearby -> updateCoords($locX,$locY); //Updating the users coordinates
			$arr = $nearby -> nearbyMe($range);
			echo "|";
			for($i = 0; $i < count($arr); $i++)
				echo $arr[$i] . "|";
		
		exit;
	}
*/
		echo "hej";
	/*$nearby -> friendList();
		$arr = $nearby -> friendList();
		echo "|";
		for ($i = 0, $i < count($arr); $i++)
			echo $arr[$i] . "|";

		exit;

	$nearby -> close();*/
?>