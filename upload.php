<!-- watched during development:
 https://youtube.com/playlist?list=PLm8sgxwSZofc_jFRsbTHPAW0Kp52KgAAm&si=yJE-go8ZPSrvP-pI
 https://youtube.com/playlist?list=PLOR5hj0X3WPdOWwU7eCCfFcgIkS1WrDYl&si=_uDfh-nC1HIBcn4u
 https://youtube.com/playlist?list=PL5kIDoSdjG7PY_kPyULbbLk4mpvStqdPR&si=Vxt44Xpmhx7jnkji
 -->

 <!-- https://www.w3schools.com/php/php_sessions.asp -->
  //https://www.w3schools.com/php/func_var_isset.asp

<?php
include 'DBConn.php';
session_start();

$theme = $_SESSION['theme'] ?? 'Light';
$statusMessage = "";

if (isset($_POST['submit_clothing'])) {
    $itemName = mysqli_real_escape_with_str($conn, $_POST['item_name']);
    $brand = mysqli_real_escape_with_str($conn, $_POST['brand']);
    $description = mysqli_real_escape_with_str($conn, $_POST['description']);
    $price = floatval($_POST['price']);
    
    // File Upload Handling
    $targetDir = "uploads/";
    if (!file_exists($targetDir)) {
        mkdir($targetDir, 0777, true);
    }
    
    $fileName = basename($_FILES["clothing_image"]["name"]);
    $targetFilePath = $targetDir . time() . "_" . $fileName; // Appends timestamp to prevent overwriting files
    $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);
    
    if(!empty($fileName)) {
        // Permit common web image profiles
        $allowTypes = array('jpg','png','jpeg','gif');
        if(in_array(strtolower($fileType), $allowTypes)){
            if(move_uploaded_file($_FILES["clothing_image"]["tmp_name"], $targetFilePath)){
                // Insert clothes posting details into your clothing schema table layout
                $sql = "INSERT INTO clothing (itemName, brand, description, price, imagePath, status) 
                        VALUES ('$itemName', '$brand', '$description', $price, '$targetFilePath', 'Pending Approval')";
                
                if(mysqli_query($conn, $sql)){
                    $statusMessage = "🎉 Sale request uploaded successfully! Awaiting Admin verification oversight.";
                } else {
                    $statusMessage = "❌ Database error: Couldn't complete registration record link.";
                }
            } else {
                $statusMessage = "❌ File Error: Failed to write uploaded image to target folder directory path.";
            }
        } else {
            $statusMessage = "❌ Invalid File Format: Only JPG, JPEG, PNG, or GIF extensions allowed.";
        }
    } else {
        $statusMessage = "❌ Selection Error: Please select an item photo preview to upload.";
    }
}

function mysqli_real_escape_with_str($conn, $str) {
    return mysqli_real_escape_string($conn, trim($str));
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style.css">
    <title>Submit Piece | Pastimes Marketplace</title>
</head>
<body class="<?php echo $theme; ?>">
    <nav>
        <div class="logo">PASTIMES</div>
        <div>
            <a href="index.php">Home</a>
            <a href="discovery.php">Discovery Feed</a>
            <a href="logout.php">Logout</a>
        </div>
    </nav>

    <div class="container" style="max-width: 600px; margin-top: 30px;">
        <div style="background: white; padding: 30px; border-radius: 8px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); color: #333;">
            <h2 style="margin-top: 0;">👕 List an Item for Sale</h2>
            <p style="color: #666; font-size: 0.9em;">Submit your vintage piece or sneakers. Items are verified by administration within 24 hours.</p>
            <hr style="border: 0; border-top: 1px solid #eee; margin: 20px 0;">

            <?php if(!empty($statusMessage)) echo "<p style='font-weight: bold; color: var(--pastimes-green); background: #e8f5e9; padding: 10px; border-radius: 4px;'>$statusMessage</p>"; ?>

            <form method="POST" enctype="multipart/form-data" style="display: flex; flex-direction: column; gap: 15px;">
                <div>
                    <label style="font-weight: bold; display: block; margin-bottom: 5px;">Garment / Piece Title:</label>
                    <input type="text" name="item_name" required placeholder="e.g. Vintage Nike Windbreaker" style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px;">
                </div>
                <div>
                    <label style="font-weight: bold; display: block; margin-bottom: 5px;">Brand Name:</label>
                    <input type="text" name="brand" required placeholder="e.g. Nike" style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px;">
                </div>
                <div>
                    <label style="font-weight: bold; display: block; margin-bottom: 5px;">Asking Price (ZAR):</label>
                    <input type="number" step="0.01" name="price" required placeholder="R 0.00" style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px;">
                </div>
                <div>
                    <label style="font-weight: bold; display: block; margin-bottom: 5px;">Condition & Details Description:</label>
                    <textarea name="description" rows="4" required placeholder="Describe condition flaws, sizing fit details, authenticity tracking tags..." style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; font-family: sans-serif;"></textarea>
                </div>
                <div>
                    <label style="font-weight: bold; display: block; margin-bottom: 5px;">Item Imagery Upload:</label>
                    <input type="file" name="clothing_image" required style="padding: 5px 0;">
                </div>
                <button type="submit" name="submit_clothing" class="btn-gold" style="padding: 12px; font-size: 1em; font-weight: bold; margin-top: 10px;">Submit Verification Request</button>
            </form>
        </div>
    </div>
</body>
</html>