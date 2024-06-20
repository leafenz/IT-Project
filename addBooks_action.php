<?php
include "db_connection.php";

if(isset($_POST['submit'])) {
    $title = $_POST['title'];
    $author = $_POST['author'];
    $genre = $_POST['genre'];
    $publishYear = $_POST['publishYear'];
    $language = $_POST['language'];
    $synopsis = $_POST['synopsis'];

    // Datei-Upload verarbeiten
    $target_dir = "pictures/";
    $target_file = $target_dir . basename($_FILES["cover"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Überprüfen, ob die Datei ein Bild ist
    $check = getimagesize($_FILES["cover"]["tmp_name"]);
    if($check !== false) {
        $uploadOk = 1;
    } else {
        echo "File is not an image.";
        $uploadOk = 0;
    }

    // Überprüfen, ob die Datei bereits existiert
    if (file_exists($target_file)) {
        echo "Sorry, file already exists.";
        $uploadOk = 0;
    }

    // Überprüfen der Dateigröße
    if ($_FILES["cover"]["size"] > 500000) {
        echo "Sorry, your file is too large.";
        $uploadOk = 0;
    }

    // Bestimmte Dateiformate zulassen
    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" 
    && $imageFileType != "gif" ) {
        $uploadOk = 0;
    }

    // Überprüfen, ob $uploadOk auf 0 gesetzt wurde
    if ($uploadOk == 0) {
        header("Location: addBooks.php");
    // Wenn alles in Ordnung ist, versuchen Sie die Datei hochzuladen
    } else {
        if (move_uploaded_file($_FILES["cover"]["tmp_name"], $target_file)) {
            $cover = basename($_FILES["cover"]["name"]);

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
            echo "Sorry, there was an error uploading your file.";
        }
    }
} else {
    header("Location: addBooks.php");
    exit;
}
?>
