<?php
include 'DBConn.php';
session_start();

// Handle quantity changes or removing an item from the cart
if (isset($_POST['update_cart'])) {
    $clothingId = intval($_POST['clothing_id']);
    $action = $_POST['action'];
    
    if ($action == 'remove') {
        unset($_SESSION['cart'][$clothingId]);
    } elseif ($action == 'decrease') {
        $_SESSION['cart'][$clothingId]--;
        if ($_SESSION['cart'][$clothingId] <= 0) {
            unset($_SESSION['cart'][$clothingId]);
        }
    } elseif ($action == 'increase') {
        $_SESSION['cart'][$clothingId]++;
    }
    header("Location: cart.php");
    exit();
}

$theme = $_SESSION['theme'] ?? 'Light';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style.css">
    <title>Your Cart | Pastimes</title>
</head>
<body class="<?php echo $theme; ?>">
    <nav>
        <div class="logo">PASTIMES</div>
        <div>
            <a href="index.php">Home</a>
            <a href="discovery.php">Continue Shopping</a>
            <a href="logout.php">Logout</a>
        </div>
    </nav>

    <div class="container" style="max-width: 800px; margin-top: 40px;">
        <h2>🛒 Your Selected Pastimes Pieces</h2>
        <hr>

        <?php 
        $total = 0;
        if (!empty($_SESSION['cart'])): 
            foreach ($_SESSION['cart'] as $id => $quantity):
                // Fetch each specific cart piece dynamically out of your clothing database table
                $query = mysqli_query($conn, "SELECT * FROM clothing WHERE clothingId = $id");
                if ($row = mysqli_fetch_assoc($query)):
                    $subtotal = $row['price'] * $quantity;
                    $total += $subtotal;
        ?>
                    <div style="display: flex; align-items: center; justify-content: space-between; background: white; padding: 15px; margin-bottom: 15px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.05); color: #333;">
                        <img src="<?php echo htmlspecialchars($row['imagePath']); ?>" style="width: 70px; height: 70px; object-fit: cover; border-radius: 5px;" onerror="this.src='default_item.png';">
                        <div style="flex-grow: 1; margin-left: 20px;">
                            <h4 style="margin: 0;"><?php echo htmlspecialchars($row['itemName']); ?></h4>
                            <small style="color: #777;">Brand: <?php echo htmlspecialchars($row['brand']); ?></small>
                            <p style="margin: 5px 0 0 0; font-weight: bold; color: var(--pastimes-green);">R <?php echo number_format($row['price'], 2); ?></p>
                        </div>
                        
                        <form method="POST" style="display: flex; align-items: center; gap: 10px;">
                            <input type="hidden" name="clothing_id" value="<?php echo $id; ?>">
                            <button type="submit" name="update_cart" value="1" onclick="this.form.action.value='decrease'" style="padding: 5px 10px;">-</button>
                            <span style="font-weight: bold;"><?php echo $quantity; ?></span>
                            <button type="submit" name="update_cart" value="1" onclick="this.form.action.value='increase'" style="padding: 5px 10px;">+</button>
                            <input type="hidden" name="action" value="">
                            <button type="submit" name="update_cart" value="1" onclick="this.form.action.value='remove'" style="background: #c62828; color: white; border: none; padding: 6px 12px; border-radius: 4px; cursor: pointer; margin-left: 15px;">Delete</button>
                        </form>
                    </div>
        <?php 
                endif;
            endforeach; 
        ?>
            <div style="text-align: right; margin-top: 30px; background: white; padding: 20px; border-radius: 8px;">
                <h3>Total Order: <span style="color: var(--pastimes-green);">R <?php echo number_format($total, 2); ?></span></h3>
                <button class="btn-gold" style="padding: 10px 25px; font-size: 1em; margin-top: 10px;" onclick="alert('Checkout integration simulated successfully for submission!')">Proceed to Secure Checkout</button>
            </div>
        <?php else: ?>
            <p style="text-align: center; color: #666; margin-top: 40px;">Your shopping cart is empty. Let's go look for some pieces!</p>
        </div>
        <?php endif; ?>
    </div>
</body>
</html>