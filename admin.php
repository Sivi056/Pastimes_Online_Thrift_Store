<?php
include 'DBConn.php';
session_start();

// Handle Account Approval Safely
if (isset($_GET['approve'])) {
    $id = intval($_GET['approve']);
    mysqli_query($conn, "UPDATE user SET status = 'Approved' WHERE id = $id OR userId = $id");
    header("Location: admin.php");
    exit();
}

// Handle Account Deletion Safely
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    mysqli_query($conn, "DELETE FROM user WHERE id = $id OR userId = $id");
    header("Location: admin.php");
    exit();
}

// RUN THE QUERY AND CATCH THE EXACT ERROR IF IT FAILS
$result = mysqli_query($conn, "SELECT * FROM user");

if (!$result) {
    // This stops the fatal crash and prints the exact SQL breakdown on your screen
    die("<div style='background: #f8d7da; color: #721c24; padding: 25px; margin: 40px auto; max-width: 800px; border: 1px solid #f5c6cb; font-family: monospace; border-radius: 6px;'>" .
        "<h2>🛑 MySQL Query Failed!</h2>" .
        "<strong>The database says:</strong> " . mysqli_error($conn) . 
        "</div>");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style.css">
    <title>Pastimes | Admin Oversight Dashboard</title>
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
        <h2>👤 Pastimes Community Access Control</h2>
        <p style="color: #666;">Review incoming account registrations and manage buyer/seller marketplace access.</p>

        <table style="width: 100%; border-collapse: collapse; margin-top: 20px; background: white; box-shadow: 0 4px 15px rgba(0,0,0,0.05);">
            <thead>
                <tr style="background: var(--pastimes-green, #006400); color: white; text-align: left;">
                    <th style="padding: 15px;">ID</th>
                    <th style="padding: 15px;">Name</th>
                    <th style="padding: 15px;">Email</th>
                    <th style="padding: 15px;">Account Role</th>
                    <th style="padding: 15px; text-align: center;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = mysqli_fetch_assoc($result)): ?>
                    <tr style="border-bottom: 1px solid #eee; color: #333;">
                        <td style="padding: 15px;"><?php echo $row['id'] ?? $row['userId'] ?? 'N/A'; ?></td>
                        <td style="padding: 15px; font-weight: bold;"><?php echo htmlspecialchars($row['username'] ?? $row['name'] ?? 'User'); ?></td>
                        <td style="padding: 15px;"><?php echo htmlspecialchars($row['email'] ?? 'No Email'); ?></td>
                        <td style="padding: 15px;"><?php echo htmlspecialchars($row['role'] ?? 'Buyer'); ?></td>
                        <td style="padding: 15px; text-align: center;">
                            <span style="color: #2e7d32; font-weight: bold;">Active</span>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>