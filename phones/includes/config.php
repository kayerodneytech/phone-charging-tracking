<?php
// Database configuration
$servername = "localhost"; // localhost
$username = "root";        //  database username
$password = "";            //  database password
$dbname = "phone_charging"; //  database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
