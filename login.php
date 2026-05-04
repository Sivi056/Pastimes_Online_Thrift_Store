<!-- watched during development:
 https://youtube.com/playlist?list=PLm8sgxwSZofc_jFRsbTHPAW0Kp52KgAAm&si=yJE-go8ZPSrvP-pI
 https://youtube.com/playlist?list=PLOR5hj0X3WPdOWwU7eCCfFcgIkS1WrDYl&si=_uDfh-nC1HIBcn4u
 https://youtube.com/playlist?list=PL5kIDoSdjG7PY_kPyULbbLk4mpvStqdPR&si=Vxt44Xpmhx7jnkji
 -->

<?php
// This file handles the login logic for users and also displays the login form.
include 'DBConn.php';
// https://www.w3schools.com/php/php_sessions.asp
// Start the session to manage user login state
session_start();
$error = "";

//removed stickyuser

//https://www.w3schools.com/php/func_var_isset.asp
// pretty much everything under this is leanred from https://youtu.be/UeTHl9dmLb8?si=yvq_bvW2s6-iwL0c starting around 0:20
if (isset($_POST['login'])) 
    {
        //https://www.w3schools.com/php/func_mysqli_real_escape_string.asp
        // Capture form data and use it for login logic
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $password = $_POST['password'];

        $sql = "SELECT * FROM user WHERE userEmail = '$email'";
        $result = mysqli_query($conn, $sql);

        //https://www.w3schools.com/php/func_mysqli_fetch_assoc.asp
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
                            // https://www.w3schools.com/php/php_sessions.asp
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