<!-- watched during development:
 https://youtube.com/playlist?list=PLm8sgxwSZofc_jFRsbTHPAW0Kp52KgAAm&si=yJE-go8ZPSrvP-pI
 https://youtube.com/playlist?list=PLOR5hj0X3WPdOWwU7eCCfFcgIkS1WrDYl&si=_uDfh-nC1HIBcn4u
 https://youtube.com/playlist?list=PL5kIDoSdjG7PY_kPyULbbLk4mpvStqdPR&si=Vxt44Xpmhx7jnkji
 -->

<?php
include 'DBConn.php';
// https://www.w3schools.com/php/php_sessions.asp
session_start();

//https://www.w3schools.com/php/func_var_isset.asp
// Verify User
if (isset($_GET['verify'])) 
    {
        $id = intval($_GET['verify']);
        // Note: gotta make sure this column name matches the DB (isVerified) else it gets upset
        mysqli_query($conn, "UPDATE user SET isVerified = 1 WHERE userId = $id");
        // Redirect back to admin page after verification
        header("Location: admin.php"); 
        exit();
    }

// used https://www.w3schools.com/php/php_mysql_delete.asp for help
// Delete User
if (isset($_GET['delete'])) 
    {
        // check we have something to delete at all
        $id = intval($_GET['delete']);
        mysqli_query($conn, "DELETE FROM user WHERE userId = $id");
        // Redirect back to admin page after deletion
        header("Location: admin.php"); 
        exit();
    }

//update user code removed

// Fetch all customers (excluding any admins if they were in this table)
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
                <!-- Loop through users and display them in the table -->
                 <!-- https://www.w3schools.com/php/func_mysqli_fetch_assoc.asp -->
                <?php while($row = mysqli_fetch_assoc($users)): ?>
            <tr>
                <td><?php echo $row['userId']; ?></td>
                <td><?php echo $row['userName']; ?></td>
                <td><?php echo $row['userEmail']; ?></td>
                <!-- Safety check for the array key to prevent PHP warnings -->
                <td><?php echo $row['isVerified'] ? "Verified" : "Pending"; ?></td>
                <td>
                    <!-- Only show the "Verify" option if the user is not already verified -->
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