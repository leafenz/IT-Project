<?php
include 'db_connection.php';

session_start();
if (!isset($_SESSION['username'])) {
    // If the user is not logged in, redirect to the login page
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $conn = mysqli_connect($servername, $username, $password, $database);

    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    $username = $_SESSION['username'];
    $sql = "SELECT userID FROM users WHERE username = ?";

if ($stmt = mysqli_prepare($conn, $sql)) {
    // Bind the username parameter to the statement
    mysqli_stmt_bind_param($stmt, "s", $username);

    // Execute the statement
    mysqli_stmt_execute($stmt);

    // Bind the result variable
    mysqli_stmt_bind_result($stmt, $userID);

    $bookID = mysqli_real_escape_string($conn, $_POST['book_id']);

    // Check if the book is already in the user's list
    $checkQuery = "SELECT * FROM bookstoread WHERE userID = ? AND bookID = ?";
    $stmt = mysqli_prepare($conn, $checkQuery);
    mysqli_stmt_bind_param($stmt, 'ii', $userID, $bookID);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);

    if (mysqli_stmt_num_rows($stmt) == 0) {
        // Insert the book into the bookstoread table
        $insertQuery = "INSERT INTO bookstoread (userID, bookID) VALUES (?, ?)";
        $insertStmt = mysqli_prepare($conn, $insertQuery);
        mysqli_stmt_bind_param($insertStmt, 'ii', $userID, $bookID);

        if (mysqli_stmt_execute($insertStmt)) {
            echo "Book successfully added to your 'Books to Read' list.";
        } else {
            echo "Error: " . mysqli_stmt_error($insertStmt);
        }
    } else {
        echo "This book is already in your 'Books to Read' list.";
    }

    mysqli_stmt_close($stmt);
    mysqli_stmt_close($insertStmt);
    mysqli_close($conn);
} else {
    echo "Invalid request.";
}}