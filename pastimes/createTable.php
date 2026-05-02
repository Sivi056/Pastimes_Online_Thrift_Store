<?php
include 'DBConn.php';


// as required, Deleting the table if it exists
$dropSQL = "DROP TABLE IF EXISTS user";
if (mysqli_query($conn, $dropSQL)) {
    echo "Old user table deleted successfully.<br>";
}


$createSQL = "CREATE TABLE user (
    userId INT PRIMARY KEY AUTO_INCREMENT,
    userName VARCHAR(100),
    userEmail VARCHAR(100) UNIQUE,
    password VARCHAR(255),
    role ENUM('Buyer', 'Seller'),
    isVerified BOOLEAN DEFAULT FALSE 
)";


if (mysqli_query($conn, $createSQL)) {
    echo "New user table created successfully.<br>";
}

// Loading the necessarydata from userData.txt
$file = fopen("userData.txt", "r");

if ($file) {
    while (($line = fgets($file)) !== false) {
        $data = explode(",", trim($line));

        
        $name = $data[0];
        $email = $data[1];
        $role = $data[3];
        $pass = password_hash($data[2], PASSWORD_DEFAULT); // This will create the 60-character hash
        $insertSQL = "INSERT INTO user (userName, userEmail, password, role) 
                      VALUES ('$name', '$email', '$pass', '$role')";
                    
        
        mysqli_query($conn, $insertSQL);
    }
    fclose($file);
    echo "Data from userData.txt loaded successfully!";
} else {
    echo "Error opening userData.txt";
}

mysqli_close($conn);
?>