<?php
// Include your connection file
include 'DBConn.php';

echo "<h2>Pastimes Store: Database Initialization System</h2>";

//Define the name of your SQL export from Requirement 8
$sqlFile = 'myClothingStore.sql';

if (file_exists($sqlFile)) 
    {
        // Read the contents of your exported SQL file
        $sqlQueries = file_get_contents($sqlFile);

        //Disable Foreign Key checks so we can drop/rebuild tables without errors
        mysqli_query($conn, "SET FOREIGN_KEY_CHECKS = 0");

        //Execute all queries in the file at once
        // mysqli_multi_query is used because .sql files contain multiple statements
        if (mysqli_multi_query($conn, $sqlQueries)) 
            {
                do 
                {
                    // We have to loop through the results to ensure everything executes
                    if ($result = mysqli_store_result($conn)) 
                        {
                            mysqli_free_result($result);
                        }
                } 
                while (mysqli_more_results($conn) && mysqli_next_result($conn));
                echo "<p style='color:green; font-weight:bold;'>SUCCESS: Database structure and data have been fully restored from $sqlFile.</p>";
            } 
            else 
            {
                echo "<p style='color:red;'>ERROR: Could not execute SQL file. " . mysqli_error($conn) . "</p>";
            }
        //Turn Foreign Key checks back on
        mysqli_query($conn, "SET FOREIGN_KEY_CHECKS = 1");
    } 
    else 
    {
        echo "<p style='color:orange;'>ERROR: The file <strong>$sqlFile</strong> was not found. Make sure you exported it from phpMyAdmin first!</p>";
    }

mysqli_close($conn);
?>