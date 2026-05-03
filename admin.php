<?php
include 'DBConn.php';
session_start();

if (isset($_GET['verify'])) {
    $id = intval($_GET['verify']);
    mysqli_query($conn, "UPDATE user SET isVerified = 1 WHERE userId = $id");
    header("Location: admin.php"); exit();
}

if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    mysqli_query($conn, "DELETE FROM user WHERE userId = $id");
    header("Location: admin.php"); exit();
}

$users = mysqli_query($conn, "SELECT * FROM user");
?>
<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div class="container" style="max-width:900px">
        <h1>Admin Panel</h1>
        <table>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
            <?php while($row = mysqli_fetch_assoc($users)): ?>
            <tr>
                <td><?php echo $row['userId']; ?></td>
                <td><?php echo $row['userName']; ?></td>
                <td><?php echo $row['userEmail']; ?></td>
                <td><?php echo $row['isVerified'] ? "Verified" : "Pending"; ?></td>
                <td>
                    <?php if(!$row['isVerified']): ?>
                    <a href="admin.php?verify=<?php echo $row['userId']; ?>">Verify</a> |
                    <?php endif; ?>
                    <a href="admin.php?delete=<?php echo $row['userId']; ?>" style="color:red">Delete</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </table>
        <a href="index.php" class="btn-gold">Back Home</a>
    </div>
</body>

</html>