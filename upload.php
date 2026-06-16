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

// Redirect if not logged in as a Seller
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Seller') {
    header("Location: login.php");
    exit();
}

$msg = "";

if (isset($_POST['upload_item'])) {
    $itemName = mysqli_real_escape_string($conn, $_POST['item_name']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $brand = mysqli_real_escape_string($conn, $_POST['brand']);
    $price = floatval($_POST['price']);
    
    // File upload logic
    $targetDir = "images/";
    // Ensure directory exists
    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0755, true);
    }
    
    $fileName = basename($_FILES["item_image"]["name"]);
    $targetFilePath = $targetDir . time() . "_" . $fileName; // Unique name prefix
    $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);
    
    if (!empty($fileName)) {
        // Allow specific file formats
        $allowTypes = array('jpg', 'png', 'jpeg', 'gif');
        if (in_array(strtolower($fileType), $allowTypes)) {
            if (move_uploaded_uploaded_file($_FILES["item_image"]["tmp_name"], $targetFilePath)) {
                // Insert into clothing database table
                $sql = "INSERT INTO clothing (itemName, description, brand, price, imagePath, isApproved) 
                        VALUES ('$itemName', '$description', '$brand', $price, '$targetFilePath', 0)";
                
                if (mysqli_query($conn, $sql)) {
                    $msg = "Item uploaded successfully! Awaiting admin distribution verification.";
                } else {
                    $msg = "Database storage failure: " . mysqli_error($conn);
                }
            } else {
                $msg = "Error moving file to storage folder.";
            }
        } else {
            $msg = "Invalid file type. Only JPG, JPEG, PNG, & GIF allowed.";
        }
    } else {
        $msg = "Please select an image to upload.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style.css">
    <title>Pastimes | Upload Showcase</title>
</head>
<body>
    <nav>
        <div class="logo">PASTIMES</div>
        <div>
            <a href="index.php">Home</a>
            <a href="discovery.php">Discovery</a>
            <a href="logout.php">Logout (<?php echo $_SESSION['username']; ?>)</a>
        </div>
    </nav>

    <div class="container">
        <h2>List an Authenticated Item</h2>
        <p style="color: #666;">Provide clear shots and honest wear descriptions for community verification.</p>
        
        <?php if($msg) echo "<p style='color: green; font-weight: bold;'>$msg</p>"; ?>

        <form method="POST" enctype="multipart/form-data" style="margin-top: 20px;">
            <input type="text" name="item_name" placeholder="Item Title (e.g., Vintage Nike Crewneck)" required>
            <input type="text" name="brand" placeholder="Brand Label (e.g., Nike, Puma)" required>
            <input type="number" step="0.01" name="price" placeholder="Price (ZAR)" required>
            
            <textarea name="description" placeholder="Describe the item condition, sizing details, and wear defects..." rows="5" style="width: 100%; border: 1px solid #ccc; border-radius: 4px; padding: 10px; box-sizing: border-box; margin-bottom: 15px;" required></textarea>
            
            <label style="display: block; text-align: left; margin-bottom: 5px; font-weight: bold; color: var(--pastimes-green);">Upload Piece Imagery:</label>
            <input type="file" name="item_image" required style="border: none; background: none; padding-left: 0;">

            <button type="submit" name="upload_item" class="btn-gold" style="margin-top: 15px; width: 100%;">Submit for Review</button>
        </form>
    </div>
</body>
</html>