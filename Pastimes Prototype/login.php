<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Simulating login and theme selection
    $_SESSION['username'] = $_POST['username'];
    $_SESSION['theme'] = $_POST['themePref'];
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<body>
    <h2>User Login</h2>
    <form method="POST">
        <input type="text" name="username" placeholder="Username" required><br><br>
        <input type="password" name="password" placeholder="8-character password" minlength="8" required><br><br>
        
        <label>Select Theme Preference:</label>
        <select name="themePref">
            <option value="Light">Light Mode</option>
            <option value="Dark">Dark Mode</option>
        </select><br><br>
        
        <button type="submit">Login</button>
    </form>
    <p>New user? <a href="register.php">Register here</a></p>
</body>
</html>