<?php
include 'DBConn.php';
session_start();
$error = "";

if (isset($_POST['login'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];

    $sql = "SELECT * FROM user WHERE userEmail = '$email'";
    $result = mysqli_query($conn, $sql);

    if ($row = mysqli_fetch_assoc($result)) {
        if (password_verify($password, $row['password'])) {
            if ($row['isVerified'] == 0) {
                $error = "Account pending admin verification.";
            } else {
                $_SESSION['username'] = $row['userName'];
                $_SESSION['role'] = $row['role'];
                $_SESSION['userId'] = $row['userId'];
                header("Location: index.php");
                exit();
            }
        } else { $error = "Invalid password."; }
    } else { $error = "User does not exist."; }
}
?>
<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div class="container">
        <h2>Login</h2>
        <?php if($error) echo "<p style='color:red;'>$error</p>"; ?>
        <form method="POST">
            <input type="email" name="email" placeholder="Email Address" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit" name="login" class="btn-gold">Login</button>
        </form>
        <p>New? <a href="register.php">Register here</a></p>
    </div>
</body>

</html>