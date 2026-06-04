<?php
// FORCE ERROR REPORTING ON SO WE CAN SEE IF ANYTHING ELSE CLASHES
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'DBConn.php';
session_start();

// --- 1. HANDLE APPROVAL ACTION ---
if (isset($_GET['verify'])) 
    {
        $id = intval($_GET['verify']);
        // Maps directly to your enum 'Approved' status column
        mysqli_query($conn, "UPDATE user SET status = 'Approved' WHERE userId = $id");
        header("Location: admin.php"); 
        exit();
    }

// --- 2. HANDLE DELETION ACTION ---
if (isset($_GET['delete'])) 
    {
        $id = intval($_GET['delete']);
        mysqli_query($conn, "DELETE FROM user WHERE userId = $id");
        header("Location: admin.php"); 
        exit();
    }

// --- 3. FETCH ALL USERS ---
// Pulls all records cleanly. If 'status' exists, it groups Pending users at the top.
$users = mysqli_query($conn, "SELECT * FROM user ORDER BY (status = 'Pending') DESC, userId DESC");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Pastimes | Admin Oversight Panel</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
    .admin-table {
        width: 100%;
        border-collapse: collapse;
        background: white;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
        margin-top: 20px;
        font-family: sans-serif;
    }

    .admin-table th {
        background-color: #1a1a1a;
        color: white;
        padding: 12px;
        text-align: left;
    }

    .admin-table td {
        padding: 12px;
        border-bottom: 1px solid #eee;
    }

    .role-badge {
        background: #eee;
        padding: 3px 8px;
        border-radius: 4px;
        font-size: 0.85em;
        font-weight: bold;
    }

    .status-active {
        color: #2e7d32;
        font-weight: bold;
        background: #e8f5e9;
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 0.85em;
        display: inline-block;
    }

    .status-pending {
        color: #c62828;
        font-weight: bold;
        background: #ffebee;
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 0.85em;
        display: inline-block;
    }
    </style>
</head>

<body>
    <div class="container" style="max-width:950px; margin: 40px auto; padding: 20px;">
        <h1
            style="border-bottom: 2px solid #D4AF37; padding-bottom: 10px; font-family: 'Helvetica Neue', sans-serif; margin-top: 0;">
            <i class="fas fa-user-shield"></i> Pastimes Admin Operations Panel
        </h1>
        <p style="color: #555; margin-bottom: 25px;">Review incoming marketplace registrations and manage account status
            access.</p>

        <table class="admin-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email Address</th>
                    <th>System Role</th>
                    <th>Status Label</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($users && mysqli_num_rows($users) > 0): ?>
                <?php while($row = mysqli_fetch_assoc($users)): ?>
                <tr>
                    <td><?php echo $row['userId']; ?></td>

                    <td><strong><?php echo htmlspecialchars($row['username'] ?? $row['userName'] ?? 'Unknown'); ?></strong>
                    </td>
                    <td><?php echo htmlspecialchars($row['userEmail'] ?? 'No Email'); ?></td>

                    <td><span class="role-badge"><?php echo $row['role'] ?? 'User'; ?></span></td>

                    <td>
                        <?php 
                            $currentStatus = $row['status'] ?? 'Pending';
                            if($currentStatus === 'Approved'): 
                            ?>
                        <span class="status-active"><i class="fas fa-check-circle"></i> Approved Active</span>
                        <?php else: ?>
                        <span class="status-pending"><i class="fas fa-clock"></i> Pending Review</span>
                        <?php endif; ?>
                    </td>

                    <td>
                        <?php if(($row['status'] ?? '') !== 'Approved'): ?>
                        <a href="admin.php?verify=<?php echo $row['userId']; ?>"
                            style="color: #2e7d32; font-weight: bold; text-decoration: none; margin-right: 15px; font-size: 0.9em;">
                            <i class="fas fa-user-check"></i> Approve
                        </a>
                        <?php endif; ?>
                        <a href="admin.php?delete=<?php echo $row['userId']; ?>"
                            style="color:#c62828; text-decoration: none; font-weight: bold; font-size: 0.9em;">
                            <i class="fas fa-trash-alt"></i> Delete
                        </a>
                    </td>
                </tr>
                <?php endwhile; ?>
                <?php else: ?>
                <tr>
                    <td colspan="6" style="text-align: center; padding: 40px; color: #777;">
                        <i class="fas fa-folder-open"
                            style="font-size: 2.5em; color: #D4AF37; margin-bottom: 10px; display: block;"></i>
                        No users found. Run loadClothingStore.php to populate the data files.
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <div style="margin-top: 35px;">
            <a href="index.php"
                style="text-decoration: none; display: inline-block; padding: 12px 24px; font-weight: bold; background: #1a1a1a; color: white; border-radius: 4px;">
                <i class="fas fa-arrow-left"></i> Exit Dashboard
            </a>
        </div>
    </div>
</body>

</html>