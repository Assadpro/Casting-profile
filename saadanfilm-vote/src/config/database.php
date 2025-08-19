<?php
$host = 'localhost'; // Database host
$user = 'your_username'; // Database username
$password = 'your_password'; // Database password
$dbname = 'your_database_name'; // Database name

// Create connection
$conn = new mysqli($host, $user, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>