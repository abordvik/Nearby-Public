<?php
//Our random string generator
function getRandomString($length = 10) {
    $validCharacters = "abcdefghijklmnopqrstuxyvwzABCDEFGHIJKLMNOPQRSTUXYVWZ0123456789";
    $validCharNumber = strlen($validCharacters);
 
    $result = "";
 
    for ($i = 0; $i < $length; $i++) {
        $index = mt_rand(0, $validCharNumber - 1);
        $result .= $validCharacters[$index];
    }
 
    return $result;
}


//Directory where we save files!
$uploaddir = '/home/dev1/public_html/backend/uploads/';

//We get the files ending:
$type = $_FILES['file']['type'];
$split = split('/', $type);
$type = $split['1'];

//We create a file name, which is completely random + it's file ending from above:
$name = getRandomString() . "." . $type;


$uploadfile = $uploaddir . $name;

echo $name;
//We move the uploaded file, to it's correct position
move_uploaded_file($_FILES['file']['tmp_name'], $uploadfile);

?>