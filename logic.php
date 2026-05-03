<?php
session_start();

if (isset($_POST['register'])) {
    // Save info into the session so the website "remembers" you tonight
    $_SESSION['username'] = $_POST['username'];
    $_SESSION['role'] = $_POST['role'];
    $_SESSION['email'] = $_POST['email'];
    
    // Redirect back to home
    header("Location: index.php");
    exit();
}

// Simple logout logic
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: index.php");
    exit();
}
?>