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
    $userID = $userData['userID']; // Get the userID
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
        <a href="logout.php">Logout</a>

        <div class="container">
            <div class="books-list">
                <h2>Books to Read</h2>
                <a href="bookGallery.php" class="add-book-button">Add New Book</a>
                <ul>
                <?php
                // Query to get books to read for the user
                $sqlBooksToRead = "SELECT b.title, b.cover, b.author FROM bookstoread br
                                   JOIN books b ON br.bookID = b.bookID
                                   WHERE br.userID = ?";
                
                // Prepare the SQL statement
                $stmtBooksToRead = mysqli_prepare($conn, $sqlBooksToRead);

                // Bind parameters
                mysqli_stmt_bind_param($stmtBooksToRead, "i", $userID);

                // Execute the statement
                mysqli_stmt_execute($stmtBooksToRead);

                // Get the result
                $resultBooksToRead = mysqli_stmt_get_result($stmtBooksToRead);

                // Check if there are books to read
                if (mysqli_num_rows($resultBooksToRead) > 0) {
                    // Fetch and display each book
                    while ($book = mysqli_fetch_assoc($resultBooksToRead)) {
                        ?>
                        <li>
                            <img src="<?php echo $row['cover']; ?>" alt="Cover" width="100">
                            <strong><?php echo $book['title']; ?></strong> by <?php echo $book['author']; ?>
                        </li>
                        <?php
                    }
                } else {
                    echo "<li>No books to read found.</li>";
                }
                ?>
                </ul>
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
