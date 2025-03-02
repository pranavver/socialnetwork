<?php

$servername = "localhost";
$username = "root";
$password = "";
$database = "socialnetwork";

//Create connection
$conn = new mysqli($servername, $username, $password, $database);

//Checking connection
if ($conn->connect_error) {
    die("Connection Failed". $conn->connect_error);
}

?>



