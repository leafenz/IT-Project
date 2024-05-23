<?php
include 'db_connection.php';
include 'header.php';

if (!isset($_SESSION['username'])) {
    // If the user is not logged in, redirect to the login page
    header("Location: login.php");
    exit();
}

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

$query = "SELECT * FROM books $whereClause";

$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Library</title>
</head>
<body>
    <h1>Book Library</h1>

    <form method="get" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
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

    <form method="get" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <label for="search">Find:</label>
        <input type="text" name="search" id="search" value="<?php echo isset($_GET['search']) ? $_GET['search'] : ''; ?>">
        <button type="submit">Search</button>
    </form>

    <?php if (mysqli_num_rows($result) > 0): ?>
        <h2>All Books</h2>
        <table>
            <tr>
                <th>Title</th>
                <th>Author</th>
                <th>Genre</th>
                <th>Publish Year</th>
                <th>Language</th>
                <th>Synopsis</th>
                <th>Cover</th>
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
                    <td><img src="/pictures/<?php echo $row['cover']; ?>" alt="Cover" width="100"></td>
                    <td>
                        <?php if (isset($_SESSION['username'])): ?>
                            <form action="addToRead.php" method="post">
                                <input type="hidden" name="book_id" value="<?php echo $row['bookID']; ?>">
                                <button type="submit">Add to "Books to Read"</button>
                            </form>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>
    <?php else: ?>
        <p>No books available</p>
    <?php endif; ?>

</body>
</html>

<?php
mysqli_close($conn);
?>
