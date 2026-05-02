<?php
include 'DBConn.php';
$msg = "";

if (isset($_POST['register'])) {
    $user = $_POST['username'];
    $email = $_POST['email'];
    $pass = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role'];

    $sql = "INSERT INTO user (username, userEmail, password, role, isVerified) 
            VALUES ('$user', '$email', '$pass', '$role', 0)"; // 0 means Pending

    if (mysqli_query($conn, $sql)) {
        $msg = "Registration successful! Please wait for an Admin to verify your account.";
    } else {
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
        <p style="color:green;"><?php echo $msg; ?></p>
        <form method="POST">
            <input type="text" name="username" placeholder="Full Name" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Create Password" required>
            <select name="role">
                <option value="Buyer">Buyer</option>
                <option value="Seller">Seller</option>
            </select>
            <button type="submit" name="register" class="btn-gold">Register</button>
        </form>
        <a href="login.php">Already have an account? Login</a>
    </div>
</body>

</html>