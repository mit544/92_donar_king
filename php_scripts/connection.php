<?php

$servername = "localhost";
$username = "root"; 
$password = ""; 
$dbname = "92donarking";     

$link = new mysqli($servername, $username, $password, $dbname);

if ($link->connect_error) {
    die("Connection failed: " . $link->connect_error);
}
// echo "Connected successfully";

?>