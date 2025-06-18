<?php
$mysqlhost = 'shuttle.proxy.rlwy.net';
$mysqlport = 54458;
$mysqluser = 'root';
$mysqlpassword = 'sGadhNwhhMtILjkTBfFqNuPwYveJZrkE';
$mysqldatabase = 'railway';

$conn = new mysqli($mysqlhost, $mysqluser, $mysqlpassword, $mysqldatabase, $mysqlport);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
