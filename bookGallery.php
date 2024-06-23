<?php
include 'db_connection.php';
include 'header.php';

$conn = mysqli_connect($servername, $username, $password, $database);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$conditions = array();

if (isset($_GET['genre']) && !empty($_GET['genre'])) {
    $genre = mysqli_real_escape_string($conn, $_GET['genre']);
    $conditions[] = "genre='$genre'";
}

if (isset($_GET['search']) && !empty($_GET['search'])) {
    $searchTerm = mysqli_real_escape_string($conn, $_GET['search']);
    $searchCondition = "(title LIKE '%$searchTerm%' OR author LIKE '%$searchTerm%' OR genre LIKE '%$searchTerm%')";
    $conditions[] = $searchCondition;
}
$whereClause = '';
if (!empty($conditions)) {
    $whereClause = 'WHERE ' . implode(' AND ', $conditions);
}

$query = "
    SELECT books.*, AVG(reviews.stars) AS averageRating
    FROM books
    LEFT JOIN reviews ON books.bookID = reviews.bookID
    $whereClause
    GROUP BY books.bookID
";

$result = mysqli_query($conn, $query);
?>

<body class="book-library-body">
    <h1 class="book-library-header">Book Library</h1>

    <form method="get" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" class="book-library-form">
        <label for="genre">Genre:</label>
        <select name="genre" id="genre">
            <option value="">All</option>
            <?php
            $genreQuery = "SELECT DISTINCT genre FROM books";
            $genreResult = mysqli_query($conn, $genreQuery);
            while ($row = mysqli_fetch_assoc($genreResult)) {
                $selected = (isset($_GET['genre']) && $_GET['genre'] == $row['genre']) ? 'selected' : '';
                echo "<option value='" . $row['genre'] . "' $selected>" . $row['genre'] . "</option>";
            }
            ?>
        </select>
        <button type="submit">Filter</button>
    </form>

    <form method="get" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" class="book-library-form">
        <label for="search">Find:</label>
        <input type="text" name="search" id="search" value="<?php echo isset($_GET['search']) ? $_GET['search'] : ''; ?>">
        <button type="submit">Search</button>
    </form>

    <?php if (mysqli_num_rows($result) > 0): ?>
        <h2 class="book-library-subheader">All Books</h2>
        <table class="book-library-table">
            <tr>
                <th>Title</th>
                <th>Author</th>
                <th>Genre</th>
                <th>Publish Year</th>
                <th>Language</th>
                <th>Synopsis</th>
                <th>Cover</th>
                <th>Rating</th>
                <th>Action</th>
            </tr>
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?php echo $row['title']; ?></td>
                    <td><?php echo $row['author']; ?></td>
                    <td><?php echo $row['genre']; ?></td>
                    <td><?php echo $row['publishYear']; ?></td>
                    <td><?php echo $row['language']; ?></td>
                    <td><?php echo $row['synopsis']; ?></td>
                    <td><img src="pictures/<?php echo $row['cover']; ?>" alt="Cover"></td>
                    <td><?php echo round($row['averageRating'], 2); ?> ★</td>
                    <td>
                        <?php
                        if (isset($_SESSION['username'])) {
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
                        
                                $bookID = $row['bookID'];
                        
                                $checkBookToReadQuery = "SELECT * FROM bookstoread WHERE userID='$userID' AND bookID='$bookID'";
                                $checkReadBooksQuery = "SELECT * FROM readbooks WHERE userID='$userID' AND bookID='$bookID'";
                        
                                $bookToReadResult = mysqli_query($conn, $checkBookToReadQuery);
                                $readBooksResult = mysqli_query($conn, $checkReadBooksQuery);
                        
                                if (mysqli_num_rows($readBooksResult) > 0) {
                                    echo "Completed";
                                } elseif (mysqli_num_rows($bookToReadResult) > 0) {
                                    ?>
                                    <form action="moveToFinished.php" method="post">
                                        <input type="hidden" name="book_id" value="<?php echo $row['bookID']; ?>">
                                        <button type="submit" class="book-library-button">Finished!</button>
                                    </form>
                                    <?php
                                } else {
                                    ?>
                                    <form action="addToRead_action.php" method="post">
                                        <input type="hidden" name="book_id" value="<?php echo $row['bookID']; ?>">
                                        <button type="submit" class="book-library-button">Add to "Books to Read"</button>
                                    </form>
                                    <?php
                                }
                            }
                        }
                        ?>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>
    <?php else: ?>
        <p class="book-library-subheader">No books available</p>
    <?php endif; ?>
</body>
</html>

<?php
include "footer.php";
mysqli_close($conn);
?>
