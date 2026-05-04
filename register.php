<?php
include 'DBConn.php';
session_start();
$msg = "";

if (isset($_POST['register'])) 
    { /* from https://youtu.be/NfOI8cndhu8?si=ZGU6evW3kBTkXBFn around 16:30 */
        $user = mysqli_real_escape_string($conn, $_POST['username']);
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        // Hash the password before storing it in the database for security
        // last year we got in trouble for using hashes lol
        $pass = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $role = $_POST['role'];

        // from https://youtu.be/NfOI8cndhu8?si=ZGU6evW3kBTkXBFn around 20:30
        $sql = "INSERT INTO user (userName, userEmail, password, role, isVerified) 
                VALUES ('$user', '$email', '$pass', '$role', 0)";

        if (mysqli_query($conn, $sql)) 
            {
                $msg = "Registration successful! Wait for Admin verification.";
            } 
            else 
            {
                $msg = "Error: " . mysqli_error($conn);
            }
    }
?>
<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div class="container">
        <h2>Register for Pastimes</h2>
        <?php if($msg) echo "<p style='color:red;'>$msg</p>"; ?>
        <form method="POST">
            <!-- from https://youtu.be/NfOI8cndhu8?si=ZGU6evW3kBTkXBFn around 1:30 -->
            <input type="text" name="username" placeholder="Full Name" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Create Password" required>
            <select name="role">
                <!-- only 2 roles, either youre sellin or buyin, guess you have a separate seller and buyer acc if you do both?? -->
                <option value="Buyer">Buyer</option>
                <option value="Seller">Seller</option>
            </select>
            <button type="submit" name="register" class="btn-gold">Register</button>
        </form>
        <a href="login.php">Already have an account? Login</a>
    </div>
</body>

</html>