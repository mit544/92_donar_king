<?php
$servername = "localhost";
$username = "root"; 
$password = ""; 
$dbname = "92donarking";     

// connection
$conn = new mysqli($servername, $username, $password, $dbname);

// error handling for connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
echo "Connected successfully";
?>