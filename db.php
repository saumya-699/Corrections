<?php

//Create constants to store non repeating values
define('LOCALHOST','localhost');
define('DB_USERNAME','root');
define('DB_PASSWORD','');
define('DB_NAME','onlineshop');

// $servername = "localhost";
// $username = "root";
// $password = "";
// $db = "onlineshop";

// Create connection
$con = mysqli_connect(LOCALHOST,DB_USERNAME,DB_PASSWORD,DB_NAME);

// Check connection
if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}


?>
