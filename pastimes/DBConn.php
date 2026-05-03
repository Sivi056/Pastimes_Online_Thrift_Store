<?php
$host = "localhost"; // Since we're running the database on our local machine, it cant really run on other peoples machines which makes things a lil harder
$dbPass = ""; // Default XAMPP password is empty
$dbName = "pastimes_db";

// Create connection
$conn = mysqli_connect($host, $dbUser, $dbPass, $dbName);

// Check connection
if (!$conn) {
    // "die" lol, when the connection fails i be like GuessI'llDie.jpg  
    die("Connection failed: " . mysqli_connect_error());
}
?>