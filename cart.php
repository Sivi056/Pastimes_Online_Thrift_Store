<?php
include 'DBConn.php';
session_start();

// Check if user is logged in to safely track account states
$userId = $_SESSION['userId'] ?? null;

// Handle quantity changes, single removals, or global empty cart wipes
if (isset($_POST['update_cart'])) {
    $clothingId = intval($_POST['clothing_id'] ?? 0);
    $action = $_POST['action'] ?? '';
    
    if ($action == 'remove' && $clothingId > 0) {
        unset($_SESSION['cart'][$clothingId]);
    } elseif ($action == 'decrease' && $clothingId > 0) {
        $_SESSION['cart'][$clothingId]--;
        if ($_SESSION['cart'][$clothingId] <= 0) {
            unset($_SESSION['cart'][$clothingId]);
        }
    } elseif ($action == 'increase' && $clothingId > 0) {
        $_SESSION['cart'][$clothingId]++;
    } elseif ($action == 'empty_all') {
        unset($_SESSION['cart']);
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <h2>🛒 Your Selected Pastimes Pieces</h2>
            <?php if (!empty($_SESSION['cart'])): ?>
            <form method="POST" style="margin: 0;">
                <input type="hidden" name="action" value="empty_all">
                <button type="submit" name="update_cart" class="btn-clear"
                    style="background: #e53935; color: white; border: none; padding: 8px 15px; border-radius: 4px; cursor: pointer; font-weight: bold;">
                    <i class="fas fa-trash-alt"></i> Empty Entire Cart
                </button>
            </form>
            <?php endif; ?>
        </div>
        <hr>

        <?php 
        $total = 0;
        if (!empty($_SESSION['cart'])): 
            foreach ($_SESSION['cart'] as $id => $quantity):
                // FIXED: Changed table to product and key to productId to align with your active schema layout
                $query = mysqli_query($conn, "SELECT * FROM product WHERE productId = $id");
                if ($row = mysqli_fetch_assoc($query)):
                    $subtotal = $row['price'] * $quantity;
                    $total += $subtotal;
        ?>
        <div
            style="display: flex; align-items: center; justify-content: space-between; background: white; padding: 15px; margin-bottom: 15px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.05); color: #333;">
            <img src="<?php echo htmlspecialchars($row['imagePath']); ?>"
                style="width: 70px; height: 70px; object-fit: cover; border-radius: 5px;"
                onerror="this.src='Images/default_item.png.jpeg';">
            <div style="flex-grow: 1; margin-left: 20px;">
                <h4 style="margin: 0;"><?php echo htmlspecialchars($row['itemName']); ?></h4>
                <small style="color: #777;">Brand: <?php echo htmlspecialchars($row['brand']); ?></small>
                <p style="margin: 5px 0 0 0; font-weight: bold; color: var(--pastimes-green, #006400);">R
                    <?php echo number_format($row['price'], 2); ?></p>
            </div>

            <form method="POST" style="display: flex; align-items: center; gap: 10px;">
                <input type="hidden" name="clothing_id" value="<?php echo $id; ?>">
                <button type="submit" name="update_cart" value="1" onclick="this.form.action.value='decrease'"
                    style="padding: 5px 10px; cursor: pointer;">-</button>
                <span style="font-weight: bold;"><?php echo $quantity; ?></span>
                <button type="submit" name="update_cart" value="1" onclick="this.form.action.value='increase'"
                    style="padding: 5px 10px; cursor: pointer;">+</button>
                <input type="hidden" name="action" value="">
                <button type="submit" name="update_cart" value="1" onclick="this.form.action.value='remove'"
                    style="background: #c62828; color: white; border: none; padding: 6px 12px; border-radius: 4px; cursor: pointer; margin-left: 15px;">Delete</button>
            </form>
        </div>
        <?php 
                endif;
            endforeach; 
        ?>
        <div
            style="text-align: right; margin-top: 30px; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.05); color:#333;">
            <h3>Total Order: <span style="color: var(--pastimes-green, #006400);">R
                    <?php echo number_format($total, 2); ?></span></h3>
            <form action="checkout_process.php" method="POST">
                <button type="submit" name="execute_checkout" class="btn-gold"
                    style="padding: 12px 30px; font-size: 1.05em; font-weight: bold; margin-top: 10px; cursor: pointer; width: auto;">
                    Proceed to Secure Checkout <i class="fas fa-arrow-right"></i>
                </button>
            </form>
        </div>
        <?php else: ?>
        <p style="text-align: center; color: #666; margin-top: 40px; font-size: 1.1em;">Your shopping cart is empty.
            Let's go look for some pieces!</p>
        <?php endif; ?>

        <div
            style="margin-top: 60px; margin-bottom: 30px; background: white; padding: 25px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.05); color: #333;">
            <h3 style="margin-top: 0; color: #333;"><i class="fas fa-history"></i> Your Purchase & Order Report History
            </h3>
            <p style="color: #666; font-size: 0.85em; margin-bottom: 15px;">Track all previous thrift orders processed
                securely through your Pastimes profile environment.</p>
            <hr style="border: 0; border-top: 1px solid #eee; margin-bottom: 20px;">

            <?php 
            if ($userId):
                // Fetch previous purchases by connecting user profile directly to historical products row states
                $historySQL = "SELECT ph.*, p.itemName, p.brand, p.imagePath 
                               FROM purchase_history ph
                               JOIN product p ON ph.productId = p.productId 
                               WHERE ph.userId = $userId 
                               ORDER BY ph.purchaseDate DESC";
                $historyResult = mysqli_query($conn, $historySQL);

                // Check if the table profile setup exists in phpMyAdmin yet
                if ($historyResult && mysqli_num_rows($historyResult) > 0):
            ?>
            <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 0.95em;">
                <thead>
                    <tr style="background: #f5f5f5; border-bottom: 2px solid #ddd;">
                        <th style="padding: 12px;">Piece Preview</th>
                        <th style="padding: 12px;">Item Name</th>
                        <th style="padding: 12px;">Brand</th>
                        <th style="padding: 12px; text-align: center;">Qty</th>
                        <th style="padding: 12px;">Amount Paid</th>
                        <th style="padding: 12px;">Date Ordered</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($pRow = mysqli_fetch_assoc($historyResult)): ?>
                    <tr style="border-bottom: 1px solid #eee;">
                        <td style="padding: 10px;">
                            <img src="<?php echo htmlspecialchars($pRow['imagePath']); ?>"
                                style="width: 45px; height: 45px; object-fit: cover; border-radius: 4px;"
                                onerror="this.src='Images/default_item.png.jpeg';">
                        </td>
                        <td style="padding: 10px; font-weight: bold;"><?php echo htmlspecialchars($pRow['itemName']); ?>
                        </td>
                        <td style="padding: 10px; color: #555;"><?php echo htmlspecialchars($pRow['brand']); ?></td>
                        <td style="padding: 10px; text-align: center; font-weight: bold;">
                            <?php echo $pRow['quantity']; ?></td>
                        <td style="padding: 10px; color: var(--pastimes-green, #006400); font-weight: bold;">R
                            <?php echo number_format($pRow['totalPaid'], 2); ?></td>
                        <td style="padding: 10px; font-size: 0.85em; color: #777;">
                            <?php echo date("d M Y, H:i", strtotime($pRow['purchaseDate'])); ?></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
            <?php 
                else: 
            ?>
            <p style="color: #888; font-style: italic; text-align: center; padding: 20px 0;">No purchase records found
                under this user account layout yet.</p>
            <?php 
                endif;
            else:
            ?>
            <p style="color: #d32f2f; font-weight: bold; text-align: center; padding: 15px;">⚠️ Please log in to
                securely pull your personal transaction history files.</p>
            <?php endif; ?>
        </div>
    </div>
</body>

</html>