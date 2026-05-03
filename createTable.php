<?php
include 'DBConn.php';

// 1. Drop existing table to start fresh
mysqli_query($conn, "SET FOREIGN_KEY_CHECKS = 0");
mysqli_query($conn, "DROP TABLE IF EXISTS `user` ");
mysqli_query($conn, "SET FOREIGN_KEY_CHECKS = 1");

// 2. Create the table
$createSQL = "CREATE TABLE `user` (
    userId INT PRIMARY KEY AUTO_INCREMENT,
    userName VARCHAR(100),
    userEmail VARCHAR(100) UNIQUE,
    password VARCHAR(255),
    role ENUM('Buyer', 'Seller', 'Admin'),
    isVerified BOOLEAN DEFAULT FALSE 
)";

if (mysqli_query($conn, $createSQL)) {
    echo "Successfully reset the User table.<br>";
} else {
    die("Error creating table: " . mysqli_error($conn));
}

// 3. Loading data from userData.txt
$filename = "userData.txt";

if (file_exists($filename)) {
    // Read file into an array and skip empty lines automatically
    $lines = file($filename, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    foreach ($lines as $line) {
        // Clean up any hidden whitespace or tabs
        $line = trim($line);
        if (empty($line)) continue;

        $data = explode(",", $line);
        
        // Safety: Only proceed if we have at least 4 columns (Name, Email, Pass, Role)
        if (count($data) >= 4) {
            $name = mysqli_real_escape_string($conn, trim($data[0]));
            $email = mysqli_real_escape_string($conn, trim($data[1]));
            // Hash the password for security
            $pass = password_hash(trim($data[2]), PASSWORD_DEFAULT);
            $role = mysqli_real_escape_string($conn, trim($data[3]));

            // INSERT IGNORE ensures no crashes if an email is a duplicate
            $insertSQL = "INSERT IGNORE INTO `user` (userName, userEmail, password, role, isVerified) 
                          VALUES ('$name', '$email', '$pass', '$role', 1)";
            
            if (!mysqli_query($conn, $insertSQL)) {
                echo "Error inserting $email: " . mysqli_error($conn) . "<br>";
            }
        }
    }
    echo "<strong>Success:</strong> Data loaded from userData.txt into the database.";
} else {
    echo "<strong>Error:</strong> userData.txt not found.";
}

mysqli_close($conn);
?>
