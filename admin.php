<?php
include 'DBConn.php';
session_start();

//HANDLING VERIFICATION ACTION 
if (isset($_GET['verify'])) {
    $id = intval($_GET['verify']);
    // Using an updated, clean integer flag approach for verification status
    mysqli_query($conn, "UPDATE user SET is_verified = 1 WHERE userId = $id");
    header("Location: admin.php"); 
    exit();
}

//HANDLING DELETE ACTION
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    mysqli_query($conn, "DELETE FROM user WHERE userId = $id");
    header("Location: admin.php"); 
    exit();
}

//FETCHING THE USERS
// Orders by verification status so unverified/pending registrations bubble to the top!
$users = mysqli_query($conn, "SELECT * FROM user ORDER BY is_verified ASC, userId DESC");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Pastimes | Admin Oversight Dashboard</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body>
    <div class="container" style="max-width:950px; margin: 40px auto; padding: 20px;">
        <h1 style="border-bottom: 2px solid var(--pastimes-gold); padding-bottom: 10px;">
            <i class="fas fa-user-shield"></i> Pastimes Community Access Control
        </h1>
        <p>Review incoming account registrations and manage buyer/seller marketplace access.</p>

        <table style="width:100%; border-collapse: collapse; margin-top: 20px;">
            <tr style="background-color: #1a1a1a; color: white;">
                <th style="padding: 12px; text-align: left;">ID</th>
                <th style="padding: 12px; text-align: left;">Name</th>
                <th style="padding: 12px; text-align: left;">Email</th>
                <th style="padding: 12px; text-align: left;">Account Role</th>
                <th style="padding: 12px; text-align: left;">Status</th>
                <th style="padding: 12px; text-align: left;">Actions</th>
            </tr>

            <?php if (mysqli_num_rows($users) > 0): ?>
            <?php while($row = mysqli_fetch_assoc($users)): ?>
            <tr style="border-bottom: 1px solid #ddd;">
                <td style="padding: 12px;"><?php echo $row['userId']; ?></td>
                <td style="padding: 12px;"><strong><?php echo htmlspecialchars($row['username']); ?></strong></td>
                <td style="padding: 12px;"><?php echo htmlspecialchars($row['userEmail']); ?></td>
                <td style="padding: 12px;"><span class="role-tag"><?php echo $row['role']; ?></span></td>

                <td style="padding: 12px;">
                    <?php if(isset($row['is_verified']) && $row['is_verified'] == 1): ?>
                    <style>
                    .verified-label {
                        color: #2e7d32;
                        font-weight: bold;
                        background: #e8f5e9;
                        padding: 4px 8px;
                        border-radius: 4px;
                        display: inline-block;
                    }
                    </style>
                    <span class="verified-label"><i class="fas fa-check-circle"></i> Active</span>
                    <?php else: ?>
                    <style>
                    .pending-label {
                        color: #c62828;
                        font-weight: bold;
                        background: #ffebee;
                        padding: 4px 8px;
                        border-radius: 4px;
                        display: inline-block;
                    }
                    </style>
                    <span class="pending-label"><i class="fas fa-clock"></i> Pending Approval</span>
                    <?php endif; ?>
                </td>

                <td style="padding: 12px;">
                    <?php if(!isset($row['is_verified']) || $row['is_verified'] == 0): ?>
                    <a href="admin.php?verify=<?php echo $row['userId']; ?>"
                        style="color: #2e7d32; font-weight: bold; text-decoration: none; margin-right: 10px;">
                        <i class="fas fa-user-check"></i> Approve
                    </a>
                    <?php endif; ?>
                    <a href="admin.php?delete=<?php echo $row['userId']; ?>"
                        style="color:red; text-decoration: none; font-weight: bold;">
                        <i class="fas fa-trash-alt"></i> Delete
                    </a>
                </td>
            </tr>
            <?php endwhile; ?>
            <?php else: ?>
            <tr>
                <td colspan="6" style="text-align: center; padding: 30px; color: #666;">
                    <i class="fas fa-folder-open" style="font-size: 2em; color: var(--pastimes-gold);"></i><br>
                    No user accounts found in the database. Run loadClothingStore.php to populate.
                </td>
            </tr>
            <?php endif; ?>
        </table>

        <div style="margin-top: 30px;">
            <a href="index.php" class="btn-gold"
                style="text-decoration: none; display: inline-block; padding: 10px 20px;">
                <i class="fas fa-home"></i> Back to Homepage
            </a>
        </div>
    </div>
</body>

</html>