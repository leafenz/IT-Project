<?php
include "db_connection.php";

if(isset($_POST['submit'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $passwordSHA = hash('sha256', $password);

    $stmt = $conn->prepare("SELECT * FROM users WHERE username=? AND passwordSHA=?");
    $stmt->bind_param("ss", $username, $passwordSHA);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows === 1) {
        session_start();
        $_SESSION['username'] = $username;
        header("Location: index.php");
        exit;
    } else {
        echo "Incorrect username or password!";
    }

    $stmt->close();
} else {
    header("Location: login.php");
    exit;
}
?>