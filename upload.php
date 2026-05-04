<?php session_start(); 
// Only allow sellers to access this page, buyers shouldnt be able to upload items for sale bc well they arent sellers so yeah
if(!isset($_SESSION['role']) || $_SESSION['role'] != 'Seller') 
    {
        header("Location: index.php");
    }
?>
<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="style.css">
    <title>Upload Item | Pastimes</title>
</head>
<body>
<nav><a href="index.php">Back Home</a></nav>

<div class="container">
    <h2>Upload Branded Clothing</h2>
    <form action="discovery.php" method="POST">
        <label>Brand</label>
        <input type="text" name="brand" placeholder="e.g. Nike, Adidas, Gucci" required>
        
        <label>Description</label>
        <textarea name="description" placeholder="Describe the material, fit, and origin..." required></textarea>
        
        <label>Condition Scale (1 = Poor, 5 = Like New)</label>
        <select name="condition">
            <option value="1">1 - Bad Condition</option>
            <option value="2">2 - Fair</option>
            <option value="3">3 - Good</option>
            <option value="4">4 - Great</option>
            <option value="5" selected>5 - Excellent / Authentic</option>
        </select>
        
        <label>Price (R)</label>
        <input type="number" name="price" placeholder="500.00" required>
        
        <button type="submit" class="btn-gold">List Item for Sale</button>
    </form>
</div>
</body>
</html>



