<?php
include 'DBConn.php';
session_start();

//This ensures the user is logged in before allowing checkout execution
if (!isset($_SESSION['userId'])) {
    header("Location: login.php");
    exit();
}

$userId = $_SESSION['userId'];

if (isset($_POST['execute_checkout']) && !empty($_SESSION['cart'])) {
    
    //Loop through your session cart items to record the purchase tracking data
    foreach ($_SESSION['cart'] as $productId => $quantity) {
        $productId = intval($productId);
        $quantity = intval($quantity);
        
        // Fetching product information 
        $productQuery = mysqli_query($conn, "SELECT price FROM product WHERE productId = $productId");
        if ($productRow = mysqli_fetch_assoc($productQuery)) {
            $price = $productRow['price'];
            $totalPrice = $price * $quantity;
            
            // Insert records into a transaction tracking system
            $insertQuery = "INSERT INTO purchase_history (userId, productId, quantity, totalPaid, purchaseDate) 
                            VALUES ($userId, $productId, $quantity, $totalPrice, NOW())";
            mysqli_query($conn, $insertQuery);
            
            // Marking the item as 'Sold' so it disappears from the Discovery feed
            mysqli_query($conn, "UPDATE product SET status = 'Sold' WHERE productId = $productId");
        }
    }
    
    // Wipe out the shopping cart session array clean as required by the rubric
    unset($_SESSION['cart']);
    
    //Kick the user back out to the login screen
    header("Location: login.php?msg=CheckoutSuccessful");
    exit();
} else {
    header("Location: cart.php");
    exit();
}
?>