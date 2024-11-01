<?php

$servername = "localhost";
$username = "root"; 
$password = ""; 
$dbname = "92donarking";     

// connection
$link = new mysqli($servername, $username, $password, $dbname);

// error handling for connection
if ($link->connect_error) {
    die("Connection failed: " . $link->connect_error);
}
// echo "Connected successfully";

?>