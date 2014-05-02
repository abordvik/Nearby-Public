<?php

 header('Access-Control-Allow-Origin: *');  


require_once("functions.php");
$nearby = new nearby();
$nearby -> logout();
header('Location: http://dev1.intelli.dk');
?>

