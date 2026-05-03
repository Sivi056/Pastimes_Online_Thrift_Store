<?php
session_start();

if (isset($_POST['register'])) {
    // Save info into the session so the website "remembers" the user across pages
    $_SESSION['username'] = $_POST['username'];
    $_SESSION['role'] = $_POST['role'];
    $_SESSION['email'] = $_POST['email'];
    
    // Redirect back to home page after registration
    header("Location: index.php");
    exit();
}

// Simple logout logic
if (isset($_GET['logout'])) {
    // Clear the session to log the user out
    session_destroy();
    // Redirect back to home page after logout
    header("Location: index.php");
    exit();
}
?>