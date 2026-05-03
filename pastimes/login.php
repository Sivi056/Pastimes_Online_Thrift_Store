<?php
// This file handles the login logic for users and also displays the login form.
include 'DBConn.php';
// Start the session to manage user login state
session_start();

// Initialize variables for error messages and sticky form data
$error = "";
$stickyUser = "";

if (isset($_POST['login'])) {
    // Capture form data and use it for login logic
    $stickyUser = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // 1. Fetch user by username and email (Requirement 4)
    $sql = "SELECT * FROM user WHERE username = '$stickyUser' AND userEmail = '$email'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result); // "Associative read approach" per requirements
        
        // 2. Compare hashed password using password_verify
        if (password_verify($password, $row['password'])) {
            
            // 3. Check if verified (Requirement 4)
            if ($row['isVerified'] == 0) {
                $error = "Account pending admin verification.";
            } else {
                $_SESSION['user'] = $row['username'];
                // Refresh to show the logged-in status at the top
                header("Location: login.php"); 
                exit();
            }
        } else {
            // Use sticky form (keeps $stickyUser) and redisplay
            $error = "Invalid password. Please try again.";
        }
    } else {
        $error = "User does not exist. Please register.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style.css">
    <title>Pastimes | Login</title>
</head>

<body>

    <?php if(isset($_SESSION['user'])): ?>
    <div
        style="background: #D4AF37; color: #006400; padding: 15px; text-align: center; font-weight: bold; border-bottom: 2px solid #006400;">
        <!-- Display logged-in user at the top of the page -->
        User <?php echo $_SESSION['user']; ?> is logged in.
    </div>
    <?php endif; ?>

    <div class="container">
        <h2>Pastimes Login</h2>

        <?php if($error != ""): ?>
            <!-- Display error message in a styled box if there's an error -->
        <p style="color:red; background: #ffebee; padding: 10px; border-radius: 5px;"><?php echo $error; ?></p>
        <?php endif; ?>

        <form action="login.php" method="POST">
            <label>Username</label>
            <!-- Use sticky form data for username to enhance user experience -->
            <input type="text" name="username" value="<?php echo htmlspecialchars($stickyUser); ?>"
                placeholder="Enter Username" required>

            <label>Email Address</label>
            <input type="email" name="email" placeholder="email@example.com" required>

            <label>Password</label>
            <input type="password" name="password" placeholder="••••••••" required>

            <button type="submit" name="login" class="btn-gold">Login to Account</button>
        </form>

        <p style="margin-top: 20px;">
            <!-- Provide a link to the registration page for users who don't have an account -->
            Don't have an account? <a href="register.php"
                style="color: var(--pastimes-green); font-weight: bold;">Register here</a>
        </p>
    </div>

</body>

</html>