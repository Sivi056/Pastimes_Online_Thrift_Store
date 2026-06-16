<?php
include 'DBConn.php';
session_start();

echo "<div style='background: #fff3cd; color: #856404; padding: 15px; border: 1px solid #ffeeba; font-family: monospace; border-radius: 5px; margin: 20px auto; max-width: 900px;'>";
echo "<h3>🔍 PASTIMES DATABASE DIAGNOSTIC UTILITY</h3>";

// 1. Check if Connection exists
if (!isset($conn)) {
    die("❌ Error: The database connection variable '\$conn' is not initialized. Check your DBConn.php file.");
} else {
    echo "✅ Database connection variable detected.<br>";
}

// 2. Discover existing tables in your local database
echo "<br><strong>📋 Available tables in your database:</strong><br>";
$table_check = mysqli_query($conn, "SHOW TABLES");
$found_user_table = "";

if ($table_check) {
    while ($t_row = mysqli_fetch_array($table_check)) {
        $tableName = $t_row[0];
        echo " • " . $tableName . "<br>";
        if (strtolower($tableName) == 'users' || strtolower($tableName) == 'user' || strlike($tableName, 'user')) {
            $found_user_table = $tableName;
        }
    }
} else {
    echo "❌ Could not list tables: " . mysqli_error($conn) . "<br>";
}

// 3. Fallback table router logic
if (empty($found_user_table)) {
    echo "<br>❌ <strong>CRITICAL ERROR:</strong> No table representing users/accounts was automatically detected. Please run your database setup script or create a 'users' table.<br>";
    // Set a dummy query to prevent fatal script failure below
    $result = false;
} else {
    echo "<br>🎯 Found table target: <strong>$found_user_table</strong><br>";
    
    // Check columns inside that table so we know the column names!
    echo "<strong>🗂️ Table Columns inside '$found_user_table':</strong> ";
    $col_check = mysqli_query($conn, "SHOW COLUMNS FROM $found_user_table");
    $columns = [];
    while($c_row = mysqli_fetch_assoc($col_check)) {
        $columns[] = $c_row['Field'];
    }
    echo implode(', ', $columns) . "<br>";

    // Run query on the table that actually exists
    $result = mysqli_query($conn, "SELECT * FROM $found_user_table");
}
echo "</div>";

// Helper function to loosely search strings
function strlike($haystack, $needle) {
    return strpos(strtolower($haystack), strtolower($needle)) !== false;
}

// Handle Account Approval Safely
if (isset($_GET['approve']) && !empty($found_user_table)) {
    $id = intval($_GET['approve']);
    $id_column = in_array('userId', $columns) ? 'userId' : 'id';
    $status_column = in_array('status', $columns) ? 'status' : (in_array('isVerified', $columns) ? 'isVerified' : '');
    
    if($status_column == 'status') {
        mysqli_query($conn, "UPDATE $found_user_table SET status = 'Approved' WHERE $id_column = $id");
    } elseif($status_column == 'isVerified') {
        mysqli_query($conn, "UPDATE $found_user_table SET isVerified = 1 WHERE $id_column = $id");
    }
    header("Location: admin.php");
    exit();
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

    <div class="container" style="max-width: 1000px; margin-top: 20px;">
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
                        // Safe extraction helpers
                        $primaryId = $row['id'] ?? $row['userId'] ?? 0;
                        $emailAddr = $row['email'] ?? $row['Email'] ?? 'No Email';
                        $accRole = $row['role'] ?? $row['AccountRole'] ?? 'Buyer';
                        
                        $displayName = "Unknown";
                        if(isset($row['username'])) $displayName = $row['username'];
                        elseif(isset($row['name'])) $displayName = $row['name'];
                        else $displayName = explode('@', $emailAddr)[0];

                        $isVerified = false;
                        if (isset($row['status']) && strtolower($row['status']) == 'approved') $isVerified = true;
                        if (isset($row['isVerified']) && $row['isVerified'] == 1) $isVerified = true;
                ?>
                    <tr style="border-bottom: 1px solid #eee; color: #333;">
                        <td style="padding: 15px; font-weight: bold; color: #777;"><?php echo $primaryId; ?></td>
                        <td style="padding: 15px; font-weight: bold;"><?php echo htmlspecialchars($displayName); ?></td>
                        <td style="padding: 15px;"><?php echo htmlspecialchars($emailAddr); ?></td>
                        <td style="padding: 15px;"><span style="background: #f4f4f4; padding: 4px 8px; border-radius: 4px; font-size: 0.9em;"><?php echo htmlspecialchars($accRole); ?></span></td>
                        <td style="padding: 15px; text-align: center;">
                            <?php if (!$isVerified): ?>
                                <a href="admin.php?approve=<?php echo $primaryId; ?>" style="background: #2e7d32; color: white; padding: 6px 12px; border-radius: 4px; text-decoration: none; font-size: 0.85em; font-weight: bold;">Approve</a>
                            <?php else: ?>
                                <span style="color: #2e7d32; font-weight: bold; font-size: 0.9em;">✓ Active</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php 
                    endwhile; 
                else: 
                ?>
                    <tr>
                        <td colspan="5" style="padding: 40px; text-align: center; color: #777;">
                            ⚠️ Diagnostic warning: No records could be fetched or queried safely.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>