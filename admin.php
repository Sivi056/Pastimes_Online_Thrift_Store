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
    // Approve item and set status to active/Available
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
    
    // Handle our custom explicit verification checkbox field
    $isVerified = isset($_POST['is_verified']) ? 1 : 0;
    
    mysqli_query($conn, "UPDATE product SET itemName='$itemName', brand='$brand', price=$price, status='$status', isVerified=$isVerified WHERE productId = $id");
    header("Location: admin.php?tab=products");
    exit();
}

// --- DATA FETCHING ---
$currentTab = $_GET['tab'] ?? 'users';
$userResult = mysqli_query($conn, "SELECT * FROM `user` ORDER BY isVerified ASC, userId DESC");
$productResult = mysqli_query($conn, "SELECT * FROM product ORDER BY status DESC, productId DESC");

// Fetch single item for editing forms if requested
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
        <p style="color: #666;">Complete CRUD capability across marketplace user profiles and clothing inventories.</p>

        <?php if ($editUser): ?>
        <div class="form-card">
            <h3><i class="fas fa-user-edit"></i> Edit User Information</h3>
            <form method="POST" style="display: flex; gap: 15px; align-items: flex-end;">
                <input type="hidden" name="user_id" value="<?php echo $editUser['userId']; ?>">
                <div style="flex: 1;">
                    <label style="font-weight:bold; display:block; margin-bottom:5px;">Name:</label>
                    <input type="text" name="user_name" value="<?php echo htmlspecialchars($editUser['userName']); ?>"
                        required style="width:100%; padding:8px;">
                </div>
                <div style="flex: 1;">
                    <label style="font-weight:bold; display:block; margin-bottom:5px;">Email:</label>
                    <input type="email" name="user_email"
                        value="<?php echo htmlspecialchars($editUser['userEmail']); ?>" required
                        style="width:100%; padding:8px;">
                </div>
                <div>
                    <label style="font-weight:bold; display:block; margin-bottom:5px;">Role:</label>
                    <select name="role" style="padding:8px;">
                        <option value="Buyer" <?php if($editUser['role']=='Buyer') echo 'selected'; ?>>Buyer</option>
                        <option value="Seller" <?php if($editUser['role']=='Seller') echo 'selected'; ?>>Seller</option>
                        <option value="Admin" <?php if($editUser['role']=='Admin') echo 'selected'; ?>>Admin</option>
                    </select>
                </div>
                <button type="submit" name="update_user" class="btn-gold" style="padding:9px 20px;">Save User</button>
                <a href="admin.php?tab=users"
                    style="padding:9px 15px; background:#999; color:white; text-decoration:none; border-radius:4px;">Cancel</a>
            </form>
        </div>
        <?php endif; ?>

        <?php if ($editProduct): ?>
        <div class="form-card">
            <h3><i class="fas fa-edit"></i> Edit Piece Inventory Listing</h3>
            <form method="POST" style="display: flex; flex-wrap: wrap; gap: 15px; align-items: flex-end;">
                <input type="hidden" name="product_id" value="<?php echo $editProduct['productId']; ?>">
                <div style="flex: 1; min-width: 200px;">
                    <label style="font-weight:bold; display:block; margin-bottom:5px;">Piece Title:</label>
                    <input type="text" name="item_name"
                        value="<?php echo htmlspecialchars($editProduct['itemName']); ?>" required
                        style="width:100%; padding:8px;">
                </div>
                <div style="flex: 1; min-width: 150px;">
                    <label style="font-weight:bold; display:block; margin-bottom:5px;">Brand:</label>
                    <input type="text" name="brand" value="<?php echo htmlspecialchars($editProduct['brand']); ?>"
                        required style="width:100%; padding:8px;">
                </div>
                <div>
                    <label style="font-weight:bold; display:block; margin-bottom:5px;">Price (R):</label>
                    <input type="number" step="0.01" name="price" value="<?php echo $editProduct['price']; ?>" required
                        style="width:100px; padding:8px;">
                </div>
                <div>
                    <label style="font-weight:bold; display:block; margin-bottom:5px;">Listing Status:</label>
                    <select name="status" style="padding:8px;">
                        <option value="Pending Approval"
                            <?php if($editProduct['status']=='Pending Approval') echo 'selected'; ?>>Pending Approval
                        </option>
                        <option value="Available" <?php if($editProduct['status']=='Available') echo 'selected'; ?>>
                            Available</option>
                        <option value="Sold" <?php if($editProduct['status']=='Sold') echo 'selected'; ?>>Sold</option>
                    </select>
                </div>
                <div style="padding-bottom: 10px; display: flex; align-items: center; gap: 8px;">
                    <input type="checkbox" name="is_verified" id="is_verified" value="1"
                        <?php if(($editProduct['isVerified'] ?? 0) == 1) echo 'checked'; ?>
                        style="transform: scale(1.3); cursor:pointer;">
                    <label for="is_verified" style="font-weight: bold; color: #b58900; cursor:pointer;"><i
                            class="fas fa-certificate"></i> Verified Badge</label>
                </div>
                <div>
                    <button type="submit" name="update_product" class="btn-gold" style="padding:9px 20px;">Save
                        Product</button>
                    <a href="admin.php?tab=products"
                        style="padding:9px 15px; background:#999; color:white; text-decoration:none; border-radius:4px;">Cancel</a>
                </div>
            </form>
        </div>
        <?php endif; ?>

        <div style="margin-top: 30px; border-bottom: 2px solid var(--pastimes-green, #006400);">
            <a href="admin.php?tab=users" class="tab-btn <?php echo ($currentTab == 'users') ? 'active' : ''; ?>"><i
                    class="fas fa-users"></i> Users Control</a>
            <a href="admin.php?tab=products"
                class="tab-btn <?php echo ($currentTab == 'products') ? 'active' : ''; ?>"><i class="fas fa-tshirt"></i>
                Products Inventory</a>
        </div>

        <?php if ($currentTab == 'users'): ?>
        <table
            style="width: 100%; border-collapse: collapse; background: white; box-shadow: 0 4px 15px rgba(0,0,0,0.05);">
            <thead>
                <tr style="background: var(--pastimes-green, #006400); color: white; text-align: left;">
                    <th style="padding: 15px;">ID</th>
                    <th style="padding: 15px;">Profile Name</th>
                    <th style="padding: 15px;">Email Domain</th>
                    <th style="padding: 15px;">System Role</th>
                    <th style="padding: 15px;">Status</th>
                    <th style="padding: 15px; text-align: center;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = mysqli_fetch_assoc($userResult)): ?>
                <tr style="border-bottom: 1px solid #eee; color: #333;">
                    <td style="padding: 15px;"><?php echo $row['userId']; ?></td>
                    <td style="padding: 15px; font-weight: bold;">
                        <?php echo htmlspecialchars($row['userName'] ?? 'User'); ?></td>
                    <td style="padding: 15px;"><?php echo htmlspecialchars($row['userEmail'] ?? 'No Email'); ?></td>
                    <td style="padding: 15px;"><span
                            style="background:#eee; padding:3px 8px; border-radius:3px; font-size:0.85em; font-weight:bold;"><?php echo htmlspecialchars($row['role'] ?? 'Buyer'); ?></span>
                    </td>
                    <td style="padding: 15px;">
                        <?php echo ($row['isVerified'] == 1) ? '<span style="color:green; font-weight:bold;">Active</span>' : '<span style="color:orange; font-weight:bold;">Pending Approval</span>'; ?>
                    </td>
                    <td style="padding: 15px; text-align: center;">
                        <?php if($row['isVerified'] == 0): ?>
                        <a href="admin.php?approve_user=<?php echo $row['userId']; ?>"
                            style="color: #2e7d32; font-weight: bold; text-decoration: none; margin-right: 12px;"><i
                                class="fas fa-check"></i> Approve</a>
                        <?php endif; ?>
                        <a href="admin.php?tab=users&edit_user=<?php echo $row['userId']; ?>"
                            style="color: #b58900; font-weight: bold; text-decoration: none; margin-right: 12px;"><i
                                class="fas fa-edit"></i> Edit</a>
                        <a href="admin.php?delete_user=<?php echo $row['userId']; ?>"
                            onclick="return confirm('Permanently remove this user account profile?')"
                            style="color: #c62828; font-weight: bold; text-decoration: none;"><i
                                class="fas fa-trash"></i> Delete</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <?php else: ?>
        <table
            style="width: 100%; border-collapse: collapse; background: white; box-shadow: 0 4px 15px rgba(0,0,0,0.05);">
            <thead>
                <tr style="background: var(--pastimes-green, #006400); color: white; text-align: left;">
                    <th style="padding: 15px;">Preview</th>
                    <th style="padding: 15px;">Piece Title</th>
                    <th style="padding: 15px;">Brand</th>
                    <th style="padding: 15px;">Price</th>
                    <th style="padding: 15px;">Authentication Status</th>
                    <th style="padding: 15px;">State</th>
                    <th style="padding: 15px; text-align: center;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while($pRow = mysqli_fetch_assoc($productResult)): ?>
                <tr style="border-bottom: 1px solid #eee; color: #333;">
                    <td style="padding: 12px;">
                        <img src="<?php echo htmlspecialchars($pRow['imagePath']); ?>"
                            style="width: 45px; height: 45px; object-fit: cover; border-radius: 4px;"
                            onerror="this.src='Images/default_item.png.jpeg';">
                    </td>
                    <td style="padding: 15px; font-weight: bold;"><?php echo htmlspecialchars($pRow['itemName']); ?>
                    </td>
                    <td style="padding: 15px;"><?php echo htmlspecialchars($pRow['brand']); ?></td>
                    <td style="padding: 15px; font-weight: bold; color: var(--pastimes-green, #006400);">R
                        <?php echo number_format($pRow['price'], 2); ?></td>
                    <td style="padding: 15px;">
                        <?php if(($pRow['isVerified'] ?? 0) == 1): ?>
                        <span
                            style="background: #fff8e1; color: #b58900; padding: 4px 8px; border-radius: 20px; font-size: 0.8em; font-weight: bold; border: 1px solid #ffe082;"><i
                                class="fas fa-certificate"></i> VERIFIED</span>
                        <?php else: ?>
                        <span style="color: #777; font-size: 0.9em; font-style: italic;">Standard</span>
                        <?php endif; ?>
                    </td>
                    <td style="padding: 15px;">
                        <?php 
                                if($pRow['status'] == 'Available') echo '<span style="color:green; font-weight:bold;">Live</span>';
                                elseif($pRow['status'] == 'Sold') echo '<span style="color:#777; font-weight:bold; text-decoration:line-through;">Sold</span>';
                                else echo '<span style="color:orange; font-weight:bold;">Awaiting Audit</span>';
                            ?>
                    </td>
                    <td style="padding: 15px; text-align: center;">
                        <?php if($pRow['status'] == 'Pending Approval'): ?>
                        <a href="admin.php?approve_product=<?php echo $pRow['productId']; ?>"
                            style="color: #2e7d32; font-weight: bold; text-decoration: none; margin-right: 12px;"><i
                                class="fas fa-check-circle"></i> Go Live</a>
                        <?php endif; ?>
                        <a href="admin.php?tab=products&edit_product=<?php echo $pRow['productId']; ?>"
                            style="color: #b58900; font-weight: bold; text-decoration: none; margin-right: 12px;"><i
                                class="fas fa-edit"></i> Edit</a>
                        <a href="admin.php?delete_product=<?php echo $pRow['productId']; ?>"
                            onclick="return confirm('Permanently wipe this product listing from database?')"
                            style="color: #c62828; font-weight: bold; text-decoration: none;"><i
                                class="fas fa-trash"></i> Delete</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <?php endif; ?>
    </div>
</body>

</html>