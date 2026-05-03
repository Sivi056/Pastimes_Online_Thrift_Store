<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style.css">
    <title>Pastimes | Online Thrift</title>
</head>

<body>

    <nav>
        <div class="logo">PASTIMES</div>
        <div>
            <a href="index.php">Home</a>
            <a href="discovery.php">Discovery</a>
            <?php if(isset($_SESSION['username'])): ?>
            <a href="logout.php">Logout (<?php echo $_SESSION['username']; ?>)</a>
            <?php else: ?>
            <a href="login.php">Login</a>
            <?php endif; ?>
        </div>
    </nav>

    <div class="container" style="text-align: center;">
        <?php if(!isset($_SESSION['username'])): ?>
        <h1>Welcome to Pastimes</h1>
        <p>Your destination for pre-loved branded clothing.</p>
        <hr style="border: 1px solid var(--pastimes-gold); width: 50%;">

        <h2>Ready to get started?</h2>
        <p>Log in to your account to start buying or selling.</p>

        <a href="login.php" class="btn-gold"
            style="display: inline-block; text-decoration: none; margin-top: 20px;">Login</a>

        <p style="margin-top: 15px; font-size: 0.9em;">Don't have an account? <a href="register.php"
                style="color: var(--pastimes-green);">Register here</a></p>
        <?php else: ?>
        <h1>Hello, <?php echo $_SESSION['username']; ?>!</h1>
        <p>Role: <strong><?php echo $_SESSION['role']; ?></strong></p>

        <?php if($_SESSION['role'] == 'Seller'): ?>
        <div style="background: #e8f5e9; padding: 20px; border-left: 5px solid var(--pastimes-green);">
            <h3>Seller Dashboard</h3>
            <p>Verify your items and manage your inventory.</p>
            <a href="upload.php" class="btn-gold">Upload New Clothes</a>
        </div>
        <?php else: ?>
        <div style="background: #fff8e1; padding: 20px; border-left: 5px solid var(--pastimes-gold);">
            <h3>Ready to Shop?</h3>
            <p>Browse authenticated branded items from verified sellers.</p>
            <a href="discovery.php" class="btn-gold">Go to Discovery Feed</a>
        </div>
        <?php endif; ?>
        <?php endif; ?>
    </div>

</body>

</html>