<?php
// This file handles the login logic for users and also displays the login form.
include 'DBConn.php';
// Start the session to manage user login state
session_start();
$error = "";

//removed stickyuser

if (isset($_POST['login'])) 
    {
        // Capture form data and use it for login logic
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $password = $_POST['password'];

        $sql = "SELECT * FROM user WHERE userEmail = '$email'";
        $result = mysqli_query($conn, $sql);

        if ($row = mysqli_fetch_assoc($result))  // "Associative read approach" per requirements
            {
                if (password_verify($password, $row['password'])) 
                    {
                    // Check if the account is verified by admin before allowing login
                    if ($row['isVerified'] == 0) 
                        {
                            $error = "Account pending admin verification.";
                        } 
                        else // If verified, set session variables and redirect to homepage
                        {
                            $_SESSION['username'] = $row['userName'];
                            $_SESSION['role'] = $row['role'];
                            $_SESSION['userId'] = $row['userId'];
                            header("Location: index.php");
                            exit();
                        }
                    } 
                    else 
                    {  
                        $error = "Invalid password."; 
                    }
            } 
            else 
            { 
                $error = "User does not exist."; 
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