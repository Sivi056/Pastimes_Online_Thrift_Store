<?php
include 'DBConn.php';
session_start();

// --- USER ACTIONS ---
if (isset($_GET['approve_user'])) {
    $id = intval($_GET['approve_user']);
    mysqli_query($conn, "UPDATE `user` SET isVerified = 1 WHERE userId = $id");
    header("Location: admin.php?tab=users");
    exit();
}

if (isset($_GET['delete_user'])) {
    $id = intval($_GET['delete_user']);
    mysqli_query($conn, "DELETE FROM `user` WHERE userId = $id");
    header("Location: admin.php?tab=users");
    exit();
}

if (isset($_POST['update_user'])) {
    $id = intval($_POST['user_id']);
    $name = mysqli_real_escape_string($conn, $_POST['user_name']);
    $email = mysqli_real_escape_string($conn, $_POST['user_email']);
    $role = mysqli_real_escape_string($conn, $_POST['role']);
    
    mysqli_query($conn, "UPDATE `user` SET userName='$name', userEmail='$email', role='$role' WHERE userId = $id");
    header("Location: admin.php?tab=users");
    exit();
}

// --- PRODUCT ACTIONS ---
if (isset($_GET['approve_product'])) {
    $id = intval($_GET['approve_product']);
    // Update status to 'Available' so the Discovery Feed query picks it up immediately
    mysqli_query($conn, "UPDATE product SET status = 'Available' WHERE productId = $id");
    header("Location: admin.php?tab=products");
    exit();
}

if (isset($_GET['delete_product'])) {
    $id = intval($_GET['delete_product']);
    mysqli_query($conn, "DELETE FROM product WHERE productId = $id");
    header("Location: admin.php?tab=products");
    exit();
}

if (isset($_POST['update_product'])) {
    $id = intval($_POST['product_id']);
    $itemName = mysqli_real_escape_string($conn, $_POST['item_name']);
    $brand = mysqli_real_escape_string($conn, $_POST['brand']);
    $price = floatval($_POST['price']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);
    
    // Explicit verification checkbox logic
    $isVerified = isset($_POST['is_verified']) ? 1 : 0;
    
    mysqli_query($conn, "UPDATE product SET itemName='$itemName', brand='$brand', price=$price, status='$status', isVerified=$isVerified WHERE productId = $id");
    header("Location: admin.php?tab=products");
    exit();
}

// --- DATA FETCHING ---
$currentTab = $_GET['tab'] ?? 'users';
$userResult = mysqli_query($conn, "SELECT * FROM `user` ORDER BY isVerified ASC, userId DESC");
$productResult = mysqli_query($conn, "SELECT * FROM product ORDER BY status DESC, productId DESC");

$editUser = null;
if (isset($_GET['edit_user'])) {
    $editId = intval($_GET['edit_user']);
    $editUser = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM `user` WHERE userId = $editId"));
}

