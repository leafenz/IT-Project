<?php
include 'db_connection.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_SESSION['username'];
                        $sql = "SELECT userID FROM users WHERE username = ?";
                    
                        if ($stmt = mysqli_prepare($conn, $sql)) {
                            // Bind the username parameter to the statement
                            mysqli_stmt_bind_param($stmt, "s", $username);
                    
                            // Execute the statement
                            mysqli_stmt_execute($stmt);
                    
                            // Bind the result variable
                            mysqli_stmt_bind_result($stmt, $userID);
                    
                            // Fetch the userID
                            mysqli_stmt_fetch($stmt);
                    
                            // Close the statement
                            mysqli_stmt_close($stmt);
                        }
                    
    $bookID = $_POST['book_id'];

    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Start a transaction
    mysqli_begin_transaction($conn);

    try {
        // Remove from bookstoread
        $deleteQuery = "DELETE FROM bookstoread WHERE userID='$userID' AND bookID='$bookID'";
        mysqli_query($conn, $deleteQuery);

        // Add to readbooks
        $insertQuery = "INSERT INTO readbooks (userID, bookID) VALUES ('$userID', '$bookID')";
        mysqli_query($conn, $insertQuery);

        // Commit transaction
        mysqli_commit($conn);

        header("Location: bookGallery.php");
    } catch (Exception $e) {
        // Rollback transaction
        mysqli_rollback($conn);
        echo "Error: " . $e->getMessage();
    }

    mysqli_close($conn);
}
?>
