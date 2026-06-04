<?php
// Include the database connection file
include 'DBConn.php';
session_start();

// --- 1. HANDLE VERIFICATION ACTION ---
if (isset($_GET['verify'])) 
    {
        $id = intval($_GET['verify']);
        // Flips the verification flag inside the user database
        mysqli_query($conn, "UPDATE user SET is_verified = 1 WHERE userId = $id");
        header("Location: admin.php"); 
        exit();
    }

// --- 2. HANDLE DELETION ACTION ---
if (isset($_GET['delete'])) 
    {
        $id = intval($_GET['delete']);
        // Removes the user profile cleanly
        mysqli_query($conn, "DELETE FROM user WHERE userId = $id");
        header("Location: admin.php"); 
        exit();
    }

// --- 3. FETCH ALL USERS FROM THE DATABASE ---
// Orders them so unverified/pending registrations bubble straight to the top of the grid view
$users = mysqli_query($conn, "SELECT * FROM user ORDER BY is_verified ASC, userId DESC");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Pastimes | Admin Oversight Panel</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body>
    <div class="container" style="max-width:950px; margin: 40px auto; padding: 20px;">
        <h1 style="border-bottom: 2px solid #D4AF37; padding-bottom: 10px; font-family: 'Helvetica Neue', sans-serif;">
            <i class="fas fa-user-shield"></i> Pastimes Admin Operations Panel
        </h1>
        <p style="color: #555; margin-bottom: 25px;">Review incoming marketplace registrations and manage active
            verification badges.</p>

        <table
            style="width:100%; border-collapse: collapse; background: white; box-shadow: 0 2px 8px rgba(0,0,0,0.06);">
            <thead>
                <tr style="background-color: #1a1a1a; color: white;">
                    <th style="padding: 12px; text-align: left;">ID</th>
                    <th style="padding: 12px; text-align: left;">Name</th>
                    <th style="padding: 12px; text-align: left;">Email Address</th>
                    <th style="padding: 12px; text-align: left;">System Role</th>
                    <th style="padding: 12px; text-align: left;">Status Label</th>
                    <th style="padding: 12px; text-align: left;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (mysqli_num_rows($users) > 0): ?>
                <?php while($row = mysqli_fetch_assoc($users)): ?>
                <tr style="border-bottom: 1px solid #eee;">
                    <td style="padding: 12px;"><?php echo $row['userId']; ?></td>

                    <td style="padding: 12px;"><strong><?php echo htmlspecialchars($row['username']); ?></strong></td>

                    <td style="padding: 12px;"><?php echo htmlspecialchars($row['userEmail']); ?></td>

                    <td style="padding: 12px;"><span
                            style="background: #eee; padding: 3px 8px; border-radius: 4px; font-size: 0.85em; font-weight: bold;"><?php echo $row['role']; ?></span>
                    </td>

                    <td style="padding: 12px;">
                        <?php if(isset($row['is_verified']) && $row['is_verified'] == 1): ?>
                        <span
                            style="color: #2e7d32; font-weight: bold; background: #e8f5e9; padding: 4px 8px; border-radius: 4px; font-size: 0.85em;"><i
                                class="fas fa-check-circle"></i> Approved Active</span>
                        <?php else: ?>
                        <span
                            style="color: #c62828; font-weight: bold; background: #ffebee; padding: 4px 8px; border-radius: 4px; font-size: 0.85em;"><i
                                class="fas fa-clock"></i> Pending Review</span>
                        <?php endif; ?>
                    </td>

                    <td style="padding: 12px;">
                        <?php if(!isset($row['is_verified']) || $row['is_verified'] == 0): ?>
                        <a href="admin.php?verify=<?php echo $row['userId']; ?>"
                            style="color: #2e7d32; font-weight: bold; text-decoration: none; margin-right: 15px; font-size: 0.9em;">
                            <i class="fas fa-user-check"></i> Approve
                        </a>
                        <?php endif; ?>
                        <a href="admin.php?delete=<?php echo $row['userId']; ?>"
                            style="color:#c62828; text-decoration: none; font-weight: bold; font-size: 0.9em;">
                            <i class="fas fa-trash-alt"></i> Delete Account
                        </a>
                    </td>
                </tr>
                <?php endwhile; ?>
                <?php else: ?>
                <tr>
                    <td colspan="6" style="text-align: center; padding: 40px; color: #777;">
                        <i class="fas fa-folder-open"
                            style="font-size: 2.5em; color: #D4AF37; margin-bottom: 10px; display: block;"></i>
                        No users registered. Run system initialization to load entries.
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <div style="margin-top: 35px;">
            <a href="index.php" class="btn-gold"
                style="text-decoration: none; display: inline-block; padding: 12px 24px; font-weight: bold; background: #1a1a1a; color: white; border-radius: 4px;">
                <i class="fas fa-arrow-left"></i> Exit Dashboard
            </a>
        </div>
    </div>
</body>

</html>