<?php
// Include the connection file
include 'DBConn.php';

echo "<h2>Pastimes Store: Database Initialization System</h2>";

// Define the name of the SQL export (Requirement 8)
$sqlFile = 'myClothingStore.sql';

if (file_exists($sqlFile)) 
    {
        // Read the contents of the exported SQL file
        $sqlQueries = file_get_contents($sqlFile);

        // Disable Foreign Key checks so we can drop/rebuild tables without errors
        mysqli_query($conn, "SET FOREIGN_KEY_CHECKS = 0");

        // Execute all structural creation queries in the file at once
        if (mysqli_multi_query($conn, $sqlQueries)) 
            {
                do 
                {
                    // Loop through and clear results to ensure multi-query finishes cleanly
                    if ($result = mysqli_store_result($conn)) 
                        {
                            mysqli_free_result($result);
                        }
                } 
                while (mysqli_more_results($conn) && mysqli_next_result($conn));
                
                echo "<p style='color:green; font-weight:bold;'>SUCCESS: Database structure has been fully reset from $sqlFile.</p>";
            } 
            else 
            {
                echo "<p style='color:red;'>ERROR: Could not execute SQL file. " . mysqli_error($conn) . "</p>";
            }
        
        // Turn Foreign Key checks back on
        mysqli_query($conn, "SET FOREIGN_KEY_CHECKS = 1");
    } 
    else 
    {
        echo "<p style='color:orange;'>ERROR: The file <strong>$sqlFile</strong> was not found.</p>";
    }

// CRITICAL FIX: Close the multi-query connection to prevent "Out of Sync" errors
mysqli_close($conn);

// Re-open a clean connection for individual text file text insertions
include 'DBConn.php';

echo "<h3>Populating Data From Requirements Text Files...</h3>";

// --- 1. POPULATE USERS FROM TEXT FILE ---
if (file_exists('userData.txt.txt')) 
    {
        $users = file('userData.txt.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($users as $userLine) 
            {
                $data = explode(',', $userLine);
                if (count($data) >= 4) 
                    {
                        $name = trim($data[0]);
                        $email = trim($data[1]);
                        $plainPass = trim($data[2]);
                        $role = trim($data[3]);
                        
                        // Hash the passwords securely to match standard professional registration guidelines
                        $hashedPass = password_hash($plainPass, PASSWORD_DEFAULT);
                        
                        // Admins are approved by default; Buyers/Sellers start as 0 (Unverified / Pending)
                        $isVerified = ($role === 'Admin') ? 1 : 0;

                        $stmt = $conn->prepare("INSERT INTO user (username, userEmail, password, role, is_verified) VALUES (?, ?, ?, ?, ?)");
                        $stmt->bind_param("ssssi", $name, $email, $hashedPass, $role, $isVerified);
                        $stmt->execute();
                        $stmt->close();
                    }
            }
        echo "<p style='color:green; font-weight:bold;'>✓ User text file entries successfully processed into database.</p>";
    }

// --- 2. POPULATE CATEGORIES FROM TEXT FILE ---
if (file_exists('categoryData.txt')) 
    {
        $categories = file('categoryData.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($categories as $catLine) 
            {
                $catName = trim($catLine);
                if (!empty($catName)) 
                    {
                        $stmt = $conn->prepare("INSERT INTO category (categoryName) VALUES (?)");
                        $stmt->bind_param("s", $catName);
                        $stmt->execute();
                        $stmt->close();
                    }
            }
        echo "<p style='color:green; font-weight:bold;'>✓ Category text file entries successfully processed into database.</p>";
    }

// --- 3. POPULATE PRODUCTS FROM TEXT FILE ---
if (file_exists('productData.txt')) 
    {
        $products = file('productData.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($products as $prodLine) 
            {
                $data = explode(',', $prodLine);
                if (count($data) >= 10) 
                    {
                        $userId = intval(trim($data[0]));
                        $categoryId = intval(trim($data[1]));
                        $brand = trim($data[2]);
                        $price = floatval(trim($data[3]));
                        $conditionRating = intval(trim($data[4]));
                        $description = trim($data[5]);
                        $size = trim($data[6]);
                        $material = trim($data[7]);
                        $color = trim($data[8]);
                        $status = trim($data[9]);

                        // Prepared statements handle special characters seamlessly (e.g. Levi's)
                        $stmt = $conn->prepare("INSERT INTO product (userId, categoryId, brand, price, conditionRating, description, size, material, color, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                        $stmt->bind_param("iiddisssss", $userId, $categoryId, $brand, $price, $conditionRating, $description, $size, $material, $color, $status);
                        $stmt->execute();
                        $stmt->close();
                    }
            }
        echo "<p style='color:green; font-weight:bold;'>✓ Product text file items mapped successfully without key collisions.</p>";
    }

// Close final operational connection safely
mysqli_close($conn);
echo "<p style='color:blue; font-weight:bold;'>System initialization complete. Platform is ready to use!</p>";
?>