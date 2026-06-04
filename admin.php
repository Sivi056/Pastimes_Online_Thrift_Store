<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'DBConn.php';
session_start();

// Handle Approval
if (isset($_GET['verify'])) {
    $id = intval($_GET['verify']);
    mysqli_query($conn, "UPDATE user SET is_verified = 1 WHERE userId = $id");
    header("Location: admin.php");
    exit();
}

// Handle Delete
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    mysqli_query($conn, "DELETE FROM user WHERE userId = $id");
    header("Location: admin.php");
    exit();
}

// Simple query to pull everything without complex ordering rules
$users = mysqli_query($conn, "SELECT * FROM user");
?>
<!DOCTYPE html>
<html>

<head>
    <title>Diagnostic Admin Panel</title>
</head>

<body style="font-family: sans-serif; padding: 20px;">

    <h2>Pastimes Diagnostic Dashboard</h2>

    <?php if (!$users): ?>
    <p style="color:red;">SQL Error: <?php echo mysqli_error($conn); ?></p>
    <?php else: ?>
    <table border="1" cellpadding="10" cellspacing="0" style="width:100%; border-collapse: collapse;">
        <tr style="background: #eee;">
            <th>ID</th>
            <th>Username</th>
            <th>Email</th>
            <th>Role</th>
            <th>Actions</th>
        </tr>
        <?php while($row = mysqli_fetch_assoc($users)): ?>
        <tr>
            <td><?php echo $row['userId']; ?></td>
            <td><?php echo isset($row['username']) ? $row['username'] : 'Key username missing!'; ?></td>
            <td><?php echo isset($row['userEmail']) ? $row['userEmail'] : 'Key userEmail missing!'; ?></td>
            <td><?php echo $row['role']; ?></td>
            <td>
                <a href="admin.php?verify=<?php echo $row['userId']; ?>">Approve</a> |
                <a href="admin.php?delete=<?php echo $row['userId']; ?>" style="color:red;">Delete</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
    <?php endif; ?>

    <p><a href="index.php">Back Home</a></p>
</body>

</html>