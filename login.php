<?php 
include "header.php";
?>

<body class="login-page">
    <h1>Login</h1>

    <form id="loginForm" action="login_action.php" method="post">
        <div class="infotext">
            <label for="username">Username:</label>
            <input type="text" name="username" id="username" required>
            <br>
            <label for="password">Password:</label>
            <input type="password" name="password" id="password" required>
            <br>
            <input type="submit" name="submit" id="submit" value="Login">
        </div>
    </form>

    <?php
    if (isset($_GET['error'])) {
        echo "<p style='color: red;'>".$_GET['error']."</p>";
    }
   ?>

</body>

<?php 
include "footer.php";
?>