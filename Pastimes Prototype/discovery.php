<?php
session_start();
$theme = $_SESSION['theme'] ?? 'Light';

// Simulated database array
$products = [
    ['id' => 1, 'name' => 'Vintage Jacket', 'price' => 450, 'img' => 'image_2.png', 'score' => 9],
    ['id' => 2, 'name' => 'Branded Sneakers', 'price' => 1200, 'img' => 'image_3.png', 'score' => 10]
];
?>
<!DOCTYPE html>
<html lang="en">
<body class="<?php echo $theme; ?>">
    <h2>Discovery Feed</h2>
    <div style="color: red;">New Drop in: 05:42:10</div>
    
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
        <?php foreach ($products as $p): ?>
            <div style="border: 1px solid #ccc; padding: 10px;">
                <img src="<?php echo $p['img']; ?>" alt="Product" style="width:100%;">
                <h3><?php echo $p['name']; ?></h3>
                <p>Price: R<?php echo $p['price']; ?></p>
                <p>Condition: <?php echo $p['score']; ?>/10</p>
                <button>Add to Cart</button>
                <button>Message Seller</button>
            </div>
        <?php endforeach; ?>
    </div>
</body>
</html>