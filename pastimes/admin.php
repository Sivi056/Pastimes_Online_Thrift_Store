<?php
include 'DBConn.php';
session_start();

// Verify User
if (isset($_GET['verify'])) {
    $id = intval($_GET['verify']);
    // Note: gotta make sure this column name matches the DB (isVerified) else it gets upset
    mysqli_query($conn, "UPDATE user SET isVerified = 1 WHERE userId = $id");
    // Redirect back to admin page after verification
    header("Location: admin.php");
    exit();
}

// Delete User
if (isset($_GET['delete'])) {
    // check we have something to delete at all
    $id = intval($_GET['delete']);
    mysqli_query($conn, "DELETE FROM user WHERE userId = $id");
    // Redirect back to admin page after deletion
    header("Location: admin.php");
    exit();
}

// Update User (Requirement 4)
if (isset($_POST['update_user'])) {
    $id = $_POST['userId'];
    $newName = $_POST['userName'];
    $newEmail = $_POST['userEmail'];
    // Update the user's name and email in the database
    mysqli_query($conn, "UPDATE user SET userName = '$newName', userEmail = '$newEmail' WHERE userId = $id");
    // Redirect back to admin page after update
    header("Location: admin.php");
    exit();
}

// Fetch all customers (excluding any admins if they were in this table)
$users = mysqli_query($conn, "SELECT * FROM user WHERE role != 'Admin'");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style.css">
    <title>Pastimes | Admin Control</title>
    <style>
    /* Ensure the table is responsive and looks good on all screen sizes */
    table {
        width: 100%;
        border-collapse: collapse;
        table-layout: fixed;
        /* Forces columns to respect widths */
    }

    th,
    td {
        padding: 10px;
        border: 1px solid #ddd;
        word-wrap: break-word;
    }

    /* Make the inputs fit inside the cells properly */
    td input {
        width: 95% !important;
        padding: 5px !important;
        margin: 0 !important;
        box-sizing: border-box;
    }

    .status-pending {
        color: orange;
        font-weight: bold;
    }

    .status-verified {
        color: green;
        font-weight: bold;
    }
    </style>
</head>

<body>

    <div class="container" style="max-width: 1000px;">
        <h1>Admin Dashboard</h1>
        <p>Manage Customer Registrations & Verification</p>
        <hr>

        <table>
            <thead>
                <tr style="background: #006400; color: #D4AF37;">
                    <!-- custom widths bc it doesnt do it nicely automatically, not great for standards but it works -->
                    <th style="width: 10%;">ID</th>
                    <th style="width: 25%;">Name</th>
                    <th style="width: 30%;">Email</th>
                    <th style="width: 15%;">Status</th>
                    <th style="width: 20%;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <!-- Loop through users and display them in the table -->
                <?php while($row = mysqli_fetch_assoc($users)): ?>
                <tr style="text-align: center;">
                    <td><?php echo $row['userId']; ?></td>

                    <form method="POST" action="admin.php">
                        <input type="hidden" name="userId" value="<?php echo $row['userId']; ?>">

                        <td>
                            <input type="text" name="userName"
                                value="<?php echo htmlspecialchars($row['userName']); ?>">
                        </td>

                        <td>
                            <input type="email" name="userEmail"
                                value="<?php echo htmlspecialchars($row['userEmail']); ?>">
                        </td>

                        <td>
                            <?php 
                            // Safety check for the array key to prevent PHP warnings
                            if(isset($row['isVerified']) && $row['isVerified'] == 1): ?>
                            <span class="status-verified">Verified</span>
                            <?php else: ?>
                            <span class="status-pending">Pending</span>
                            <?php endif; ?>
                        </td>

                        <td>
                            <button type="submit" name="update_user"
                                style="background:none; border:none; color:blue; cursor:pointer; text-decoration:underline; font-size:14px;">Update</button>
                            <br>
                            <!-- Only show the "Verify" option if the user is not already verified -->
                            <?php if(isset($row['isVerified']) && !$row['isVerified']): ?>
                            <a href="admin.php?verify=<?php echo $row['userId']; ?>"
                                style="color: green; font-weight: bold; font-size:14px;">Verify</a> |
                            <?php endif; ?>

                            <a href="admin.php?delete=<?php echo $row['userId']; ?>" style="color:red; font-size:14px;"
                                onclick="return confirm('Are you sure you want to delete this user?')">Delete</a>
                        </td>
                    </form>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <br>
        <a href="login.php" class="btn-gold">Back to Login</a>
    </div>

</body>

</html>