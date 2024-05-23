<?php
include "db_connection.php";

session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php"); // Redirect to the login page if not logged in
    exit();
}

$username = $_SESSION['username'];

// Query to select all user information based on username
$sql = "SELECT * FROM users WHERE username = ?";

// Prepare the SQL statement
$stmt = mysqli_prepare($conn, $sql);

// Bind parameters
mysqli_stmt_bind_param($stmt, "s", $username);

// Execute the statement
mysqli_stmt_execute($stmt);

// Get the result
$result = mysqli_stmt_get_result($stmt);

// Check if user exists
if (mysqli_num_rows($result) > 0) {
    // Fetch user data
    $userData = mysqli_fetch_assoc($result);
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
        <h2>User Information:</h2>
        <ul>
            <li>Username: <?php echo $userData['username']; ?></li>
            <li>Email: <?php echo $userData['email']; ?></li>
            <li>First Name: <?php echo $userData['firstname']; ?></li>
            <li>Last Name: <?php echo $userData['lastname']; ?></li>
            <li>Birthdate: <?php echo $userData['birthdate']; ?></li>
            <li>Sex: <?php echo $userData['sex']; ?></li>
        </ul>
        <a href="logout.php">Logout</a> <!-- Add a logout link -->

        <div class="container">
            <div class="books-list">
                <h2>Books to Read</h2>
                <!-- List of books to read goes here -->
                <a href="bookGallery.php" class="add-book-button">Add New Book</a>
            </div>

            <div class="books-list">
                <h2>Read Books</h2>
                <!-- List of read books goes here -->
            </div>
        </div>
    </body>
    </html>
    <?php
} else {
    // If no user found, display a message
    echo "<p>User not found.</p>";
}

// Close the database connection
mysqli_close($conn);
?>
