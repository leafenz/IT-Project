<?php
    include "header.php";
?>
<h1>Registrierung</h1>

<!-- Form for the whole registration-process -->
<form name="registrieren" action="..." method="post">
    <div>
        <label for=""></label><br>
        <label for="female">
            <input type="radio" id="female" name="gender" value="w">
            Weiblich
        </label><br>
        <label for="male">
            <input type="radio" id="male" name="gender" value="m">
            Männlich
        </label><br>
        <label for="diverse">
            <input type="radio" id="diverse" name="gender" value="d">
            Divers
        </label>
    </div>
    <div>
        <label for="vorname">Vorname:</label><br>
        <input type="text" id="vorname" name="vorname" required><br>
    </div>
    <div>
        <label for="nachname">Nachname:</label><br>
        <input type="text" id="nachname" name="nachname" required><br>
    </div>
    <div>
        <label for="email">E-Mail-Adresse:</label><br>
        <input type="email" id="email" name="email" required><br>
    </div>
    <div>
        <label for="username">Username:</label><br>
        <input type="text" id="username" name="username" required><br>
    </div>
    <input type="hidden" id="typ" name="typ" value="1">
    <div>
        <label for="password">Passwort:</label><br>
        <input type="password" id="password" name="password" required><br>
        <label for="passwordwie">Passwort wiederholen:</label><br>
        <input type="password" id="password-repeat" name="password-repeat" required><br>
    </div><br>
    <div>

    </div>
    <button>Registrieren</button>
</form>

<?php
    include "footer.php";
?>