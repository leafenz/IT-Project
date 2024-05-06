<?php
include "db_connection.php";

// Check if the user is logged in
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php"); // Redirect to the login page if not logged in
    exit();
}

$username = $_SESSION['username'];
$userID = "SELECT userID.* FROM users
        WHERE username = $username";

// Query to select all books associated with the user from the readBooks table
$username = $_SESSION['username'];
$sql = "SELECT books.* FROM books 
        INNER JOIN readBooks ON books.bookID = readBooks.bookID 
        WHERE readBooks.userID = $userID";

$result = mysqli_query($conn, $sql);

// Check if any books were found
if (mysqli_num_rows($result) > 0) {
    // Start HTML output
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>User Profile</title>
    </head>
    <body>
        <h1>User Profile</h1>
        <h2>Books Read:</h2>
        <ul>
            <?php
            // Output each book as a list item
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<li>{$row['title']} by {$row['author']}</li>";
            }
            ?>
        </ul>
        <a href="logout.php">Logout</a> <!-- Add a logout link -->
    </body>
    </html>
    <?php
} else {
    // If no books found, display a message
    echo "<p>No books found for this user.</p>";
}

// Close the database connection
mysqli_close($conn);
?>
