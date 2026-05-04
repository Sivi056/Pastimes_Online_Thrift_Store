<?php
include 'DBConn.php';
session_start();

$theme = $_SESSION['theme'] ?? 'Light';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style.css">
    <title>Discovery Feed | Pastimes</title>
</head>
<body class="<?php echo $theme; ?>">
    <nav>
        <div class="logo">PASTIMES</div>
        <div>
            <a href="index.php">Home</a>
            <a href="discovery.php">Discovery</a>
            <?php if(isset($_SESSION['username'])): ?>
                <a href="logout.php">Logout</a>
            <?php else: ?>
                <a href="login.php">Login</a>
            <?php endif; ?>
        </div>
    </nav>

    <div class="container">
        <h1>Discovery Feed</h1>
        <p style="color: var(--pastimes-gold); font-weight: bold;">Next Drop in: <span id="timer">05:42:10</span></p>
        <hr>

        <!-- from https://www.youtube.com/watch?v=0QY2VI1JbN8&list=PLm8sgxwSZofc_jFRsbTHPAW0Kp52KgAAm&index=3 about 14:50 in -->
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-top: 20px;">
            <div style="background: white; padding: 15px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); text-align: center;">
                <img src="image_3.png" alt="Nike Sneakers" style="width: 100%; border-radius: 5px;">
                <h3>Nike Air Force 1</h3>
                <p>Condition: 5/5 (Authentic)</p>
                <p style="font-weight: bold; color: var(--pastimes-green);">R 1,200.00</p>
                <button class="btn-gold">Add to Cart</button>
            </div>

            <div style="background: white; padding: 15px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); text-align: center;">
                <img src="image_4.png" alt="Levi Jeans" style="width: 100%; border-radius: 5px;">
                <h3>Vintage Levi's </h3>
                <p>Condition: 4/5</p>
                <p style="font-weight: bold; color: var(--pastimes-green);">R 450.00</p>
                <button class="btn-gold">Add to Cart</button>
            </div>
        </div>
    </div>
</body>
</html> 