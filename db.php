<?php
$host = "127.0.0.1";
$dbname = "LMS";
$username = "root"; // Change if needed
$password = "";     // Set your MySQL password

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
