<?php
$host = "hostname";
$dbname = "databaseName";
$username = "username"; // Change if needed
$password = "yourpassword";     // Set your MySQL password

// Create connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// Optional: uncomment to check connection
// echo "Connected successfully";
else {
    // echo "Connection Successful ! ";
}
?>
