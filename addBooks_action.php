<?php
include "db_connection.php";

if(isset($_POST['submit'])) {
    $title = $_POST['title'];
    $author = $_POST['author'];
    $genre = $_POST['genre'];
    $publishYear = $_POST['publishYear'];
    $language = $_POST['language'];
    $cover = $_POST['cover'];
    $synopsis = $_POST['synopsis'];

    // Prepared statement gegen SQL-Injections
    $stmt = $conn->prepare("INSERT INTO books (title, author, genre, publishYear, language, cover, synopsis) 
                            VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssss", $title, $author, $genre, $publishYear, $language, $cover, $synopsis);

    if ($stmt->execute()) {
        $stmt->close();
        header("Location: index.php");
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
} else {
    header("Location: addBooks.php");
    exit;
}
?>
