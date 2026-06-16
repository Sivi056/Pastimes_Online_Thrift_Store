<?php
include 'DBConn.php';
session_start();

// Handle Account Approval
if (isset($_GET['approve'])) {
    $id = intval($_GET['approve']);
    // We update both common verification naming variations to be completely safe
    mysqli_query($conn, "UPDATE users SET isVerified = 1 WHERE id = $id");
    mysqli_query($conn, "UPDATE users SET status = 'Approved' WHERE id = $id");
    header("Location: admin.php");
    exit();
}

// Handle Account Deletion
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    mysqli_query($conn, "DELETE FROM users WHERE id = $id");
    header("Location: admin.php");
    exit();
}

// Safely pull users from database
$result = mysqli_query($conn, "SELECT * FROM users");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style.css">
    <title>Pastimes | Community Access Control</title>
</head>
<body>
    <nav>
        <div class="logo">PASTIMES</div>
        <div>
            <a href="index.php">Home</a>
            <a href="discovery.php">Discovery</a>
            <a href="logout.php">Logout</a>
        </div>
    </nav>

    <div class="container" style="max-width: 1000px; margin-top: 40px;">
        <div style="text-align: left; margin-bottom: 20px;">
            <h2 style="font-size: 24px; color: #333;">👤 Pastimes Community Access Control</h2>
            <p style="color: #666; margin-top: 5px;">Review incoming account registrations and manage buyer/seller marketplace access.</p>
        </div>

        <table style="width: 100%; border-collapse: collapse; margin-top: 20px; background: white; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.05);">
            <thead>
                <tr style="background: var(--pastimes-green, #006400); color: white; text-align: left;">
                    <th style="padding: 15px;">ID</th>
                    <th style="padding: 15px;">Name / User</th>
                    <th style="padding: 15px;">Email</th>
                    <th style="padding: 15px;">Account Role</th>
                    <th style="padding: 15px; text-align: center;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                if ($result && mysqli_num_rows($result) > 0): 
                    while($row = mysqli_fetch_assoc($result)): 
                        // FOOLPROOF USERNAME FALLBACK CHECK
                        $displayName = "Unknown User";
                        if (isset($row['username'])) {
                            $displayName = $row['username'];
                        } elseif (isset($row['name'])) {
                            $displayName = $row['name'];
                        } elseif (isset($row['Name'])) {
                            $displayName = $row['Name'];
                        } else {
                            $displayName = explode('@', $row['email'])[0]; // Use email prefix if username key is missing
                        }

                        // Determine current verification status safely
                        $isVerified = false;
                        if (isset($row['isVerified']) && ($row['isVerified'] == 1 || $row['isVerified'] === true)) {
                            $isVerified = true;
                        } elseif (isset($row['status']) && strtolower($row['status']) === 'approved') {
                            $isVerified = true;
                        }
                        
                        $role = $row['role'] ?? $row['AccountRole'] ?? 'Buyer';
                ?>
                    <tr style="border-bottom: 1px solid #eee;">
                        <td style="padding: 15px; font-weight: bold; color: #777;"><?php echo $row['id'] ?? $row['userId'] ?? 'N/A'; ?></td>
                        <td style="padding: 15px; font-weight: bold; color: #333;"><?php echo htmlspecialchars($displayName); ?></td>
                        <td style="padding: 15px; color: #555;"><?php echo htmlspecialchars($row['email'] ?? $row['Email'] ?? 'No Email'); ?></td>
                        <td style="padding: 15px; color: #666;"><span style="background: #f4f4f4; padding: 4px 8px; border-radius: 4px; font-size: 0.9em;"><?php echo htmlspecialchars($role); ?></span></td>
                        <td style="padding: 15px; text-align: center;">
                            <?php if (!$isVerified): ?>
                                <a href="admin.php?approve=<?php echo $row['id'] ?? $row['userId']; ?>" style="background: #2e7d32; color: white; padding: 6px 12px; border-radius: 4px; text-decoration: none; font-size: 0.85em; margin-right: 5px; font-weight: bold;">Approve</a>
                            <?php else: ?>
                                <span style="color: #2e7d32; font-weight: bold; margin-right: 10px; font-size: 0.9em;">✓ Active</span>
                            <?php endif; ?>
                            <a href="admin.php?delete=<?php echo $row['id'] ?? $row['userId']; ?>" onclick="return confirm('Delete this user?');" style="background: #c62828; color: white; padding: 6px 12px; border-radius: 4px; text-decoration: none; font-size: 0.85em; font-weight: bold;">Delete</a>
                        </td>
                    </tr>
                <?php 
                    endwhile; 
                else: 
                ?>
                    <tr>
                        <td colspan="5" style="padding: 30px; text-align: center; color: #777;">No user accounts found in database.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>