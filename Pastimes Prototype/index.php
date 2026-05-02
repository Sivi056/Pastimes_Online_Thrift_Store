<?php
session_start();
// Default theme if not set
$theme = isset($_SESSION['theme']) ? $_SESSION['theme'] : 'Light';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Pastimes | Pre-loved Branded Clothing</title>
    <style>
        .Light { background: #f4f4f4; color: #333; }
        .Dark { background: #222; color: #eee; }
        nav { display: flex; gap: 20px; padding: 10px; border-bottom: 1px solid #ccc; }
        .hero { padding: 50px; text-align: center; }
    </style>
</head>
<body class="<?php echo $theme; ?>">
    <nav>
        <a href="index.php">Home</a>
        <a href="discovery.php">Discovery</a>
        <a href="login.php">Login/Register</a>
    </nav>

    <div class="hero">
        <h1>Welcome to Pastimes</h1>
        <p>Your dedicated marketplace for South African pre-loved branded clothing.</p>
        <img src="image.png" alt="Pastimes Logo" style="width:200px;">
    </div>
</body>
</html>