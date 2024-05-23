<?php
include "db_connection.php";

if(isset($_POST['submit'])) {
    $salutation = $_POST['salutation'];
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $email = $_POST['email'];
    $username = $_POST['username'];
    $birthday = $_POST['birthday'];
    $password = $_POST['password'];
    $repeat_password = $_POST['repeat_password'];

    if ($password !== $repeat_password) {
        echo "Passwords do not match!";
        exit;
    }

    $passwordSHA = hash('sha256', $password);

    $sex = "";
    if ($salutation === "woman") {
        $sex = "F";
    } elseif ($salutation === "men") {
        $sex = "M";
    } elseif ($salutation === "divers") {
        $sex = "D";
    }

    $stmt = $conn->prepare("INSERT INTO users (username, firstname, lastname, passwordSHA, birthdate, sex, email) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssss", $username, $fname, $lname, $passwordSHA, $birthday, $sex, $email);

    if ($stmt->execute()) {
        header("Location: login.php");
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
} else {
    header("Location: registration.php");
    exit;
}
?>
