<?php
include "header.php";
?>
<?php
function sanitizeInput($data) {
    global $conn;
    return mysqli_real_escape_string($conn, htmlspecialchars($data));
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $salutation = sanitizeInput($_POST["salutation"]);
    $firstname = sanitizeInput($_POST["fname"]);
    $lastname = sanitizeInput($_POST["lname"]);
    $email = sanitizeInput($_POST["email"]);
    $username = sanitizeInput($_POST["username"]);
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT);
    $birthday = sanitizeInput($_POST["birthday"]);

    $checkExisting = "SELECT * FROM users WHERE email = '$email' OR username = '$username'";
    $result = $conn->query($checkExisting);

    if ($result->num_rows > 0) {
        echo "Error: Email or username already exists. Please choose a different one.";
    } else {
        $currentDateTime = date("Y-m-d H:i:s");

        $sql = "INSERT INTO users (salutation, firstname, lastname, email, username, passwordSHA, birthday, acc_created) VALUES ('$salutation', '$firstname', '$lastname', '$email', '$username', '$password', '$birthday', '$currentDateTime')";

        if ($conn->query($sql) === TRUE) {
            echo "User registered successfully!";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
}
?>
<?php
include "footer.php";
?>
