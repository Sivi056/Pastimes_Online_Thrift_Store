<?php
$host = "localhost";
$dbUser = "root";
$dbPass = ""; // Default XAMPP password is empty
$dbName = "pastimes_db";

// Create connection
$conn = mysqli_connect($host, $dbUser, $dbPass, $dbName);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>