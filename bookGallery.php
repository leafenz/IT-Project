<?php
include 'db_connection.php';

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
            <option value="">Alle</option>
            <?php
            $genreQuery = "SELECT DISTINCT genre FROM books";
            $genreResult = mysqli_query($conn, $genreQuery);
            while ($row = mysqli_fetch_assoc($genreResult)) {
                $selected = ($_GET['genre'] == $row['genre']) ? 'selected' : '';
                echo "<option value='" . $row['genre'] . "' $selected>" . $row['genre'] . "</option>";
            }
            ?>
        </select>
        <button type="submit">Filtern</button>
    </form>

    <form method="get" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <label for="search">Suche:</label>
        <input type="text" name="search" id="search" value="<?php echo isset($_GET['search']) ? $_GET['search'] : ''; ?>">
        <button type="submit">Suchen</button>
    </form>

    <?php if (mysqli_num_rows($result) > 0): ?>
        <h2>Alle Bücher</h2>
        <table>
            <tr>
                <th>Title</th>
                <th>Author</th>
                <th>Genre</th>
                <th>Publish Year</th>
                <th>Language</th>
                <th>Synopsis</th>
                <th>Cover</th>
            </tr>
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?php echo $row['title']; ?></td>
                    <td><?php echo $row['author']; ?></td>
                    <td><?php echo $row['genre']; ?></td>
                    <td><?php echo $row['publishYear']; ?></td>
                    <td><?php echo $row['language']; ?></td>
                    <td><?php echo $row['synopsis']; ?></td>
                    <td><img src="<?php echo "pictures/" . $row['cover']; ?>" alt="Cover" width="100"></td>
                </tr>
            <?php endwhile; ?>
        </table>
    <?php else: ?>
        <p>Keine Bücher vorhanden</p>
    <?php endif; ?>

</body>
</html>

<?php
mysqli_close($conn);
?>
