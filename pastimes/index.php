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
        <div style="font-size: 1.5rem; color: var(--pastimes-gold); font-weight: bold;">PASTIMES</div>
        <div>
            <a href="index.php">Home</a>
            <a href="discovery.php">Discovery</a>
            <?php if(isset($_SESSION['username'])): ?>
            <a href="logout.php" style="color: white;">Logout (<?php echo $_SESSION['username']; ?>)</a>
            <?php endif; ?>
        </div>
    </nav>

    <div class="container" style="text-align: center;">
        <?php if(!isset($_SESSION['username'])): ?>
        <h1>Welcome to Pastimes</h1>
        <p>Your destination for pre-loved branded clothing.</p>
        <hr style="border: 1px solid var(--pastimes-gold); width: 50%;">

        <h2>Create Your Account</h2>
        <form action="logic.php" method="POST">
            <input type="text" name="username" placeholder="Full Name" required>
            <input type="email" name="email" placeholder="Email Address" required>
            <input type="password" name="password" placeholder="Password (8+ characters)" minlength="8" required>

            <div style="margin: 15px 0; text-align: left;">
                <label><strong>I want to:</strong></label><br>
                <input type="radio" name="role" value="Buyer" checked style="width: auto;"> Buy Clothing
                <input type="radio" name="role" value="Seller" style="width: auto;"> Sell Clothing
            </div>

            <button type="submit" name="register" class="btn-gold">Join the Community</button>
        </form>
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