<html>
<head>
<!--<script src="../js/jquery.js"></script>-->
<script src="../js/jStorage.js"></script>
</head>
<body>
<script>
		document.write("test");
		var token = $.jStorage.get("token");
        var uID = $.jStorage.get("uID");
		window.location = "http://dev1.intelli.dk/backend/nearby.php?uid=" + uID + "&token=" + token + "&locX=0&locY=0";
			
</script>


<?php
	/*//Importing the nearby function class
	require_once("functions.php");
	//Creation a nearby object	
	$nearby = new nearby();
	$nearby -> check(); //Checks if the user is logged in

	
	$arr = $nearby -> nearbyMe(2000000);
	echo "|";
	for($i = 0; $i < count($arr); $i++)
		echo $arr[$i] . "|";
	$nearby -> close();
	echo '<meta http-equiv="refresh" content="1">'
	*/
?>
</body>
</html>