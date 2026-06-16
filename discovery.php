<!-- watched during development:
 https://youtube.com/playlist?list=PLm8sgxwSZofc_jFRsbTHPAW0Kp52KgAAm&si=yJE-go8ZPSrvP-pI
 https://youtube.com/playlist?list=PLOR5hj0X3WPdOWwU7eCCfFcgIkS1WrDYl&si=_uDfh-nC1HIBcn4u
 https://youtube.com/playlist?list=PL5kIDoSdjG7PY_kPyULbbLk4mpvStqdPR&si=Vxt44Xpmhx7jnkji
 //https://www.w3schools.com/php/php_sessions.asp
 -->

<?php
include 'DBConn.php';
session_start();

$theme = $_SESSION['theme'] ?? 'Light';
$msg = "";

// Initialize the shopping cart session if it doesn't exist yet
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Handle adding an item to the cart dynamically
if (isset($_POST['add_to_cart'])) {
    $clothingId = intval($_POST['clothing_id']);
    if (isset($_SESSION['cart'][$clothingId])) {
        $_SESSION['cart'][$clothingId]++;
    } else {
        $_SESSION['cart'][$clothingId] = 1;
    }
    $msg = "Item added to your cart!";
}

// Fetch all uploaded clothes from the database system
$result = mysqli_query($conn, "SELECT * FROM clothing");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <title>Discovery Feed | Pastimes</title>
</head>
<body class="<?php echo $theme; ?>">
    <nav>
        <div class="logo">PASTIMES</div>
        <div>
            <a href="index.php">Home</a>
            <a href="discovery.php">Discovery</a>
            <?php if(isset($_SESSION['username'])): ?>
                <a href="cart.php"><i class="fas fa-shopping-cart"></i> Cart (<?php echo array_sum($_SESSION['cart']); ?>)</a>
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

        <?php if($msg) echo "<p style='color: green; font-weight: bold; margin: 15px 0;'>$msg <a href='cart.php' style='text-decoration: underline; color: var(--pastimes-green);'>View Cart</a></p>"; ?>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-top: 20px;">
            <?php if ($result && mysqli_num_rows($result) > 0): ?>
                <?php while($row = mysqli_fetch_assoc($result)): ?>
                    <div style="background: white; padding: 15px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); text-align: center; display: flex; flex-direction: column; justify-content: space-between; color: #333;">
                        
                        <img src="<?php echo htmlspecialchars($row['imagePath']); ?>" alt="Thrift Piece" style="width: 100%; height: 230px; object-fit: cover; border-radius: 5px;" onerror="this.src='https://images.unsplash.com/photo-1489987707025-afc232f7ea0f?q=80&w=500&auto=format&fit=cover';">
                        
                        <div style="margin-top: 10px;">
                            <small style="color: #999; text-transform: uppercase; font-weight: bold;"><?php echo htmlspecialchars($row['brand']); ?></small>
                            <h3 style="margin: 5px 0 10px 0; font-size: 1.2em;"><?php echo htmlspecialchars($row['itemName']); ?></h3>
                            <p style="font-size: 0.9em; color: #666; margin-bottom: 10px;"><?php echo htmlspecialchars($row['description']); ?></p>
                            <p style="font-weight: bold; color: var(--pastimes-green); font-size: 1.15em; margin-bottom: 15px;">R <?php echo number_format($row['price'], 2); ?></p>
                            
                            <form method="POST">
                                <input type="hidden" name="clothing_id" value="<?php echo $row['clothingId']; ?>">
                                <button type="submit" name="add_to_cart" class="btn-gold" style="width: 100%;">Add to Cart</button>
                            </form>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div style="background: white; padding: 15px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); text-align: center;">
                    <img src="image_3.png" alt="Nike Sneakers" style="width: 100%; border-radius: 5px;">
                    <h3>Nike Air Force 1</h3>
                    <p>Condition: 5/5 (Authentic)</p>
                    <p style="font-weight: bold; color: var(--pastimes-green);">R 1,200.00</p>
                    <button class="btn-gold">Add to Cart</button>
                </div>
                <div style="background: white; padding: 15px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); text-align: center;">
                    <img src="image_4.png" alt="Levi Jeans" style="width: 100%; border-radius: 5px;">
                    <h3>Vintage Levi's</h3>
                    <p>Condition: 4/5</p>
                    <p style="font-weight: bold; color: var(--pastimes-green);">R 450.00</p>
                    <button class="btn-gold">Add to Cart</button>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>