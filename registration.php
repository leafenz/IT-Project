<?php 
include "header.php";

?>
<body>
<h1>Registration</h1>

<form id="registrationForm" action="registration-action.php" method="post">
    <div class="infotext">
        Salutation: <br>
        <input type="radio" id="woman" name="salutation" value="woman">
        <label for="woman">Woman</label><br>
        <input type="radio" id="men" name="salutation" value="men">
        <label for="men">Men</label><br>
        <input type="radio" id="divers" name="salutation" value="divers">
        <label for="divers">Divers</label><br>
        <label for="fname">First Name:</label>
        <input type="text" name="fname" id="fname" required>
        <br>
        <label for="lname">Last Name:</label>
        <input type="text" name="lname" id="lname" required>
        <br>
        <label for="email">Email:</label>
        <input type="email" name="email" id="email" required>
        <br>
        <label for="username">Username:</label>
        <input type="text" name="username" id="username" required>
        <br>
        <br>
        <label for="birthday">Birthday:</label>
        <input type="date" name="birthday" id="birthday" required>
        <br>
        <label for="password">Password:</label>
        <input type="password" name="password" id="password" required>
        <br>
        <label for="repeat_password">Repeat password:</label>
        <input type="password" name="repeat_password" id="repeat_password" required>
        <br>
        <input type="submit" name="submit" id="submit" value="Submit">
    </div>
</form>
</body>
<?php 
include "footer.php";
?>
