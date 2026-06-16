<?php
// FORCE ERROR REPORTING ON SO WE CAN TRACE ANY UNEXPECTED BEHAVIOR
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'DBConn.php';
session_start();

// --- 1. HANDLE APPROVAL ACTION ---
if (isset($_GET['verify'])) 
    {
        $id = intval($_GET['verify']);
        // Maps directly to your column: 'isVerified' with a capital V
        mysqli_query($conn, "UPDATE user SET isVerified = 1 WHERE userId = $id");
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
// Clean query targeting the exact table 'user' used in register.php
$users = mysqli_query($conn, "SELECT * FROM user");

// Safeguard against the Fatal error on line 53 if the database context fails
if (!$users) {
    die("<div style='padding: 20px; background: #ffebee; color: #c62828; font-family: sans-serif; border-radius: 4px; margin: 20px;'>
            <strong>Database Query Failed:</strong> " . mysqli_error($conn) . " 
            <br><small>Check if your local table is named 'user' and contains the columns 'userId', 'userName', 'userEmail', 'role', and 'isVerified'.</small>
         </div>");
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Pastimes | Admin Oversight Panel</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background-color: #f9f9f9;
        margin: 0;
        padding: 0;
    }

    .admin-container {
        max-width: 1050px;
        margin: 40px auto;
        padding: 30px;
        background: white;
        border-radius: 8px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
    }

    .admin-table {
        width: 100%;
        border-collapse: collapse;
        background: white;
        margin-top: 20px;
    }

    .admin-table th {
        background-color: #006400;
        /* Matching your dark green theme header */
        color: white;
        padding: 14px;
        text-align: left;
        font-size: 0.95em;
    }

    .admin-table td {
        padding: 14px;
        border-bottom: 1px solid #eee;
        color: #333;
        font-size: 0.95em;
    }

    .role-badge {
        background: #eef2f7;
        padding: 4px 10px;
        border-radius: 4px;
        font-size: 0.85em;
        font-weight: 600;
        color: #4a5568;
    }

    .status-active {
        color: #2e7d32;
        font-weight: bold;
        background: #e8f5e9;
        padding: 6px 12px;
        border-radius: 4px;
        font-size: 0.85em;
        display: inline-block;
    }

    .status-pending {
        color: #c62828;
        font-weight: bold;
        background: #ffebee;
        padding: 6px 12px;
        border-radius: 4px;
        font-size: 0.85em;
        display: inline-block;
    }

    .action-btn {
        text-decoration: none;
        font-weight: bold;
        font-size: 0.9em;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }
    </style>
</head>

<body>
    <div class="admin-container">
        <h1
            style="border-bottom: 2px solid #D4AF37; padding-bottom: 15px; font-family: 'Helvetica Neue', sans-serif; margin-top: 0; color: #1a1a1a;">
            <i class="fas fa-user-shield"></i> Pastimes Community Access Control
        </h1>
        <p style="color: #666; margin-bottom: 25px;">Review incoming account registrations and manage buyer/seller
            marketplace access.</p>

        <table class="admin-table">
            <thead>
                <tr>
                    <th style="width: 8%;">ID</th>
                    <th style="width: 25%;">Name</th>
                    <th style="width: 25%;">Email</th>
                    <th style="width: 15%;">Account Role</th>
                    <th style="width: 12%;">Status</th>
                    <th style="width: 15%;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (mysqli_num_rows($users) > 0): ?>
                <?php while($row = mysqli_fetch_assoc($users)): ?>
                <tr>
                    <td><?php echo $row['userId'] ?? $row['userid'] ?? $row['id'] ?? '—'; ?></td>

                    <td>
                        <strong>
                            <?php 
                            if (!empty($row['userName'])) {
                                echo htmlspecialchars($row['userName']);
                            } elseif (!empty($row['username'])) {
                                echo htmlspecialchars($row['username']);
                            } else {
                                echo 'Unknown Profile';
                            }
                            ?>
                        </strong>
                    </td>

                    <td><?php echo htmlspecialchars($row['userEmail'] ?? $row['email'] ?? 'No Email Registered'); ?>
                    </td>
                    <td><span
                            class="role-badge"><?php echo htmlspecialchars(!empty($row['role']) ? $row['role'] : 'Unassigned'); ?></span>
                    </td>

                    <td>
                        <?php if(isset($row['isVerified']) && ($row['isVerified'] == 1 || $row['isVerified'] == '1')): ?>
                        <span class="status-active"><i class="fas fa-check-circle"></i> Active</span>
                        <?php else: ?>
                        <span class="status-pending"><i class="fas fa-clock"></i> Pending Review</span>
                        <?php endif; ?>
                    </td>

                    <td>
                        <?php if(!isset($row['isVerified']) || $row['isVerified'] == 0): ?>
                        <a href="admin.php?verify=<?php echo $row['userId'] ?? $row['userid'] ?? $row['id']; ?>"
                            class="action-btn" style="color: #2e7d32; margin-right: 15px;">
                            <i class="fas fa-user-check"></i> Approve
                        </a>
                        <?php endif; ?>
                        <a href="admin.php?delete=<?php echo $row['userId'] ?? $row['userid'] ?? $row['id']; ?>"
                            class="action-btn" style="color: #c62828;">
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
                        No users currently found in your database. Run your initialization scripts.
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