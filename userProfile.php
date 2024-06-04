<?php
include "db_connection.php";
include "header.php";

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
    
    // Handle the "finished!" button click
    if (isset($_POST['finished'])) {
        $bookID = $_POST['bookID'];

        // Remove the book from bookstoread
        $deleteSql = "DELETE FROM bookstoread WHERE userID = ? AND bookID = ?";
        $deleteStmt = mysqli_prepare($conn, $deleteSql);
        mysqli_stmt_bind_param($deleteStmt, "ii", $userID, $bookID);
        mysqli_stmt_execute($deleteStmt);

        // Add the book to readbooks
        $insertSql = "INSERT INTO readbooks (userID, bookID) VALUES (?, ?)";
        $insertStmt = mysqli_prepare($conn, $insertSql);
        mysqli_stmt_bind_param($insertStmt, "ii", $userID, $bookID);
        mysqli_stmt_execute($insertStmt);

        // Redirect to refresh the page
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    }

    // Handle the review submission
    if (isset($_POST['submit_review'])) {
        $bookID = $_POST['bookID'];
        $stars = $_POST['stars'];
        $comment = $_POST['comment'];

        // Insert the review into the reviews table
        $reviewSql = "INSERT INTO reviews (userID, bookID, stars, comment) VALUES (?, ?, ?, ?)";
        $reviewStmt = mysqli_prepare($conn, $reviewSql);
        mysqli_stmt_bind_param($reviewStmt, "iiis", $userID, $bookID, $stars, $comment);
        mysqli_stmt_execute($reviewStmt);

        // Redirect to refresh the page
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    }
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>User Profile</title>
        <style>
            .review-form {
                display: none;
                margin-top: 10px;
            }
        </style>
    </head>
    <body>
        <h1>User Profile</h1>
        <h2>User Information:</h2>
        <ul>
            <li>Username: <?php echo htmlspecialchars($userData['username']); ?></li>
            <li>Email: <?php echo htmlspecialchars($userData['email']); ?></li>
            <li>First Name: <?php echo htmlspecialchars($userData['firstname']); ?></li>
            <li>Last Name: <?php echo htmlspecialchars($userData['lastname']); ?></li>
            <li>Birthdate: <?php echo htmlspecialchars($userData['birthdate']); ?></li>
            <li>Sex: <?php echo htmlspecialchars($userData['sex']); ?></li>
        </ul>
        <a href="logout.php">Logout</a>

        <div class="container">
            <div class="books-list">
                <h2>Books to Read</h2>
                <a href="bookGallery.php" class="add-book-button">Add New Book</a>
                <ul>
                <?php
                // Query to get books to read for the user
                $sqlBooksToRead = "SELECT b.bookID, b.title, b.cover, b.author FROM bookstoread br
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
                            <img src="pictures/<?php echo htmlspecialchars($book['cover']); ?>" alt="Cover" width="100">
                            <strong><?php echo htmlspecialchars($book['title']); ?></strong> by <?php echo htmlspecialchars($book['author']); ?>
                            <form method="POST" style="display:inline;">
                                <input type="hidden" name="bookID" value="<?php echo htmlspecialchars($book['bookID']); ?>">
                                <button type="submit" name="finished">Finished!</button>
                            </form>
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
    <ul>
    <?php
    // Query to get read books for the user
    $sqlReadBooks = "SELECT b.bookID, b.title, b.cover, b.author FROM readbooks rb
                     JOIN books b ON rb.bookID = b.bookID
                     WHERE rb.userID =?";
    
    // Prepare the SQL statement
    $stmtReadBooks = mysqli_prepare($conn, $sqlReadBooks);

    // Bind parameters
    mysqli_stmt_bind_param($stmtReadBooks, "i", $userID);

    // Execute the statement
    mysqli_stmt_execute($stmtReadBooks);

    // Get the result
    $resultReadBooks = mysqli_stmt_get_result($stmtReadBooks);

    // Check if there are read books
    if (mysqli_num_rows($resultReadBooks) > 0) {
        // Fetch and display each book
        while ($book = mysqli_fetch_assoc($resultReadBooks)) {
            // Query to check if the user has already reviewed the book
            $sqlCheckReview = "SELECT stars FROM reviews WHERE bookID =? AND userID =?";
            $stmtCheckReview = mysqli_prepare($conn, $sqlCheckReview);
            mysqli_stmt_bind_param($stmtCheckReview, "ii", $book['bookID'], $userID);
            mysqli_stmt_execute($stmtCheckReview);
            $resultCheckReview = mysqli_stmt_get_result($stmtCheckReview);
            $hasReviewed = mysqli_num_rows($resultCheckReview) > 0;

            if ($hasReviewed) {
                $review = mysqli_fetch_assoc($resultCheckReview);
                $stars = $review['stars'];
               ?>
                <li>
                    <img src="pictures/<?php echo htmlspecialchars($book['cover']);?>" alt="Cover" width="100">
                    <strong><?php echo htmlspecialchars($book['title']);?></strong> by <?php echo htmlspecialchars($book['author']);?>
                    <p style="color:forestgreen">Reviewed: <?php echo $stars;?> stars </p>
                </li>
                <?php
            } else {
               ?>
                <li>
                    <img src="pictures/<?php echo htmlspecialchars($book['cover']);?>" alt="Cover" width="100">
                    <strong><?php echo htmlspecialchars($book['title']);?></strong> by <?php echo htmlspecialchars($book['author']);?>
                    <button onclick="toggleReviewForm(<?php echo htmlspecialchars($book['bookID']);?>)">Give Review</button>
                    <form class="review-form" id="review-form-<?php echo htmlspecialchars($book['bookID']);?>" method="POST">
                        <input type="hidden" name="bookID" value="<?php echo htmlspecialchars($book['bookID']);?>">
                        <label for="stars">Stars:</label>
                        <select name="stars" required>
                            <option value="">Select</option>
                            <?php for ($i = 1; $i <= 10; $i++) {?>
                                <option value="<?php echo $i;?>"><?php echo $i;?></option>
                            <?php }?>
                        </select>
                        <label for="comment">Comment:</label>
                        <textarea name="comment" required></textarea>
                        <button type="submit" name="submit_review">Submit Review</button>
                    </form>
                </li>
                <?php
            }
        }
    } else {
        echo "<li>No read books found.</li>";
    }
}
   ?>
    </ul>
</div>

<script>
    function toggleReviewForm(bookID) {
        var form = document.getElementById('review-form-' + bookID);
        if (form.style.display === 'none' || form.style.display === '') {
            form.style.display = 'block';
        } else {
            form.style.display = 'none';
        }
    }
</script>
