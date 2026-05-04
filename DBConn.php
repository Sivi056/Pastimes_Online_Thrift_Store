<!-- watched during development:
 https://youtube.com/playlist?list=PLm8sgxwSZofc_jFRsbTHPAW0Kp52KgAAm&si=yJE-go8ZPSrvP-pI
 https://youtube.com/playlist?list=PLOR5hj0X3WPdOWwU7eCCfFcgIkS1WrDYl&si=_uDfh-nC1HIBcn4u
 https://youtube.com/playlist?list=PL5kIDoSdjG7PY_kPyULbbLk4mpvStqdPR&si=Vxt44Xpmhx7jnkji
 -->

<?php
$host = "localhost"; // Since we're running the database on our local machine, it cant really run on other peoples machines which makes things a lil harder
$dbUser = "root";
$dbPass = ""; // Default XAMPP password is empty
$dbName = "pastimes_db";

// Create connection
$conn = mysqli_connect($host, $dbUser, $dbPass, $dbName);

// Check connection
if (!$conn) 
    {
        // "die" lol, when the connection fails i be like GuessI'llDie.jpg  
        die("Connection failed: " . mysqli_connect_error());
    }
?>