$editProduct = null;
if (isset($_GET['edit_product'])) {
    $editId = intval($_GET['edit_product']);
    $editProduct = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM product WHERE productId = $editId"));
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <title>Pastimes | Admin Oversight Dashboard</title>
    <style>
    .tab-btn {
        padding: 12px 25px;
        font-weight: bold;
        cursor: pointer;
        border: none;
        background: #ddd;
        color: #333;
        border-radius: 4px 4px 0 0;
        margin-right: 5px;
        text-decoration: none;
        display: inline-block;
    }

    .tab-btn.active {
        background: var(--pastimes-green, #006400);
        color: white;
    }

    .form-card {
        background: #fff;
        padding: 25px;
        border-radius: 6px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        margin-bottom: 30px;
        color: #333;
        border-left: 5px solid var(--pastimes-green, #006400);
    }
    </style>
</head>

<body>
    <nav>
        <div class="logo">PASTIMES Dashboard</div>
        <div>
            <a href="index.php">Home</a>
            <a href="discovery.php">Discovery</a>
            <a href="logout.php">Logout</a>
        </div>
    </nav>

    <div class="container" style="max-width: 1100px; margin-top: 40px;">
        <h2>⚙️ System Administration Hub</h2>
        <p style="color: #666;">Manage users and approve marketplace inventory.</p>

        <?php if ($editUser): ?>
        <div class="form-card">
            <h3><i class="fas fa-user-edit"></i> Edit User</h3>
            <form method="POST" style="display: flex; gap: 15px; align-items: flex-end;">
                <input type="hidden" name="user_id" value="<?php echo $editUser['userId']; ?>">
                <div style="flex: 1;">
                    <label>Name:</label>
                    <input type="text" name="user_name" value="<?php echo htmlspecialchars($editUser['userName']); ?>"
                        required style="width:100%; padding:8px;">
                </div>
                <div style="flex: 1;">
                    <label>Email:</label>
                    <input type="email" name="user_email"
                        value="<?php echo htmlspecialchars($editUser['userEmail']); ?>" required
                        style="width:100%; padding:8px;">
                </div>
                <div>
                    <label>Role:</label>
                    <select name="role" style="padding:8px;">
                        <option value="Buyer" <?php if($editUser['role']=='Buyer') echo 'selected'; ?>>Buyer</option>
                        <option value="Seller" <?php if($editUser['role']=='Seller') echo 'selected'; ?>>Seller</option>
                        <option value="Admin" <?php if($editUser['role']=='Admin') echo 'selected'; ?>>Admin</option>
                    </select>
                </div>
                <button type="submit" name="update_user" class="btn-gold" style="padding:9px 20px;">Save User</button>
            </form>
        </div>
        <?php endif; ?>

        <?php if ($editProduct): ?>
        <div class="form-card">
            <h3><i class="fas fa-edit"></i> Edit Piece Inventory</h3>
            <form method="POST" style="display: flex; flex-wrap: wrap; gap: 15px; align-items: flex-end;">
                <input type="hidden" name="product_id" value="<?php echo $editProduct['productId']; ?>">
                <div style="flex: 1; min-width: 200px;">
                    <label>Piece Title:</label>
                    <input type="text" name="item_name"
                        value="<?php echo htmlspecialchars($editProduct['itemName']); ?>" required
                        style="width:100%; padding:8px;">
                </div>
                <div style="flex: 1; min-width: 150px;">
                    <label>Brand:</label>
                    <input type="text" name="brand" value="<?php echo htmlspecialchars($editProduct['brand']); ?>"
                        required style="width:100%; padding:8px;">
                </div>
                <div>
                    <label>Price (R):</label>
                    <input type="number" step="0.01" name="price" value="<?php echo $editProduct['price']; ?>" required
                        style="width:100px; padding:8px;">
                </div>
                <div>
                    <label>Status:</label>
                    <select name="status" style="padding:8px;">
                        <option value="Pending Approval"
                            <?php if($editProduct['status']=='Pending Approval') echo 'selected'; ?>>Pending Approval
                        </option>
                        <option value="Available" <?php if($editProduct['status']=='Available') echo 'selected'; ?>>
                            Available</option>
                        <option value="Sold" <?php if($editProduct['status']=='Sold') echo 'selected'; ?>>Sold</option>
                    </select>
                </div>
                <div style="display: flex; align-items: center; gap: 8px;">
                    <input type="checkbox" name="is_verified" id="is_verified" value="1"
                        <?php if(($editProduct['isVerified'] ?? 0) == 1) echo 'checked'; ?>>
                    <label for="is_verified" style="font-weight: bold; color: #b58900;">Verified Badge</label>
                </div>
                <button type="submit" name="update_product" class="btn-gold" style="padding:9px 20px;">Save
                    Product</button>
            </form>
        </div>
        <?php endif; ?>

        <div style="margin-top: 30px; border-bottom: 2px solid var(--pastimes-green, #006400);">
            <a href="admin.php?tab=users" class="tab-btn <?php echo ($currentTab == 'users') ? 'active' : ''; ?>"><i
                    class="fas fa-users"></i> Users</a>
            <a href="admin.php?tab=products"
                class="tab-btn <?php echo ($currentTab == 'products') ? 'active' : ''; ?>"><i class="fas fa-tshirt"></i>
                Inventory</a>
        </div>

        <?php if ($currentTab == 'users'): ?>
        <table
            style="width: 100%; border-collapse: collapse; background: white; box-shadow: 0 4px 15px rgba(0,0,0,0.05);">
        </table>
        <?php else: ?>
        <table
            style="width: 100%; border-collapse: collapse; background: white; box-shadow: 0 4px 15px rgba(0,0,0,0.05);">
        </table>
        <?php endif; ?>
    </div>
</body>

</html>